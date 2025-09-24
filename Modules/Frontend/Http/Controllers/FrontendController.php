<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Blog\Models\Blog;
use Modules\Clinic\Models\ClinicsCategory;
use Modules\Clinic\Models\Clinics;
use Modules\Clinic\Models\Doctor;
use Modules\Clinic\Models\DoctorRating;
use Modules\Appointment\Models\Appointment;
use Modules\Clinic\Models\ClinicsService;
use Modules\FAQ\Models\Faqs;
use Modules\Slider\Models\Slider;
use Modules\FrontendSetting\Models\FrontendSetting;
use App\Models\Setting;
use Modules\Page\Models\Page;


class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allservice = ClinicsService::with('category')->where('is_enable_advance_payment', 0)->get();
        $allclinics = Clinics::get();
        $doctors = Doctor::with('user')->take(7)->get();

        $ratings = DoctorRating::with('user', 'doctor')->orderby('experience_rating', 'desc')->take(5)->get();

        $sliders = Slider::where('status', 1)->get();

        $sectionData = [];
        $sectionKeys = ['section_1', 'section_2', 'section_3', 'section_4', 'section_5', 'section_6', 'section_7','section_8', 'section_9'];

        foreach ($sectionKeys as $key) {
            $section = FrontendSetting::where('key', $key)->first();
            $sectionData[$key] = $section ? json_decode($section->value, true) : null;
        }

        foreach ($doctors as $doctor) {
            $doctor_id = optional($doctor->user)->id;
            $reviews = DoctorRating::where('doctor_id', $doctor_id)->get();
            $average_rating = $reviews->avg('experience_rating');
            $doctor->average_rating = $average_rating;
            $total_appointment = Appointment::where('doctor_id', $doctor_id)->where('status', 'checkout')->count();
            $doctor->total_appointment = $total_appointment;
        }

        $faqs = faqs::where('status', 1)->orderBy('created_at', 'asc')->take(4)->get();

        $blogs = Blog::with('author')->where('status', 1)->take(7)->get();

        $paymentMethodsList = [
            'cash' => 'cash_payment_method',  // Always available
            'Wallet' => 'wallet_payment_method', // Always available
            'Stripe' => 'str_payment_method',
            'Paystack' => 'paystack_payment_method',
            'PayPal' => 'paypal_payment_method',
            'Flutterwave' => 'flutterwave_payment_method',
            'Airtel' => 'airtel_payment_method',
            'PhonePay' => 'phonepay_payment_method',
            'Midtrans' => 'midtrans_payment_method',
            'Cinet' => 'cinet_payment_method',
            'Sadad' => 'sadad_payment_method',
            'Razor' => 'razor_payment_method',
        ];

        $enabledPaymentMethods = ['cash', 'Wallet'];
        foreach ($paymentMethodsList as $displayName => $settingKey) {
            if (setting($settingKey, 0) == 1) {
                $enabledPaymentMethods[] = $displayName;
            }
        }
        $data = [
            'allservice' => $allservice,
            'allclinics' => $allclinics,
            'doctors' => $doctors,
            'faqs' => $faqs,
            'blogs' => $blogs,
            'paymentMethods' => $enabledPaymentMethods,
            'sliders' => $sliders,
            'ratings' => $ratings,
            'sectionData' => $sectionData
        ];

        return view('frontend::index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('frontend::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('frontend::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function searchList()
    {
        return view('frontend::search');
    }

    public function faqList()
    {
        $faqs = faqs::where('status', 1)->get();
        return view('frontend::faq', compact('faqs'));
    }

    public function aboutUs()
    {
        $about_us=Page::where('slug','about-us')->first();

        return view('frontend::about_us', compact('about_us'));
    }

    public function contactUs()
    {
        $settings = Setting::whereIn('type', ['bussiness', 'string'])->pluck('val', 'name')->toArray();
        return view('frontend::contact_us', compact('settings'));
    }
    public function getSearch(Request $request)
    {
        $category_list = ClinicsCategory::query()->where('status', 1);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $category_list->where('name', 'like', "%{$searchTerm}%");
        }

        // Execute the query and get the results
        $categories = $category_list->orderBy('updated_at', 'desc')->get();

        $clinics_list = Clinics::CheckMultivendor()->where('status', 1);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $clinics_list->where('name', 'like', "%{$searchTerm}%");
        }

        // Execute the query and get the results
        $Clinics = $clinics_list->orderBy('updated_at', 'desc')->get();

        $service_list = ClinicsService::CheckMultivendor()->with('category')->where('status', 1);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $service_list->where('name', 'like', "%{$searchTerm}%");
        }

        // Execute the query and get the results
        $service = $service_list->orderBy('updated_at', 'desc')->get();

        $doctor_list = Doctor::CheckMultivendor()->with('user', 'doctorclinic', 'doctorService')->where('status', 1);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $doctor_list->whereHas('user', function ($query) use ($searchTerm) {
                $query->whereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", [strtolower("%{$searchTerm}%")]);
            });

        }

        // Execute the query and get the results
        $doctors = $doctor_list->orderBy('updated_at', 'desc')->get();

        $html = '';
        if ($categories->isNotEmpty()) {
            foreach ($categories as $index => $category) {
                $html .= '<div class="col">';
                $html .= '<a href="' . route('category-details', ['id' => $category->id]) . '" class="d-block text-decoration-none">'; // Replaced the first div with an anchor tag
                $html .= '<div class="serach-card p-3 text-center rounded">'; // Inner card structure remains
                $html .= '<div class="position-relative">';
                $html .= '<div class="d-block clinics-img">';
                $html .= '<img src="' . (!empty($category->file_url) ? $category->file_url : default_file_url()) . '" alt="category-image" class="w-100 rounded object-cover">';
                $html .= '</div>';
                $html .= '<h6 class="clinics-title line-count-1 mt-3 mb-2 pb-1">';
                $html .= htmlspecialchars($category->name ?? '') . '</h6>'; // Removed nested anchor
                $html .= '<p class="mb-0 text-capitalize line-count-2 font-size-14">';
                $html .= 'Checkups are vital for early detection & wellness</p>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</a>'; // Close the anchor tag
                $html .= '</div>';
            }
        }

        // Clinics Section
        if ($Clinics->isNotEmpty()) {
            foreach ($Clinics as $index => $clinic) {
                $html .= '<div class="col">';
                $html .= '<a href="' . route('clinic-details', ['id' => $clinic->id]) . '" class="d-block text-decoration-none">'; // Replaced the first div with an anchor tag
                $html .= '<div class="serach-card p-3 text-center rounded">'; // Inner card structure remains
                $html .= '<div class="position-relative">';
                $html .= '<div class="d-block">';
                $html .= '<img src="' . (!empty($clinic->file_url) ? $clinic->file_url : default_file_url()) . '" alt="clinic-image" class="w-100 rounded object-cover">';
                $html .= '</div>';
                $html .= '<h6 class="line-count-1 mt-3 mb-2 pb-1">';
                $html .= htmlspecialchars($clinic->name ?? '') . '</h6>'; // Removed nested anchor
                $html .= '<p class="mb-0 text-capitalize line-count-2 font-size-14 text-body">';
                $html .= htmlspecialchars($clinic->description ?? '') . '</p>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</a>'; // Close the anchor tag
                $html .= '</div>';
            }
        }

        // service Section
        if ($service->isNotEmpty()) {
            foreach ($service as $index => $service) {
                $html .= '<div class="col">';
                $html .= '<a href="' . route('service-details', ['id' => $service->id]) . '" class="d-block text-decoration-none">'; // Replaced the first div with an anchor tag
                $html .= '<div class="serach-card p-3 text-center rounded">'; // Inner card structure remains
                $html .= '<div class="position-relative">';
                $html .= '<div class="d-block">';
                $html .= '<img src="' . (!empty($service->file_url) ? $service->file_url : default_file_url()) . '" alt="service-image" class="w-100 rounded object-cover">';
                $html .= '</div>';
                $html .= '<h6 class="line-count-1 mt-3 mb-2 pb-1">';
                $html .= htmlspecialchars($service->name ?? '') . '</h6>'; // Removed nested anchor
                $html .= '<p class="mb-0 text-capitalize line-count-2 font-size-14 text-body">';
                $html .= htmlspecialchars($service->description ?? '') . '</p>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</a>'; // Close the anchor tag
                $html .= '</div>';
            }
        }

        if ($doctors->isNotEmpty()) {
            foreach ($doctors as $index => $doctor) {
                $html .= '<div class="col">';
                $html .= '<a href="' . route('doctor-details', ['id' => $doctor->id]) . '" class="d-block text-decoration-none">'; // Replaced the first div with an anchor tag
                $html .= '<div class="serach-card p-3 text-center rounded">'; // Inner card structure remains
                $html .= '<div class="position-relative">';
                $html .= '<div class="d-block">';
                $html .= '<img src="' . (!empty(optional($doctor->user)->profile_image) ? optional($doctor->user)->profile_image : user_avatar()) . '" alt="doctor-image" class="w-100 rounded object-cover">';
                $html .= '</div>';
                $html .= '<h6 class="line-count-1 mt-3 mb-2 pb-1">';
                $html .= htmlspecialchars(optional($doctor->user)->first_name . ' ' . optional($doctor->user)->last_name ?? 'Unkown') . '</h6>'; // Removed nested anchor
                $html .= '<p class="mb-0 text-capitalize line-count-2 font-size-14 text-body">';
                $html .= htmlspecialchars(optional(optional($doctor->user)->profile)->expert ?? '') . '</p>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</a>'; // Close the anchor tag
                $html .= '</div>';
            }
        }

        // Handle case where no other data types have results
        if (empty($categories) && empty($Clinics)) {
            $html .= '';
        }

        return response()->json([
            'status' => true,
            'html' => $html,
            'message' => __('movie.search_list'),
        ], 200);
    }
}
