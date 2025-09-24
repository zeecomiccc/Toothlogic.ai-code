<?php

namespace Modules\Customer\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Appointment\Models\Appointment;
use Modules\Customer\Http\Requests\CustomerRequest;
use Modules\CustomField\Models\CustomField;
use Modules\CustomField\Models\CustomFieldGroup;
use Yajra\DataTables\DataTables;
use Modules\CustomForm\Models\CustomForm;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;
use Illuminate\Validation\ValidationException;
use Modules\Appointment\Trait\AppointmentTrait;
use Modules\NotificationTemplate\Models\NotificationTemplate;
use Modules\Wallet\Models\Wallet;
use Modules\Clinic\Models\Clinics;
use Modules\Customer\Models\OtherPatient;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Concerns\FromArray;

class CustomersController extends Controller
{
    use AppointmentTrait;
    // use Authorizable;
    protected string $exportClass = '\App\Exports\CustomerExport';

    public function __construct()
    {
        // Page Title
        $this->module_title = 'customer.singular_title';

        // module name
        $this->module_name = 'customers';

        // directory path of the module
        $this->module_path = 'customer::backend';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => 'fa-regular fa-sun',
            'module_name' => $this->module_name,
            'module_path' => $this->module_path,
        ]);
        $this->middleware(['permission:view_customer'])->only('index');
        $this->middleware(['permission:edit_customer'])->only('edit', 'update');
        $this->middleware(['permission:add_customer'])->only('store');
        $this->middleware(['permission:delete_customer'])->only('destroy');
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = __('messages.bulk_update');

        // dd($actionType, $ids, $request->status);
        switch ($actionType) {
            case 'change-status':
                $customer = User::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = __('messages.bulk_customer_update');
                break;

            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }
                User::whereIn('id', $ids)->delete();
                $message = __('messages.bulk_customer_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
                break;
        }

        return response()->json(['status' => true, 'message' => __('messages.bulk_update')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $module_action = 'List';
        $columns = CustomFieldGroup::columnJsonValues(new User());
        $customefield = CustomField::exportCustomFields(new User());
        $patients = User::role('user')->setRolePatients(auth()->user())->where('status', 1)->get();
        $email = $patients->where('user_type', 'user')->pluck('email');
        $contact = $patients->where('user_type', 'user')->pluck('mobile');

        $patientNames = $patients->where('user_type', 'user')->pluck('first_name', 'last_name')
            ->map(function ($firstName, $lastName) {
                return $firstName . ' ' . $lastName;
            })
            ->values()
            ->all();

        $export_import = true;
        $export_columns = [
            [
                'value' => 'Name',
                'text' => __('customer.lbl_name'),
            ],
            [
                'value' => 'mobile',
                'text' => __('customer.lbl_phone_number'),
            ],
            [
                'value' => 'email',
                'text' => __('appointment.lbl_email'),
            ],
            [
                'value' => 'status',
                'text' => __('clinic.lbl_status'),
            ],
        ];
        $import_columns = [
            [
                'value' => 'first_name',
                'text' => __('customer.lbl_first_name'),
            ],
            [
                'value' => 'last_name',
                'text' => __('customer.lbl_last_name'),
            ],
            [
                'value' => 'email',
                'text' => __('appointment.lbl_email'),
            ],
            [
                'value' => 'mobile',
                'text' => __('customer.lbl_phone_number'),
            ],
            [
                'value' => 'gender',
                'text' => __('customer.lbl_gender'),
            ],
            [
                'value' => 'date_of_birth',
                'text' => __('customer.lbl_date_of_birth'),
            ],
            [
                'value' => 'password',
                'text' => __('customer.lbl_password'),
            ],
        ];

        $export_url = route('backend.customers.export');
        $import_url = route('backend.customers.import-users');

        $otherPatients = OtherPatient::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get();

        return view('customer::backend.customers.index', compact('module_action', 'import_columns', 'columns', 'import_url', 'customefield', 'export_import', 'export_columns', 'export_url', 'patients', 'patientNames', 'email', 'contact', 'otherPatients'));
    }

    public function update_status(Request $request, User $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }

    public function index_list(Request $request)
    {
        $term = trim($request->q);

        // Need To Add Role Base

        if ($request->has('filter') && $request->filter == 'all') {

            $query_data = User::role(['user'])->with('media')->where(function ($q) use ($term) {
                if (!empty($term)) {
                    $q->orWhere('first_name', 'LIKE', "%$term%");
                    $q->orWhere('last_name', 'LIKE', "%$term%");
                }
            });
        } else {

            $query_data = User::role(['user'])->setRolePatients(auth()->user())->with('media')->where(function ($q) use ($term) {
                if (!empty($term)) {
                    $q->orWhere('first_name', 'LIKE', "%$term%");
                    $q->orWhere('last_name', 'LIKE', "%$term%");
                }
            });
        }




        $query_data = $query_data->where('status', 1)->get();

        $data = [];

        foreach ($query_data as $row) {
            $data[] = [
                'id' => $row->id,
                'name' => $row->full_name,
                'avatar' => $row->profile_image,
                'email' => $row->email,
                'mobile' => $row->mobile,
                'created_at' => $row->created_at,
            ];
        }

        return response()->json($data);
    }


    public function index_data(Datatables $datatable, Request $request)
    {
        $module_name = $this->module_name;
        $query = User::role('user')->setRolePatients(auth()->user());

        $customform = CustomForm::where('module_type', 'patient_module')
            ->where('status', 1)
            ->get();

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {

                $query->where('status', $filter['column_status']);
            }

            if (isset($filter['patient_name'])) {
                $nameParts = explode(' ', $filter['patient_name']);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                $query->where(function ($query) use ($firstName, $lastName) {
                    $query->where('first_name', 'like', '%' . $firstName . '%')
                        ->orWhere('last_name', 'like', '%' . $lastName . '%');
                });
            }
            if (isset($filter['email'])) {

                $query->where('email', $filter['email']);
            }
            if (isset($filter['contact'])) {

                $query->where('mobile', $filter['contact']);
            }
            if (isset($filter['other_patient'])) {
                $query->whereHas('otherPatients', function ($q) use ($filter) {
                    $q->where('id', $filter['other_patient'])
                        ->orWhere('user_id', $filter['other_patient']);
                });
            }
        }
        $query->orderBy('created_at', 'desc');

        $datatable = $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->addColumn('action', function ($data) use ($module_name, $customform) {
                return view('customer::backend.customers.action_column', compact('module_name', 'data', 'customform'));
            })

            ->editColumn('image', function ($data) {
                return "<img src='" . $data->profile_image . "'class='avatar avatar-50 rounded-pill'>";
            })

            ->editColumn('customer_id', function ($data) {
                return view('customer::backend.customers.user_id', compact('data'));
            })
            ->filterColumn('customer_id', function ($query, $keyword) {
                if (!empty($keyword)) {
                    $query->where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
                }
            })
            ->orderColumn('customer_id', function ($query, $order) {
                $query->orderByRaw("CONCAT(first_name, ' ', last_name) $order");
            }, 1)

            ->editColumn('email_verified_at', function ($data) {
                $checked = '';
                if ($data->email_verified_at) {
                    return '<span class="badge bg-success-subtle"><i class="fa-solid fa-envelope" style="margin-right: 2px"></i> ' . __('customer.msg_verified') . '</span>';
                }

                return '<button  type="button" data-url="' . route('backend.customers.verify-customer', $data->id) . '" data-token="' . csrf_token() . '" class="button-status-change btn btn-text-danger btn-sm  bg-danger-subtle"  id="datatable-row-' . $data->id . '"  name="is_verify" value="' . $data->id . '" ' . $checked . '>Verify</button>';
            })

            ->editColumn('is_banned', function ($data) {
                $checked = '';
                if ($data->is_banned) {
                    $checked = 'checked="checked"';
                }

                return '
                    <div class="form-check form-switch ">
                        <input type="checkbox" data-url="' . route('backend.customers.block-customer', $data->id) . '" data-token="' . csrf_token() . '" class="switch-status-change form-check-input"  id="datatable-row-' . $data->id . '"  name="is_banned" value="' . $data->id . '" ' . $checked . '>
                    </div>
                 ';
            })

            ->editColumn('status', function ($row) {
                $checked = '';
                if ($row->status) {
                    $checked = 'checked="checked"';
                }

                return '
                    <div class="form-check form-switch ">
                        <input type="checkbox" data-url="' . route('backend.customers.update_status', $row->id) . '" data-token="' . csrf_token() . '" class="switch-status-change form-check-input"  id="datatable-row-' . $row->id . '"  name="status" value="' . $row->id . '" ' . $checked . '>
                    </div>
                ';
            })
            ->editColumn('updated_at', function ($data) {
                $module_name = $this->module_name;

                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('llll');
                }
            })

            ->orderColumns(['id'], '-:column $1');

        // Custom Fields For export
        $customFieldColumns = CustomField::customFieldData($datatable, User::CUSTOM_FIELD_MODEL, null);

        return $datatable->rawColumns(array_merge(['action', 'status', 'is_banned', 'email_verified_at', 'check', 'image'], $customFieldColumns))
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->except('profile_image');
        $data = $request->all();

        $data['mobile'] = str_replace(' ', '', $data['mobile']);
        $data['user_type'] = 'user';

        // Handle default email if not provided
        if (empty($data['email'])) {
            $baseEmail = strtolower($data['first_name'] . $data['last_name']) . '@patient.com';
            $email = $baseEmail;
            $counter = 1;

            // Ensure email uniqueness
            while (User::where('email', $email)->exists()) {
                $email = strtolower($data['first_name'] . $data['last_name'] . $counter) . '@patient.com';
                $counter++;
            }
            $data['email'] = $email;
        }

        // Handle default password if not provided
        if (empty($data['password'])) {
            $data['password'] = '12345678';
        }

        $data['password'] = Hash::make($data['password']);
        $data = User::create($data);

        if ($data->user_type == 'user') {
            $wallet = [
                'title' => $data->first_name . ' ' . $data->last_name,
                'user_id' => $data->id,
                'amount' => 0
            ];
            Wallet::create($wallet);
        }

        $data->syncRoles(['user']);

        \Artisan::call('cache:clear');

        if ($request->custom_fields_data) {
            $data->updateCustomFieldData(json_decode($request->custom_fields_data));
        }
        if ($request->has('profile_image') && !empty($request->profile_image)) {
            storeMediaFile($data, $request->file('profile_image'), 'profile_image');
        }


        $message = __('messages.create_form', ['form' => __('customer.singular_title')]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);

        if (!is_null($data)) {
            $custom_field_data = $data->withCustomFields();
            $data['custom_field_data'] = collect($custom_field_data->custom_fields_data)
                ->filter(function ($value) {
                    return $value !== null;
                })
                ->toArray();
        }

        $data['profile_image'] = $data->profile_image;

        return response()->json(['data' => $data, 'status' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CustomerRequest $request, $id)
    {
        $data = User::findOrFail($id);

        $request_data = $request->except('profile_image', 'password');
        $request_data['mobile'] = str_replace(' ', '', $request_data['mobile']);

        // Handle empty email during update - keep existing email if not provided
        if (empty($request_data['email'])) {
            unset($request_data['email']); // Don't update email if empty
        }

        $data->update($request_data);


        if ($request->custom_fields_data) {
            $data->updateCustomFieldData(json_decode($request->custom_fields_data));
        }

        if ($request->hasFile('profile_image')) {
            storeMediaFile($data, $request->file('profile_image'), 'profile_image');
        }

        if ($request->profile_image == null) {
            $data->clearMediaCollection('profile_image');
        }

        $message = __('messages.update_form', ['form' => __('customer.singular_title')]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (env('IS_DEMO')) {
            return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
        }
        $data = User::findOrFail($id);

        $appointmentStatus = Appointment::where('user_id', $id)
            ->whereNotIn('status', ['checkout', 'check_in'])
            ->update(['status' => 'cancelled']);
        $data->tokens()->delete();

        $data->forceDelete();

        $message = __('messages.delete_form', ['form' => __('customer.singular_title')]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function trashed()
    {
        $module_name = $this->module_name;

        $module_name_singular = Str::singular($module_name);

        $module_action = 'Trash List';

        $data = User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate();

        return view('customer::backend.customers.trash', compact('data', 'module_name_singular', 'module_action'));
    }

    /**
     * Restore a soft deleted entry.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($id)
    {
        $module_action = 'Restore';

        $data = User::withTrashed()->find($id);
        $data->restore();

        return redirect('app/customers');
    }

    public function change_password(Request $request)
    {
        $data = $request->all();

        $user_id = $data['user_id'];

        $data = User::findOrFail($user_id);

        $request_data = $request->only('password');
        $request_data['password'] = Hash::make($request_data['password']);

        $data->update($request_data);

        $message = __('messages.password_update');

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function block_customer(Request $request, User $id)
    {
        $id->update(['is_banned' => $request->status]);

        if ($request->status == 1) {
            $message = __('messages.google_blocked');
        } else {
            $message = __('messages.google_unblocked');
        }

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function verify_customer(Request $request, $id)
    {
        $data = User::findOrFail($id);

        $current_time = Carbon::now();

        $data->update(['email_verified_at' => $current_time]);

        return response()->json(['status' => true, 'message' => __('messages.customer_verify')]);
    }
    public function patient_detail($id)
    {
        $patient = User::with(['appointment', 'profile', 'userrating'])->findOrFail($id);

        // Get the most booked doctors for the selected patient
        $topDoctors = $patient->appointment
            ->groupBy('doctor_id')
            ->map(fn($appointments) => $appointments->count())
            ->filter(fn($count) => $count >= 5)
            ->sortDesc()
            ->take(3);

        // Fetch the doctor details
        $doctorDetails = User::whereIn('id', $topDoctors->keys())->with('employeeAppointment')->get();

        // Get the most visited clinics for the selected patient
        $topClinics = $patient->appointment
            ->groupBy('clinic_id')
            ->map(fn($appointments) => $appointments->count())
            ->filter(fn($count) => $count >= 5)
            ->sortDesc()
            ->take(3);

        // Fetch the clinic details
        $clinicDetails = Clinics::whereIn('id', $topClinics->keys())->get();
        $data = [
            'totalAppointments' => $patient->appointment->count(),
            'topDoctors' => $doctorDetails, // Top 3 booked doctors
            'topClinics' => $clinicDetails, // Top 3 visited clinics
            'cancelledAppointments' => $patient->appointment->where('status', 'cancelled')->count(),
            'completedAppointments' => $patient->appointment->where('status', 'checkout')->count(),
            'upcomingAppointments' => $patient->appointment->where('status', 'confirmed')->count(),
            'patientInfo' => [
                'name' => $patient->first_name . ' ' . $patient->last_name,
                'email' => $patient->email,
                'contact' => $patient->mobile,
                'dob' => $patient->date_of_birth,
                'id' => $patient->id,
            ],
        ];

        $otherPatients = OtherPatient::where('user_id', $id)->with('appointments')->get();

        return view('customer::backend.customers.patient_detail', compact('data', 'patient', 'otherPatients'));
    }


    public function updateOtherPatient(Request $request, $id)
    {
        $otherPatient = OtherPatient::findOrFail($id);
        $otherPatient->first_name = $request->input('first_name');
        $otherPatient->last_name = $request->input('last_name');
        $otherPatient->contactNumber = $request->input('contactNumber');
        $otherPatient->relation = $request->input('relation');
        $otherPatient->dob = $request->input('dob');
        $otherPatient->gender = $request->input('gender');
        if ($request->hasFile('profile_image')) {
            $otherPatient->clearMediaCollection('profile_image');
            $otherPatient->addMediaFromRequest('profile_image')->toMediaCollection('profile_image'); // Upload new image
        }
        $otherPatient->save();

        return response()->json([
            'status' => true,
            'message' =>  __('messages.member_updated'),
        ], 200);
    }

    public function delete($id)
    {
        try {
            $patient = OtherPatient::findOrFail($id);
            $patient->delete();

            return response()->json([
                'status' => true,
                'message' => __('messages.tips_data_delete'),
                'title' => __('messages.deleted')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('messages.delete_failed'),
                'title' => __('messages.error')
            ], 500);
        }
    }


    // public function customerDetails(Request $request, $id)
    // {

    //     $data = User::with(['profile', 'media', 'appointment', 'userrating'])->findOrFail($id);

    //     $data->total_appointment = $data->appointment->count();
    //     $data->appointments = $data->appointment;
    //     $data->total_rating = $data->userrating->count();

    //     return response()->json(['data' => $data, 'status' => true]);
    // }


    public function customerDetails(Request $request, $id)
    {

        $data = User::with(['profile', 'appointment', 'media', 'userrating'])->findOrFail($id);

        $appointment = Appointment::SetRole(auth()->user())->with('clinicservice', 'appointmenttransaction', 'cliniccenter', 'doctor')->where('user_id', $id)->get();


        $data->total_appointment = count($appointment);
        $data->appointments = $appointment;
        $data->total_rating = $data->userrating->count();

        return response()->json(['data' => $data, 'status' => true]);
    }

    public function importUsers(Request $request)
    {
        // Log::info($request->all());

        // Validate the request
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods,html|max:2048', // Include html as an accepted file type
            'file_format' => 'required|in:csv,xlsx,xls,ods,html', // Include html in the allowed file formats
        ]);

        $file = $request->file('file');
        $fileFormat = $request->input('file_format');

        $userImport = new UserImport();

        try {

            // Import the file based on its format
            if ($fileFormat === 'html') {
                // Import using Maatwebsite's HTML reader
                Excel::import($userImport, $file, null, \Maatwebsite\Excel\Excel::HTML);
            } else {
                // Handle other formats (csv, xlsx, xls, ods)
                Excel::import($userImport, $file);
            }

            $importedUsers = $userImport->getImportedUsers();

            if ($importedUsers) {
                foreach ($importedUsers as $importedUserData) {
                    $user = $importedUserData['user'];
                    $plaintextPassword = $importedUserData['plaintext_password'];

                    $notificationData = [
                        'user_id' => $user->id,
                        'user_name' => $user->first_name . ' ' . $user->last_name,
                        'user_email' => $user->email,
                        'password' => $plaintextPassword,
                    ];

                    $notification_template = NotificationTemplate::where('type', 'resend_user_credentials')->first();
                    $channels = $notification_template->channels;

                    if ($request->email_notification) {
                        $channels['IS_MAIL'] = "1";

                        $notification_template->channels = $channels;
                        $notification_template->save();

                        $this->sendNotificationOnBookingUpdate('resend_user_credentials', $notificationData);
                    }
                    $channels['IS_MAIL'] = "0";
                    $notification_template->channels = $channels;
                    $notification_template->save();

                    if ($request->sms_notification) {
                        $channels['IS_SMS'] = "1";
                        $channels['IS_WHATSAPP'] = "1";

                        $notification_template->channels = $channels;
                        $notification_template->save();

                        $this->sendNotificationOnBookingUpdate('resend_user_credentials', $notificationData);
                    }

                    $channels['IS_SMS'] = "0";
                    $channels['IS_WHATSAPP'] = "0";
                    $notification_template->channels = $channels;
                    $notification_template->save();
                }
            }

            return response()->json(['message' => 'File imported successfully!']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during import: ' . $e->getMessage()], 500);
        }
    }

    public function downloadSample(Request $request, $type)
    {
        $fileName = "sample.$type";

        $export = new class implements FromArray {
            public function array(): array
            {
                return [
                    ['first_name', 'last_name', 'email', 'mobile', 'gender', 'date_of_birth', 'password'],
                    ['demo1', 'demo1', 'demo1@example.com', '1234567890', 'Male', '1990-01-01', 'password1'],
                    ['demo2', 'demo2', 'demo2@example.com', '0987654321', 'Female', '1992-02-02', 'password2'],
                    ['demo3', 'demo3', 'demo3@example.com', '1122334455', 'Male', '1994-03-03', 'password3'],
                    ['demo4', 'demo4', 'demo4@example.com', '2233445566', 'Female', '1996-04-04', 'password4'],
                    ['demo5', 'demo5', 'demo5@example.com', '3344556677', 'Male', '1998-05-05', 'password5'],
                ];
            }
        };

        switch ($type) {
            case 'csv':
                return Excel::download($export, $fileName, ExcelFormat::CSV);

            case 'xlsx':
                return Excel::download($export, $fileName, ExcelFormat::XLSX);

            case 'xls':
                return Excel::download($export, $fileName, ExcelFormat::XLS);

            case 'ods':
                return Excel::download($export, $fileName, ExcelFormat::ODS);

            case 'html':
                return Excel::download($export, $fileName, ExcelFormat::HTML);

            default:
                return response()->json(['error' => 'Invalid file type'], 400);
        }
    }


    public function otherpatient(Request $request)
    {


        $data = $request->except('profile_image');

        $otherPatient = OtherPatient::create($data);


        if ($request->hasFile('profile_image')) {
            storeMediaFile($otherPatient, $request->file('profile_image'), 'profile_image');
        }

        return redirect()
            ->route('backend.customers.patient_detail', $otherPatient->user_id)
            ->with('success', __('messages.member_added'));
    }

    public function patientHistory($id)
    {
        $patient = \App\Models\User::findOrFail($id);
        return view('customer::backend.customers.patient_history', compact('patient'));
    }
}
