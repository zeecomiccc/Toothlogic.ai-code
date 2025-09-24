<?php

namespace Modules\Appointment\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Modules\Clinic\Models\Clinics;
use App\Models\BodyChartSetting;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Appointment\Models\AppointmentPatientBodychart;
use Modules\Clinic\Models\Doctor;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Response;
use Modules\CustomField\Models\CustomField;
use Modules\CustomField\Models\CustomFieldGroup;
use Modules\Appointment\Models\Appointment;
use Modules\Appointment\Models\AppointmentTransaction;
use Modules\Appointment\Models\AppointmentPatientRecord;
use Modules\Commission\Models\CommissionEarning;
use Modules\Clinic\Models\ClinicsService;
use Modules\Constant\Models\Constant;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceEmail;
use Illuminate\Support\Facades\File;
use Modules\Appointment\Models\PatientEncounter;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Modules\CustomForm\Models\CustomForm;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Imports\AppointmentImport;

class ClinicAppointmentController extends Controller
{
    protected string $exportClass = '\App\Exports\ClinicAppointmentExport';

    public function __construct()
    {
        // Page Title
        $this->module_title = 'appointment.singular_title';
        // module name
        $this->module_name = 'appointments';

        // module icon
        $this->module_icon = 'fa-solid fa-clipboard-list';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];

        $module_action = 'List';
        $columns = CustomFieldGroup::columnJsonValues(new Appointment());
        $doctor = Doctor::SetRole(auth()->user())->where('status', 1)->with('user')->get();
        $customefield = CustomField::exportCustomFields(new Appointment());
        $service = ClinicsService::SetRole(auth()->user())->where('status', 1)->get();
        $userId = auth()->id();

        $user_id = $request->user_id ?? null;
        $hidePatientColumn = false;
        if ($user_id) {
            $hidePatientColumn = true;
        }

        $doctor_id = null;
        $export_doctor_id = null;

        if ($request->has('doctor_id') && $request->doctor_id != '') {

            $doctor_id = $request->doctor_id;
            $export_doctor_id = $doctor_id;
        };

        $clinic_id = null;

        if ($request->has('clinic_id') && $request->clinic_id != '') {

            $clinic_id = $request->clinic_id;
        };

        $export_import = true;
        $export_columns = [
            [
                'value' => 'id',
                'text' => __('appointment.lbl_id'),
            ],
            [
                'value' => 'Patient Name',
                'text' => __('appointment.lbl_patient_name'),
            ],
            [
                'value' => 'start_date_time',
                'text' => __('appointment.lbl_date_time'),
            ],
            [
                'value' => 'services',
                'text' => __('appointment.lbl_services'),
            ],
            [
                'value' => 'service_amount',
                'text' => __('appointment.price'),
            ],
            [
                'value' => 'doctor',
                'text' => __('appointment.lbl_doctor'),
            ],
            [
                'value' => 'status',
                'text' => __('appointment.lbl_status'),
            ],
            [
                'value' => 'payment_status',
                'text' => __('appointment.lbl_payment_status'),
            ],

        ];
        $import_columns = [

            [
                'value' => 'start_date_time',
                'text' => __('appointment.lbl_date_time'),
            ],
            [
                'value' => 'services',
                'text' => __('appointment.lbl_services'),

            ],
            [
                'value' => 'Patient Name',
                'text' => __('appointment.lbl_patient_name'),
            ],
            [
                'value' => 'doctor',
                'text' => __('appointment.lbl_doctor'),
            ],
            [
                'value' => 'clinic Name',
                'text' => __('appointment.lbl_clinic_name'),
            ],
            [
                'value' => 'status',
                'text' => __('appointment.lbl_status'),
            ],

        ];


        $patients = User::role('user')
            ->setRolePatients(auth()->user())
            ->where('status', 1)
            ->get();

        $import_url = route('backend.appointments.import');
        $export_url = route('backend.appointments.export');
        return view('appointment::backend.clinic_appointment.index_datatable', compact('service', 'module_action', 'filter', 'columns', 'customefield', 'export_import', 'export_columns', 'import_columns', 'export_url', 'import_url', 'export_doctor_id', 'doctor', 'user_id', 'doctor_id', 'clinic_id', 'patients', 'hidePatientColumn'));
    }
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = __('messages.bulk_update');

        switch ($actionType) {


            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }
                Appointment::whereIn('id', $ids)->delete();
                $message = __('appointment.appointment_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
                break;
        }

        return response()->json(['status' => true, 'message' => $message]);
    }
    public function index_data(Request $request)
    {
        $module_name = $this->module_name;
        $userId = auth()->id();
        $query = Appointment::SetRole(auth()->user())->with('payment', 'commissionsdata', 'patientEncounter', 'cliniccenter');

        $customform = CustomForm::where('module_type', 'appointment_module')
            ->where('status', 1)
            ->get()
            ->filter(function ($item) {
                $showInArray = json_decode($item->show_in, true); // Decode JSON string to array
                return in_array('appointment', $showInArray);
            });

        if ($request->user_id !== null) {
            $user_id = $request->user_id;
            $query->where('user_id', $user_id);
        }

        if ($request->has('doctor_id') && $request->doctor_id != '') {

            $query->where('doctor_id', $request->doctor_id)
                ->where('status', 'checkout')
                ->WhereHas('payment', function ($paymentQuery) use ($request) {
                    $paymentQuery->where('payment_status', 1);
                })
                ->whereHas('commissionsdata', function ($commissionQuery) {
                    $commissionQuery->where('commission_status', 'unpaid')->where('user_type', 'doctor');
                });
        }

        if ($request->has('clinic_id')) {
            $clinic_ids = explode(',', $request->clinic_id);

            $query->whereIn('clinic_id', $clinic_ids)
                ->where('status', 'checkout')
                ->WhereHas('payment', function ($paymentQuery) use ($request) {
                    $paymentQuery->where('payment_status', 1);
                })
                ->whereHas('commissionsdata', function ($commissionQuery) {
                    $commissionQuery->where('commission_status', 'unpaid')->where('user_type', 'vendor');
                });
        }

        $status = Constant::where('type', 'BOOKING_STATUS')->where('name', '!=', 'checkout')->get();
        $payment_status = Constant::where('type', 'PAYMENT_STATUS')->get();


        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
            if (isset($filter['doctor_id'])) {
                $query->where('doctor_id', $filter['doctor_id']);
            }
            if (isset($filter['patient_name'])) {
                $fullName = $filter['patient_name'];
                $query->whereHas('user', function ($query) use ($fullName) {
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$fullName%"]);
                });
            }
            if (isset($filter['service_id'])) {
                $query->where('service_id', $filter['service_id']);
            }
            if (isset($filter['patient_id'])) {
                if (!empty($filter['patient_id'])) {
                    // Show specific patient's appointments
                    if (!empty($filter['other_patient_id'])) {
                        // If other patient is selected, show only their appointments
                        if ($filter['other_patient_id'] === 'you' || $filter['other_patient_id'] == 'all') {
                            // Show only current user's appointments
                            $query->where('user_id', (int)$filter['patient_id']);
                        } else {

                            $query->where('otherpatient_id', (int)$filter['other_patient_id']);
                        }
                    } else {
                        // Show both main patient and their other patients' appointments
                        $query->where(function ($q) use ($filter) {
                            $q->where('user_id', (int)$filter['patient_id'])
                                ->orWhereHas('otherPatient', function ($subQ) use ($filter) {
                                    $subQ->where('user_id', (int)$filter['patient_id']);
                                });
                        });
                    }
                }
            }
            if (!empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
        }
        $doctor = User::where('user_type', 'doctor')->get();
        return Datatables::of($query)
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $data->id . '"  name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
            })
            ->addColumn('action', function ($data) use ($customform) {
                return view('appointment::backend.clinic_appointment.datatable.action_column', compact('data', 'customform'))->render();
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
            ->editColumn('id', function ($data) {
                return "<a href='" . route('backend.appointments.clinicAppointmentDetail', ['id' => $data->id]) . "' class='text-primary'>#" . $data->id . "</a>";
            })
            ->editColumn('user_id', function ($data) {
                return view('appointment::backend.clinic_appointment.user_id', compact('data'));
            })
            ->editColumn('start_date_time', function ($data) {
                $timezone = Setting::where('name', 'default_time_zone')->value('val') ?? 'UTC';

                $dateSetting = Setting::where('name', 'date_formate')->first();
                $dateformate = $dateSetting ? $dateSetting->val : 'Y-m-d';

                $timeSetting = Setting::where('name', 'time_formate')->first();
                $timeformate = $timeSetting ? $timeSetting->val : 'h:i A';

                $combinedFormat = $dateformate . ' ' . $timeformate;

                $date = Carbon::parse($data->start_date_time)
                    ->timezone($timezone)
                    ->format($combinedFormat);

                return $date;
            })
            ->editColumn('services', function ($data) {
                if ($data->clinicservice) {
                    return optional($data->clinicservice)->name;
                } else {
                    return '-';
                }
            })
            ->filterColumn('services', function ($data, $keyword) {
                $data->whereHas('clinicservice', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderColumn('services', function ($query, $order) {
                $query->join('clinics_services', 'appointments.service_id', '=', 'clinics_services.id')
                    ->orderBy('clinics_services.name', $order);
            }, 1)

            ->editColumn('service_amount', function ($data) {
                if ($data->patientEncounter != null) {
                    if (!empty(optional($data->patientEncounter->billingrecord)->final_total_amount)) {
                        $total_amount = optional($data->patientEncounter->billingrecord)->final_total_amount;
                    } else {
                        $total_amount = $data->total_amount;
                    }
                } else {
                    $total_amount = $data->total_amount;
                }
                return '<span>' . \Currency::format($total_amount) . '</span>';
            })
            ->editColumn('doctor_id', function ($data) {
                // $doctor_id = $data->doctor_id;
                // $doctor = User::find($doctor_id);
                return view('appointment::backend.clinic_appointment.doctor_id', compact('data'));
                // if ($doctor != null) {

                //     return $doctor->first_name . ' ' . $doctor->last_name;
                // } else {

                //     return '-';
                // }
            })
            ->orderColumn('doctor_id', function ($query, $order) {
                $query->join('users', 'appointments.doctor_id', '=', 'users.id')
                    ->orderByRaw("CONCAT(users.first_name, ' ', users.last_name) $order");
            }, 1)
            ->editColumn('payment_status', function ($data) use ($payment_status) {

                if ($data->status == 'cancelled' && $data->advance_paid_amount != 0) {
                    return '<span class="text-capitalize badge bg-success-subtle p-3">' . __('appointment.advance_refund') . '</span>';
                } elseif ($data->status === 'cancelled' && optional($data->appointmenttransaction)->payment_status == 1) {
                    return '<span class="text-capitalize badge bg-success-subtle p-3">' . __('appointment.refunded') . '</span>';
                } elseif ($data->status === 'cancelled') {
                    return '--';
                } else {
                    return view('appointment::backend.appointment.datatable.select_payment_status', compact('data', 'payment_status'));
                }
            })

            ->editColumn('status', function ($data) use ($status) {
                return view('appointment::backend.appointment.datatable.select_status', compact('data', 'status'));
            })

            ->editColumn('updated_at', function ($data) {
                // $setting = Setting::where('name', 'date_formate')->first();
                // $dateformate = $setting ? $setting->val : 'Y-m-d';
                // $setting = Setting::where('name', 'time_formate')->first();
                // $timeformate = $setting ? $setting->val : 'h:i A';
                // $date = optional($dateformate) && optional($timeformate)
                // ? date($dateformate, strtotime($data->updated_at)) . ' ' . date($timeformate, strtotime($data->updated_at))
                // : $data->updated_at;
                $diff = timeAgoInt($data->updated_at);

                if ($diff < 25) {
                    return timeAgo($data->updated_at);
                } else {
                    return customDate($data->updated_at);
                }

                // return $date;
            })


            ->rawColumns(['check', 'action', 'status', 'services', 'service_amount', 'start_date_time', 'id', 'payment_status'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
        // Custom Fields For export
        $customFieldColumns = CustomField::customFieldData($datatable, Appointment::CUSTOM_FIELD_MODEL, null);
        return $datatable->rawColumns(array_merge(['action', 'status', 'services', 'start_date_time', 'check', 'id'], $customFieldColumns))
            ->toJson();
    }
    public function index_patientdata(Request $request)
    {
        $module_name = $this->module_name;
        $query = Appointment::query()->with('cliniccenter');
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('demo_admin')) {
            $query;
        } else {
            $query->where('employee_id', auth()->id());
        }
        $status = config('appointment.STATUS');
        $payment_status = config('appointment.PAYMENT_STATUS');

        $query->orderBy('created_at', 'desc');
        $doctor = User::where('user_type', 'doctor')->get();
        $filter = $request->filter;
        $doctor = User::where('user_type', 'doctor')->get();

        if (isset($filter)) {
            if (isset($filter['category_id'])) {
                $query->where('employee_id', $filter['category_id']);
            }
        }


        return Datatables::of($query)
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $data->id . '"  name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
            })
            ->addColumn('action', function ($data) {
                return view('clinic::backend.patientList.action_column', compact('data'));
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
            ->editColumn('id', function ($data) {
                return "<a href='#' class='text-primary'>#" . $data->id . "</a>";
            })
            ->editColumn('user_id', function ($data) {
                return view('appointment::backend.clinic_appointment.user_id', compact('data'));
            })
            ->editColumn('start_date', function ($data) {
                $startDate = Carbon::parse($data->start_date_time);
                return $startDate->toDateString();
            })
            ->orderColumn('start_date', function ($data, $order) {
                $data->orderBy('start_date_time', $order);
            }, 1)
            ->editColumn('start_time', function ($data) {
                $startTime = Carbon::parse($data->start_date_time);
                return $startTime->toTimeString();
            })
            ->orderColumn('start_time', function ($data, $order) {
                $data->orderBy('start_date_time', $order);
            }, 1)
            ->addColumn('clinic_name', function ($data) {
                if ($data->cliniccenter) {
                    $clinic = optional($data->cliniccenter)->name;
                    return $clinic;
                } else {
                    return '-';
                }
            })
            ->editColumn('services', function ($data) {
                if ($data->clinicservice) {
                    return $data->clinicservice->name;
                } else {
                    return '-';
                }
            })
            ->filterColumn('services', function ($data, $keyword) {
                $data->whereHas('clinicservice', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderColumn('services', function ($data, $order) {
                $data->whereHas('clinicservice', function ($q) use ($order) {
                    $q->orderBy('name', $order);
                });
            }, 1)

            ->editColumn('service_amount', function ($data) {
                return '<span>' . \Currency::format($data->service_amount) . '</span>';
            })
            ->editColumn('employee_id', function ($data) {
                $doctor_id = $data->doctor_id;
                $doctor = User::find($doctor_id);

                if ($doctor != null) {

                    return $doctor->first_name . ' ' . $doctor->last_name;
                } else {

                    return '-';
                }
            })
            ->editColumn('payment_status', function ($data) use ($payment_status) {
                if ($data->status === 'cancelled') {
                    return '--';
                } else {
                    return view('appointment::backend.appointment.datatable.select_payment_status', compact('data', 'payment_status'));
                }
            })

            ->editColumn('status', function ($data) use ($status) {
                return view('appointment::backend.appointment.datatable.select_status', compact('data', 'status'));
            })

            ->editColumn('updated_at', function ($data) {
                $diff = timeAgoInt($data->updated_at);

                if ($diff < 25) {
                    return timeAgo($data->updated_at);
                } else {
                    return customDate($data->updated_at);
                }
            })


            ->rawColumns(['check', 'action', 'status', 'services', 'clinic_name', 'service_amount', 'start_date', 'start_time', 'id'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('appointment::create');
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
        return view('appointment::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('appointment::edit');
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
        $data = PatientEncounter::findOrFail($id);

        $data->delete();

        $message = __('appointment.encounter_delete_successfully');

        return response()->json(['message' => $message, 'status' => true], 200);
    }
    public function patient_record(Request $request)
    {
        $encounter = PatientEncounter::findOrFail($request->id);
        $module_action = 'List';
        $module_title = __('appointment.soap');
        $encounter_id = $encounter->id;
        $appointment_id = $encounter->appointment_id;
        $patient_id = $encounter->user_id;
        return view('appointment::backend.clinic_appointment.appointment_patient_record', compact('module_action', 'module_title', 'appointment_id', 'patient_id', 'encounter_id'));
    }
    public function bodychart(Request $request, $id)
    {
        $patient_encounter = PatientEncounter::findOrFail($request->id);
        $module_action = 'List';
        $module_title = __('messages.body_chart');
        $encounter_id = $request->id;
        $appointment_id = $patient_encounter->appointment_id;
        $patient_id = $patient_encounter->user_id;
        return view('appointment::backend.clinic_appointment.appointment_bodychart', compact('module_action', 'module_title', 'appointment_id', 'patient_id', 'encounter_id', 'patient_encounter'));
    }
    public function appointment_patient(Request $request, $id)
    {
        $record = AppointmentPatientRecord::where('encounter_id', $id)->first();
        if ($record) {
            $record->update($request->all());
            $message = __('appointment.save_successfully');
        } else {
            $record = AppointmentPatientRecord::create($request->all());
            $message = __('appointment.save_successfully');
        }

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function appointment_patient_data(Request $request, $id)
    {
        $data = AppointmentPatientRecord::where('encounter_id', $id)->first();
        if (!$data) {
            $message = __('appointment.data_not_found');
            return response()->json(['status' => false, 'message' => $message]);
        }
        return response()->json(['data' => $data, 'status' => true]);
    }

    public function appointment_bodychart(Request $request, $id)
    {
        $baseURL = config('app.url');

        $data = $request->except('file_url');

        $bodychart = AppointmentPatientBodychart::create($data);

        if ($request->hasFile('file_url')) {
            storeMediaFile($bodychart, $request->file('file_url'));
        }

        $returnUrl = "{$baseURL}/app/bodychart/{$id}";

        $message = __('appointment.save_successfully');

        \Artisan::call('config:clear');

        return response()->json([
            'status' => true,
            'return_url' => $returnUrl,
            'message' => $message
        ]);
    }


    public function bodychart_templatedata(Request $request, $id)
    {
        $fields = ['theme_mode', 'Menubar_position', 'menu_items', 'image_handling', 'body_image'];
        foreach ($fields as $field) {
            $bodysetting[$field] = setting($field);
            if (in_array($field, ['body_image'])) {
                $bodysetting[$field] = asset(setting($field));
            }
        }
        $bodychart = BodyChartSetting::all();
        foreach ($bodychart as $field) {
            if ($field->default == 1) {
                $bodysetting['image'] = $field->full_url;
                $bodysetting['name'] = $field->name;
            }
        }
        return response()->json(['status' => true, 'bodysetting' => $bodysetting]);
    }
    public function appointment_bodychart_data(Request $request, $id)
    {
        $data = AppointmentPatientBodychart::findOrFail($id);
        $fields = ['theme_mode', 'Menubar_position', 'menu_items', 'image_handling', 'body_image'];
        foreach ($fields as $field) {
            $bodysetting[$field] = setting($field);
            if (in_array($field, ['body_image'])) {
                $bodysetting[$field] = asset(setting($field));
            }
        }
        return response()->json(['data' => $data, 'bodysetting' => $bodysetting, 'status' => true]);
    }
    public function bodychart_image_list()
    {
        //$fields=['theme_mode','Menubar_position','menu_items','image_handling','body_image'];
        $bodychart = BodyChartSetting::all();

        $data = [];

        foreach ($bodychart as $field) {

            $data[] =
                [
                    'id' => $field->id,
                    'image' => $field->full_url,
                    'name' => $field->name,
                    'default' => $field->default,
                    'uniqueId' => $field->uniqueId,
                    'image_name' => $field->image_name,
                ];
        }
        return response()->json($data);
    }

    public function patient_list(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $module_title = 'appointment.patient_list';
        $doctor = Doctor::where('status', 1)->with('user')->get();
        $module_action = 'List';
        $export_import = true;
        $export_columns = [
            [
                'value' => 'id',
                'text' => 'Id',
            ],
            [
                'value' => 'Patient Name',
                'text' => 'Patient Name',
            ],
            [
                'value' => 'start_date',
                'text' => 'Start Date',
            ],
            [
                'value' => 'start_time',
                'text' => 'start_time',
            ],
            [
                'value' => 'services',
                'text' => 'services',
            ],
            [
                'value' => 'service_amount',
                'text' => 'service_amount',
            ],
            [
                'value' => 'Clinic_Name',
                'text' => 'Clinic_Name',
            ],

            [
                'value' => 'doctor',
                'text' => 'doctor',
            ],
            [
                'value' => 'status',
                'text' => 'Status',
            ],
            [
                'value' => 'payment_status',
                'text' => 'Payment Status',
            ],

        ];
        $export_url = route('backend.appointments.patient_list.export');
        return view('clinic::backend.patientList.index', compact('module_action', 'module_title', 'filter', 'export_import', 'doctor', 'export_columns', 'export_url'));
    }
    public function patientListExport(Request $request)
    {
        $this->exportClass = '\App\Exports\PatientListExport';

        return $this->export($request);
    }
    public function patientDeatails(Request $request, $id)
    {
        $data = Appointment::with('cliniccenter', 'employee', 'user', 'clinicservice')->findOrFail($id);

        return response()->json(['data' => $data, 'status' => true]);
    }
    public function view()
    {
        $module_title = 'Patient Detail';
        $module_action = 'List';

        return view('appointment::backend.clinic_appointment.view', compact('module_action', 'module_title'));
    }
    public function appointmentDetail($id)
    {
        $module_title = "appointment.singular_title";
        $appointment = Appointment::with('user', 'doctor', 'otherPatient', 'clinicservice', 'appointmenttransaction', 'cliniccenter', 'media', 'patientEncounter.billingrecord')->findOrFail($id);
        $setting = Setting::where('name', 'date_formate')->first();
        $dateformate = $setting ? $setting->val : 'Y-m-d';
        $setting = Setting::where('name', 'time_formate')->first();
        $timeformate = $setting ? $setting->val : 'h:i A';
        $setting = Setting::where('name', 'default_time_zone')->first();
        $timeZone = $setting ? $setting->val : 'h:i A';

        return view('appointment::backend.clinic_appointment.appointment_detail', compact('appointment', 'module_title', 'dateformate', 'timeformate', 'timeZone'));
    }

    public function bodychart_datatable(Request $request, $id)
    {

        $query = AppointmentPatientBodychart::query()->where('encounter_id', $id)->with('patient_encounter');

        $query->orderBy('created_at', 'desc');
        return Datatables::of($query)
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $data->id . '"  name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
            })
            ->editColumn('name', function ($data) {
                $avatarUrl = $data->file_url ?? default_user_avatar();
                return "<a href='{$avatarUrl}' target='_blank'>
                            <img src='{$avatarUrl}' alt='avatar' class='avatar avatar-40 rounded-pill'>
                        </a> " . $data->name;
            })
            ->addColumn('action', function ($data) {
                return view('appointment::backend.clinic_appointment.datatable.bodychart_action_column', compact('data'));
            })
            ->editColumn('patient_id', function ($data) {
                return optional(optional($data->patient_encounter)->user)->first_name . ' ' . optional(optional($data->patient_encounter)->user)->last_name;
            })
            ->editColumn('doctor_name', function ($data) {
                return optional(optional($data->patient_encounter)->doctor)->first_name . ' ' . optional(optional($data->patient_encounter)->doctor)->last_name;
            })

            ->rawColumns(['check', 'action', 'name'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }
    public function bodychart_form(Request $request, $id)
    {
        $patient_encounter = PatientEncounter::findOrFail($request->id);
        $module_action = 'List';
        $module_title = __('messages.body_chart');
        $encounter_id = $request->id;
        $appointment_id = $patient_encounter->appointment_id;
        $patient_id = $patient_encounter->user_id;
        return view('appointment::backend.clinic_appointment.apointment_bodychartform', compact('module_action', 'module_title', 'appointment_id', 'patient_id', 'encounter_id'));
    }
    public function bodychartdestroy(Request $request, $id)
    {
        $data = AppointmentPatientBodychart::findOrFail($id);
        $data->delete();
        $message = __('appointment.bodychart_delete');
        return response()->json(['message' => $message, 'status' => true], 200);
    }
    public function bodychart_bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = __('messages.bulk_update');

        switch ($actionType) {


            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }

                AppointmentPatientBodychart::whereIn('id', $ids)->delete();
                $message = __('messages.bodychart_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
                break;
        }

        return response()->json(['status' => true, 'message' => $message]);
    }
    public function editbodychartview(Request $request, $id)
    {
        $module_action = 'List';
        $data = AppointmentPatientBodychart::with('patient_encounter')->find($id);
        $bodychart_id = $id;
        $encounter_id = $data->encounter_id;
        $appointment_id = $data->appointment_id;
        $patient_id = $data->patient_encounter->user_id;
        return view('appointment::backend.clinic_appointment.apointment_bodychartform', compact('module_action', 'appointment_id', 'patient_id', 'bodychart_id', 'encounter_id'));
    }

    public function getBodychartDetail($id)
    {

        $html = '';

        $data = AppointmentPatientBodychart::with('patient_encounter')->find($id);
        $bodychart_id = $id;
        $encounter_id = $data->encounter_id;
        $appointment_id = $data->appointment_id;
        $patient_id = $data->patient_encounter->user_id;

        if (!empty($data)) {

            $html = view('appointment::backend.clinic_appointment.apointment_bodychartform', ['encounter_id' => $encounter_id, 'appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'bodychart_id' => $bodychart_id])->render();
        }

        return response()->json(['html' => $html]);
    }


    public function appointment_upadtebodychart(Request $request, $id)
    {
        $baseURL = env('APP_URL');

        $image_handling = setting('image_handling');

        $record = AppointmentPatientBodychart::findOrFail($id);
        $data = $request->except('file_url');
        $encounter_id = $record->encounter_id;
        $apointment_id = $record->appointment_id;
        $return_url = $baseURL . '/app/bodychart/' . $encounter_id;
        if ($record && $image_handling === 'Saved_image') {
            $record->update($data);
            if ($request->hasFile('file_url')) {
                storeMediaFile($record, $request->file('file_url'));
            }
            $message = __('appointment.save_successfully');
        } else {
            $record = AppointmentPatientBodychart::create($data);
            if ($request->hasFile('file_url')) {
                storeMediaFile($record, $request->file('file_url'));
            }
            $message = __('appointment.save_successfully');
        }
        \Artisan::call('config:clear');
        return response()->json(['status' => true, 'encounter_id' => $encounter_id, 'return_url' => $return_url, 'message' => $message]);
    }

    public function invoice_detail(Request $request)
    {
        $id = $request->id;
        $module_action = __('appointment.invoice_detail');
        
        // Fetch appointment with all relationships
        $appointment = Appointment::with('user', 'doctor', 'clinicservice', 'cliniccenter', 'appointmenttransaction', 'patientEncounter.billingrecord')
            ->where('id', $id)
            ->where('status', 'checkout')
            ->first();
        
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }
        
        // Check if this is an estimate or a paid appointment
        $isEstimate = optional($appointment->patientEncounter)->billingrecord && 
                     optional($appointment->patientEncounter->billingrecord)->is_estimate;
        
        if ($isEstimate) {
            // For estimates, we don't need to check payment status
            $appointments = collect([$appointment]);
        } else {
            // For regular invoices, check if it's paid
            if (!optional($appointment->appointmenttransaction)->payment_status == 1) {
                abort(404, 'Invoice not found or not paid');
            }
            $appointments = collect([$appointment]);
        }
        
        $data = $appointments->toArray();
        $dateformate = \App\Models\Setting::where('name', 'date_formate')->first()->val ?? 'Y-m-d';
        $timeformate = \App\Models\Setting::where('name', 'time_formate')->first()->val ?? 'h:i A';
        
        return view('appointment::backend.clinic_appointment.invoice_detail', compact('module_action', 'data', 'dateformate', 'timeformate'));
    }

    public function downloadPDF(Request $request)
    {
        $id = $request->id;
        $appointments = Appointment::with('user', 'doctor', 'clinicservice', 'cliniccenter.media', 'appointmenttransaction', 'patientEncounter.billingrecord')
            ->where('id', $id)
            ->where('status', 'checkout')
            ->whereHas('appointmenttransaction', function ($query) {
                $query->where('payment_status', 1);
            })->get();
        $appointments->each(function ($appointment) {
            $appointment->date_of_birth = optional($appointment->user)->date_of_birth ?? '-';
            
            // Ensure brand_mark_url is computed for the cliniccenter
            if ($appointment->cliniccenter) {
                // Try to get brand mark from media library
                $brandMarkUrl = $appointment->cliniccenter->getFirstMediaUrl('brand_mark');
                
                // If no media found, check if there's a direct brand_mark_url field
                if (empty($brandMarkUrl) && isset($appointment->cliniccenter->brand_mark_url)) {
                    $brandMarkUrl = $appointment->cliniccenter->brand_mark_url;
                }
                
                $appointment->cliniccenter->brand_mark_url = !empty($brandMarkUrl) ? $brandMarkUrl : null;
            }
        });
        $data = $appointments->toArray();
        $dateformate = \App\Models\Setting::where('name', 'date_formate')->first()->val ?? 'Y-m-d';
        $timeformate = \App\Models\Setting::where('name', 'time_formate')->first()->val ?? 'h:i A';

        if ($request->is('api/*')) {
            $pdf = PDF::loadHTML(view("appointment::backend.clinic_appointment.invoice", [
                'data' => $data, 
                'dateformate' => $dateformate, 
                'timeformate' => $timeformate,
            ])->render())
                ->setOptions(['defaultFont' => 'Noto Sans']);

            $baseDirectory = storage_path('app/public');
            $highestDirectory = collect(File::directories($baseDirectory))->map(function ($directory) {
                return basename($directory);
            })->max() ?? 0;
            $nextDirectory = intval($highestDirectory) + 1;
            while (File::exists($baseDirectory . '/' . $nextDirectory)) {
                $nextDirectory++;
            }
            $newDirectory = $baseDirectory . '/' . $nextDirectory;
            File::makeDirectory($newDirectory, 0777, true);

            $filename = 'invoice_' . $id . '.pdf';
            $filePath = $newDirectory . '/' . $filename;

            $pdf->save($filePath);

            $url = url('storage/' . $nextDirectory . '/' . $filename);
            if (!isset($appointments) || $appointments->isEmpty() || !$appointments->first()->user_id) {
                return response()->json(['error' => 'User ID not found.'], 404);
            }
            $user_id = $appointments->first()->user_id;
            $user = User::findOrFail($user_id);
            $email = $user->email;
            $subject = 'Your Invoice';
            $details = __('appointment.invoice_find') . $url;

            Mail::to($email)->send(new InvoiceEmail($data, $subject, $details, $filePath, $filename));
            if (!empty($url)) {
                return response()->json(['status' => true, 'link' => $url], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Url Not Found'], 404);
            }
        } else {
            $pdf = PDF::loadView('appointment::backend.clinic_appointment.invoice', compact('data', 'dateformate', 'timeformate'))
                ->setOptions([
                    'defaultFont' => 'Noto Sans',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);
            return response()->streamDownload(
                function () use ($pdf) {
                    echo $pdf->output();
                },
                "invoice_{$id}.pdf",
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="invoice_' . $id . '.pdf"',
                ]
            );
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods,html|max:2048', // Include html as an accepted file type
            'file_format' => 'required|in:csv,xlsx,xls,ods,html', // Include html in the allowed file formats
        ]);

        $file = $request->file('file');
        $fileFormat = $request->input('file_format');
        $appointmentImport = new AppointmentImport();

        if ($fileFormat === 'html') {
            Excel::import($appointmentImport, $file, null, \Maatwebsite\Excel\Excel::HTML);
        } else {
            Excel::import($appointmentImport, $file);
        }

        $errors = $appointmentImport->getErrors();

        if (!empty($errors)) {
            return response()->json([
                'message' => 'File imported with errors!',
                'errors' => $errors,
            ], 422);
        }
        return response()->json(['message' => 'File imported successfully!']);
    }

    public function downloadSample(Request $request, $type)
    {
        $fileName = "sample.$type";

        $export = new class implements FromArray {
            public function array(): array
            {
                return [
                    ['start_date_time', 'services', 'Patient Name', 'doctor', 'clinic Name', 'status'],
                    ['2024-06-15 10:00:00', 'Cardiac Consultation', 'John Doe', 'Felix Harris', 'HeartCare & OrthoCare Center', 'Confirmed'],
                    ['2024-06-16 11:00:00', 'Teeth Cleaning', 'Jane Doe', 'Jorge Perez', 'HeartCare & OrthoCare Center', 'Pending'],

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
}
