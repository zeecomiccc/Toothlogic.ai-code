<?php

namespace Modules\Clinic\Http\Controllers;

use App\Authorizable;
use App\Models\User;

use Modules\Service\Models\SystemServiceCategory;
use Modules\Clinic\Models\Clinics;
use App\Http\Controllers\Controller;
use Modules\Clinic\Http\Requests\ClinicRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Modules\CustomField\Models\CustomField;
use Modules\CustomField\Models\CustomFieldGroup;
use Modules\Clinic\Models\ClinicGallery;
use Modules\Clinic\Models\ClinicsService;
use Modules\Clinic\Models\DoctorClinicMapping;
use Illuminate\Support\Str;
use Modules\Clinic\Models\ClinicSession;
use Modules\MultiVendor\Models\MultiVendor;
use Modules\World\Models\Country;
use Modules\Clinic\Trait\ClinicTrait;

class ClinicesController extends Controller
{
    use ClinicTrait;

    protected string $exportClass = '\App\Exports\ClinicExport';
    public function __construct()
    {
        // Page clinic_name

        // module name
        $this->module_name = 'clinics';

        // module icon
        $this->module_icon = 'fa-solid fa-clipboard-list';

        view()->share([
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);

        $this->middleware(['permission:view_clinics_center'])->only('index');
        $this->middleware(['permission:edit_clinics_center'])->only('edit', 'update');
        $this->middleware(['permission:add_clinics_center'])->only('store');
        $this->middleware(['permission:delete_clinics_center'])->only('destroy');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $module_action = 'List';
        $user = auth()->user();

        $module_title = 'clinic.singular_title';

        // if ($user->user_type === 'vendor') {
        //     $module_title = 'clinic.Locations';
        // } else {
        //     $module_title = 'clinic.singular_title';
        // }


        $filter = [
            'status' => $request->status,
        ];
        $columns = CustomFieldGroup::columnJsonValues(new Clinics());

        $customefield = CustomField::exportCustomFields(new Clinics());
        $categoryName = SystemServiceCategory::get();
        $countries = Country::all();
        $clinicadmin = User::role(['vendor'])->get();

        $export_import = true;
        $export_columns = [
            [
                'value' => 'name',
                'text' => __('clinic.lbl_name'),
            ],
            [
                'value' => 'system_service_category',
                'text' => __('clinic.speciality'),
            ],

            [
                'value' => 'description',
                'text' => __('clinic.lbl_description'),
            ],
            [
                'value' => 'contact_number',
                'text' => __('clinic.lbl_contact_number'),
            ],

        ];

        if (multiVendor() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('demo_admin'))) {
            $export_columns[] = [
                'value' => 'vendor_id',
                'text' => __('multivendor.singular_title'),
            ];
        }

        $export_columns[] = [
            'value' => 'status',
            'text' => __('service.lbl_status'),
        ];
        $export_url = route('backend.clinics.export');
        return view('clinic::backend.clinic.index_datatable', compact('module_action', 'module_title', 'clinicadmin', 'filter', 'columns', 'customefield', 'export_import', 'export_columns', 'categoryName', 'export_url', 'countries'));
    }


    public function index_list(Request $request)
    {

        $userId = auth()->id();

        $query_data = Clinics::SetRole(auth()->user())->with('clinicdoctor', 'specialty', 'clinicdoctor', 'receptionist');

        if ($request->filled('doctor_id') && $request->doctor_id != null) {
            $doctor_id = $request->doctor_id;
            $query_data->whereHas('clinicdoctor', function ($query) use ($doctor_id) {
                $query->where('doctor_id', $doctor_id);
            });
        }
        if ($request->has('vendor_id')) {
            $vendor_id = $request->vendor_id;

            // Check if multiVendor is enabled
            if (multiVendor() == "1") {
                // Check if vendor_id is not null
                if ($vendor_id !== null) {
                    $user = User::find($vendor_id);

                    // Check user existence and type
                    if ($user && in_array($user->user_type, ['admin', 'demo_admin'])) {
                        $user_ids = User::role(['admin', 'demo_admin'])->pluck('id');
                        $query_data->whereIn('vendor_id', $user_ids);
                    } else {
                        $query_data->where('vendor_id', $vendor_id);
                    }
                }
                // else {
                //     $query_data->where('id', $request->clinic_id);
                // }
            }
        }
        if ($request->has('clinicId')) {
            $clinicIds = explode(",", $request->clinicId);
            $query_data->whereIn('id', $clinicIds);
        }

        if ($request->filled('clinic_id') && $request->clinic_id !== null) {
            $clinicIds = explode(",", $request->clinic_id);
            if (auth()->user()->hasRole('receptionist')) {
                $query_data->with('receptionist')->whereHas('receptionist', function ($qry) use ($clinicIds) {
                    $qry->where('clinic_id', $clinicIds);
                });
            }
            $query_data->whereIn('id', $clinicIds);
        }
        if ($request->has('clinicid')) {

            $query_data->where('id', $request->clinicid);
        }
        $query_data = $query_data->where('status', 1)->get();

        $data = [];

        foreach ($query_data as $row) {
            $data[] = [

                'id' => $row->id,
                'clinic_name' => $row->name,
                'description' => $row->description,
                'status' => $row->status,
                'avatar' => $row->file_url,
                'address' => $row->address,

            ];
        }

        if ($request->is('api/*')) {
            return response()->json(['status' => true, 'data' => $data, 'message' => __('clinic_list')]);
        }

        return response()->json($data);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $userId = auth()->id();
        $query = Clinics::SetRole(auth()->user())->with('clinicdoctor', 'specialty', 'clinicdoctor', 'receptionist');

        $filter = $request->filter;

        if (isset($filter)) {

            if (isset($filter['clinic_name'])) {
                $query->where('id', $filter['clinic_name']);
            }

            if (isset($filter['category_name'])) {
                $query->where('system_service_category', $filter['category_name']);
            }

            if (isset($filter['clinic_admin'])) {
                $query->where('vendor_id', $filter['clinic_admin']);
            }

            if (isset($filter['country'])) {

                $query->where('country', $filter['country']);
            }


            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }


        return $datatable->eloquent($query)
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $data->id . '"  name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
            })
            ->addColumn('action', function ($data) {
                return view('clinic::backend.clinic.action_column', compact('data'));
            })
            ->editColumn('clinic_name', function ($data) {
                return view('clinic::backend.clinic.clinic_id', compact('data'));
            })
            ->addColumn('receptionist', function ($data) {
                if ($data->receptionist && $data->receptionist->users) {
                    $name = $data->receptionist->users->full_name ?? $data->receptionist->users->name ?? '--';
                    $email = $data->receptionist->users->email ?? '--';
                    return '<div class="text-start"><div>' . e($name) . '</div><small>' . e($email) . '</small></div>';
                }
                return '--';
            })
            ->editColumn('system_service_category', function ($data) {
                $categoryName = optional($data->specialty)->name;
                return $categoryName ?? '-';
            })
            ->editColumn('description', function ($data) {
                return '<span>' . ($data->description ?? '-') . '</span>';
            })
            ->editColumn('vendor_id', function ($data) {
                return optional($data->vendor)->full_name;
            })
            ->editColumn('status', function ($data) {

                $checked = '';
                if ($data->status) {
                    $checked = 'checked="checked"';
                }

                return '
                    <div class="form-check form-switch ">
                        <input type="checkbox" data-url="' . route('backend.clinics.update_status', $data->id) . '" data-token="' . csrf_token() . '" class="switch-status-change form-check-input"  id="datatable-row-' . $data->id . '"  name="status" value="' . $data->id . '" ' . $checked . '>
                    </div>
                ';
            })
            ->editColumn('updated_at', function ($data) {


                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('llll');
                }
            })

            ->filterColumn('clinic_name', function ($query, $keyword) {
                if (!empty($keyword)) {
                    $query->where('name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
                }
            })
            ->orderColumn('clinic_name', function ($query, $order) {
                $query->orderBy('name', $order);
            }, 1)
            ->rawColumns(['action', 'clinic_name', 'status', 'check', 'image', 'description', 'receptionist'])
            ->orderColumns(['id'], '-:column $1')
            ->toJson();

        $customFieldColumns = CustomField::customFieldData($datatable, Clinics::CUSTOM_FIELD_MODEL, null);

        return $datatable->rawColumns(array_merge(['description', 'action', 'status', 'clinic_name', 'system_service_category', 'vendor_id', 'contact_number'], $customFieldColumns))
            ->toJson();
    }



    public function update_status(Request $request, Clinics $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('clinic.clinic_status')]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clinics::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClinicRequest $request)
    {

        // $uploadedFiles = $request->file('file_url');
        $data = $request->except(['file_url', 'brand_mark']);
        $data['slug'] = strtolower(Str::slug($request->clinic_name, '-'));
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('demo_admin')) {
            $data['vendor_id'] = $request->filled('vendor_id') ? $request->vendor_id : auth()->user()->id;
        } else {
            $data['vendor_id'] = auth()->user()->id;
        }
        $categoryName = $request->input('system_service_category');
        $category = SystemServiceCategory::where('name', $categoryName)->first();
        $data['system_service_category'] = $category ? $category->id : null;

        $clinic = Clinics::create($data);


        if ($request->custom_fields_data) {
            $clinic->updateCustomFieldData(json_decode($request->custom_fields_data));
        }

        if ($request->hasFile('file_url')) {
            storeMediaFile($clinic, $request->file('file_url'));
        }

        if ($request->hasFile('brand_mark')) {
            storeMediaFile($clinic, $request->file('brand_mark'), 'brand_mark');
        }


        $days = [
            ['day' => 'monday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
            ['day' => 'tuesday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
            ['day' => 'wednesday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
            ['day' => 'thursday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
            ['day' => 'friday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
            ['day' => 'saturday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
            ['day' => 'sunday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => true, 'breaks' => []],
        ];


        foreach ($days as $key => $val) {

            $val['clinic_id'] = $clinic->id;

            ClinicSession::create($val);
        }


        $message = __('messages.create_form', ['form' => __('clinic.singular_title')]);

        if ($request->is('api/*')) {
            return response()->json(['message' => $message, 'data' => $data, 'status' => true], 200);
        } else {
            return response()->json(['message' => $message, 'status' => true], 200);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('clinic::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $module_action = 'Edit';

        $data = Clinics::with('specialty')->findOrFail($id); // Assuming the relationship name is specialty
        $data['system_service_category'] = optional($data->specialty)->name;
        return response()->json(['data' => $data, 'status' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $data = Clinics::findOrFail($id);

        $request_data = $request->except(['file_url', 'brand_mark']);
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('demo_admin')) {
            $request_data['vendor_id'] = $request->has('vendor_id') ? $request->vendor_id : auth()->user()->id;
        }
        $categoryName = $request->input('system_service_category');
        $category = SystemServiceCategory::where('name', $categoryName)->first();
        $request_data['system_service_category'] = $category ? $category->id : null;
        $data->update($request_data);

        if ($request->hasFile('file_url')) {

            storeMediaFile($data, $request->file('file_url'));
        }

        if ($request->hasFile('brand_mark')) {
            storeMediaFile($data, $request->file('brand_mark'), 'brand_mark');
        }

        if ($request->is('api/*')) {
            if ($request->file_url && $request->file_url == null) {
                $data->clearMediaCollection('file_url');
            }
            if ($request->brand_mark && $request->brand_mark == null) {
                $data->clearMediaCollection('brand_mark');
            }
        } else {
            if ($request->file_url == null) {
                $data->clearMediaCollection('file_url');
            }
            if ($request->brand_mark == null) {
                $data->clearMediaCollection('brand_mark');
            }
        }

        if ($request->custom_fields_data) {
            $data->updateCustomFieldData(json_decode($request->custom_fields_data));
        }

        $clinicSession = ClinicSession::where('clinic_id', $id)->get();

        if ($clinicSession->isEmpty()) {

            $days = [
                ['day' => 'monday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
                ['day' => 'tuesday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
                ['day' => 'wednesday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
                ['day' => 'thursday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
                ['day' => 'friday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
                ['day' => 'saturday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => false, 'breaks' => []],
                ['day' => 'sunday', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'is_holiday' => true, 'breaks' => []],
            ];

            foreach ($days as $key => $val) {

                $val['clinic_id'] = $id;

                ClinicSession::create($val);
            }
        }

        $message = __('messages.update_form', ['form' => __('clinic.singular_title')]);

        if ($request->is('api/*')) {
            return response()->json(['message' => $message, 'data' => $data, 'status' => true], 200);
        } else {
            return response()->json(['message' => $message, 'status' => true], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (\Auth::user()->hasAnyRole(['demo_admin'])) {

            return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
        }
        $data = Clinics::where('id', $id)->delete();

        $message = __('clinic.clinic_delete');;

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = __('messages.bulk_update');

        switch ($actionType) {
            case 'change-status':
                $clinic = Clinics::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = __('clinic.clinic_status');
                break;

            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }
                Clinics::whereIn('id', $ids)->delete();
                $message = __('clinic.clinic_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
                break;
        }

        return response()->json(['status' => true, 'message' => $message]);
    }


    public function getGalleryImages($id)
    {
        $clinic = Clinics::findOrFail($id);

        $data = ClinicGallery::where('clinic_id', $id)->get();

        return response()->json(['data' => $data, 'clinic' => $clinic, 'status' => true]);
    }

    public function uploadGalleryImages(Request $request, $id)
    {

        $gallery = collect($request->gallery, true);

        $images = ClinicGallery::where('clinic_id', $id)->whereNotIn('id', $gallery->pluck('id'))->get();

        foreach ($images as $key => $value) {
            $value->clearMediaCollection('gallery_images');
            $value->delete();
        }

        foreach ($gallery as $key => $value) {
            if ($value['id'] == 'null') {
                $clinicGallery = ClinicGallery::create([
                    'clinic_id' => $id,
                ]);

                $clinicGallery->addMedia($value['file'])->toMediaCollection('gallery_images');

                $clinicGallery->full_url = $clinicGallery->getFirstMediaUrl('gallery_images');
                $clinicGallery->save();
            }
        }

        return response()->json(['message' => __('clinic.update_clinic_gallery'), 'status' => true]);
    }

    public function clinicDetails(Request $request, $id)
    {
        $data = Clinics::with('cities', 'states', 'countries', 'specialty', 'clinicsessions')->findOrFail($id);

        $data->pincode = $data->pincode ?? '-';
        $data->city = optional($data->cities)->name ? optional($data->cities)->name : '-';
        $data->state = optional($data->states)->name ? optional($data->states)->name : '-';
        $data->country = optional($data->countries)->name ? optional($data->countries)->name : '-';
        $data->system_service_category = optional($data->specialty)->name ? optional($data->specialty)->name : '-';

        $data->clinic_sessions = $this->getClinicSession($data->clinicsessions);

        return response()->json(['data' => $data, 'status' => true]);
    }

    public function clinicList(Request $request)
    {
        if (auth()->user() !== null) {

            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('demo_admin')) {
                $data = ClinicsService::where('status', 1)->get();
            } elseif (auth()->user()->hasRole('vendor')) {
                $id = auth()->user()->id;
                $data = ClinicsService::Where('vendor_id', $id)->where('status', 1)->get();
            }
        }
        return response()->json(['data' => $data, 'status' => true]);
    }
}
