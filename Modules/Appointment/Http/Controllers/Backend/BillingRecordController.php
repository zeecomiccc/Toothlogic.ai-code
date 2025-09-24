<?php

namespace Modules\Appointment\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CustomField\Models\CustomField;
use Modules\CustomField\Models\CustomFieldGroup;
use Yajra\DataTables\DataTables;
use Modules\Appointment\Models\BillingRecord;
use Modules\Appointment\Models\PatientEncounter;
use Modules\Appointment\Models\AppointmentTransaction;
use Modules\Appointment\Models\Appointment;
use Carbon\Carbon;
use Modules\Clinic\Models\ClinicsService;
use Modules\Commission\Models\CommissionEarning;
use Modules\Appointment\Trait\AppointmentTrait;
use App\Models\Setting;
use Modules\Appointment\Models\BillingItem;
use Modules\Appointment\Trait\BillingRecordTrait;
use Modules\Appointment\Transformers\BillingItemResource;
use Modules\Clinic\Http\Controllers\ClinicsServiceController;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Appointment\Models\Installment;
use Modules\Tip\Models\TipEarning;
use Modules\Clinic\Models\Clinics;
use Currency;

class BillingRecordController extends Controller
{
    use AppointmentTrait;
    use BillingRecordTrait;
    protected string $exportClass = '\App\Exports\BillingExport';
    public function __construct()
    {
        // Page Title
        $this->module_title = 'appointment.billing_record';
        // module name
        $this->module_name = 'billing-record';

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
            'payment_status' => $request->payment_status,
        ];

        $module_action = 'List';
        $columns = CustomFieldGroup::columnJsonValues(new BillingRecord());
        $customefield = CustomField::exportCustomFields(new BillingRecord());
        $service = ClinicsService::SetRole(auth()->user())->with('sub_category', 'doctor_service', 'ClinicServiceMapping', 'systemservice')->where('status', 1)->get();

        $export_import = true;
        $export_columns = [
            [
                'value' => 'encounter_id',
                'text' => __('appointment.lbl_encounter_id'),
            ],
            [
                'value' => 'user_id',
                'text' => __('appointment.lbl_patient_name'),
            ],
            [
                'value' => 'clinic_id',
                'text' => __('appointment.lbl_clinic'),
            ],
            [
                'value' => 'doctor_id',
                'text' => __('appointment.lbl_doctor'),
            ],
            [
                'value' => 'service_id',
                'text' => __('appointment.lbl_service'),
            ],
            [
                'value' => 'total_amount',
                'text' => __('appointment.lbl_total_amount'),
            ],
            [
                'value' => 'date',
                'text' => __('appointment.lbl_date'),
            ],
            [
                'value' => 'payment_status',
                'text' => __('appointment.lbl_payment_status'),
            ],

        ];
        $export_url = route('backend.billing-record.export');

        return view('appointment::backend.billing_record.index_datatable', compact('service', 'module_action', 'filter', 'columns', 'customefield', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = __('messages.bulk_update');

        switch ($actionType) {
            case 'change-status':
                $clinic = BillingRecord::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = __('clinic.clinic_status');
                break;

            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }
                BillingRecord::whereIn('id', $ids)->delete();
                $message = __('clinic.clinic_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
                break;
        }

        return response()->json(['status' => true, 'message' => $message]);
    }
    public function index_data(Datatables $datatable, Request $request)
    {
        $query = BillingRecord::SetRole(auth()->user());

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('payment_status', $filter['column_status']);
            }
        }


        if (isset($filter)) {
            if (isset($filter['doctor_name'])) {
                $query->where('doctor_id', $filter['doctor_name']);
            }
            if (isset($filter['patient_name'])) {
                $query->where("user_id", $filter['patient_name']);
            }
            if (isset($filter['clinic_name'])) {
                $query->where("clinic_id", $filter['clinic_name']);
            }
            if (isset($filter['service_name'])) {
                $query->where('service_id', $filter['service_name']);
            }
        }

        // Debug: Log the SQL query and test data
        \Log::info('DataTable query SQL:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        // Test: Get a sample record to see what fields are available
        $testRecord = $query->first();
        if ($testRecord) {
            \Log::info('DataTable test record:', [
                'id' => $testRecord->id,
                'is_estimate' => $testRecord->is_estimate,
                'is_estimate_type' => gettype($testRecord->is_estimate),
                'payment_status' => $testRecord->payment_status,
                'encounter_id' => $testRecord->encounter_id
            ]);
        }

        $datatable = $datatable->eloquent($query->select('billing_record.*'))
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $data->id . '"  name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
            })
            ->addColumn('action', function ($data) {
                return view('appointment::backend.billing_record.action_column', compact('data'));
            })
            ->addColumn('payment_mode', function ($data) {
                $modes = $data->installments->pluck('payment_mode')->unique()->filter()->toArray();
                return $modes ? implode(', ', $modes) : '--';
            })
            ->addColumn('remaining_balance', function ($data) {
                $total = $data->final_total_amount ?? $data->total_amount;
                $paid = $data->installments->sum('amount');
                $remaining = $total - $paid;
                return $remaining > 0 ? \Currency::format($remaining) : \Currency::format(0);
            })

            ->editColumn('clinic_id', function ($data) {
                return view('appointment::backend.patient_encounter.clinic_id', compact('data'));
            })

            ->editColumn('user_id', function ($data) {
                return view('appointment::backend.clinic_appointment.user_id', compact('data'));
            })

            ->editColumn('date', function ($data) {
                return $data->date ? date('Y-m-d', strtotime($data->date)) : '--';
            })

            ->editColumn('doctor_id', function ($data) {
                return view('appointment::backend.clinic_appointment.doctor_id', compact('data'));
            })

            ->editColumn('payment_status', function ($data) {
                return view('appointment::backend.billing_record.verify_action', compact('data'));
            })


            ->editColumn('service_id', function ($data) {
                if ($data->clinicservice) {
                    return optional($data->clinicservice)->name;
                } else {
                    return '-';
                }
            })

            ->addColumn('quantity', function ($data) {
                return $data->billingItem->sum('quantity') ?? 0;
            })

            ->editColumn('total_amount', function ($data) {

                if ($data->final_total_amount) {

                    return '<span>' . \Currency::format($data->final_total_amount) . '</span>';
                } else {

                    return '<span>' . \Currency::format($data->total_amount) . '</span>';
                }
            })


            ->filterColumn('doctor_id', function ($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('user', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', '%' . $keyword . '%')
                            ->orWhere('last_name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    });
                }
            })


            ->filterColumn('user_id', function ($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('user', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', '%' . $keyword . '%')
                            ->orWhere('last_name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    });
                }
            })

            ->filterColumn('clinic_id', function ($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('clinic', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    });
                }
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
        $customFieldColumns = CustomField::customFieldData($datatable, BillingRecord::CUSTOM_FIELD_MODEL, null);

        return $datatable->rawColumns(array_merge(['action', 'payment_status', 'check', 'total_amount', 'quantity', 'payment_mode', 'remaining_balance'], $customFieldColumns))
            ->toJson();
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
        $data = BillingRecord::where('id', $id)->first();

        return response()->json(['data' => $data, 'status' => true]);
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

    public function saveBillingDetails(Request $request)
    {

        $data = $request->all();

        $encounter_details = PatientEncounter::where('id', $data['encounter_id'])->with('appointment', 'billingrecord')->first();

        $service_price_data = $data['service_details']['service_price_data'] ?? null;

        $tax_data = isset($data['service_details']['tax_data']) ? json_encode($data['service_details']['tax_data'], true) : null;

        $date = isset($data['date']) ? date('Y-m-d', strtotime($data['date'])) : (isset($encounter_details['encounter_date']) ? date('Y-m-d', strtotime($encounter_details['encounter_date'])) : null);

        $service_id = $data['service_details']['id'] ?? null;


        if ($request->is('api/*')) {
            $service_id = $request->input('service_id');

            if ($request->service_id == null) {
                $billingData = optional($encounter_details->billingrecord)->billingItem ?? collect();
                $service_id = optional($billingData->first())->item_id;
            }

            $data['service_details'] = ClinicsService::where('id', $service_id)->first();

            $newRequest = new Request([
                'service_id' => $service_id,
                'encounter_id' => $request->input('encounter_id')
            ]);

            $data['final_discount'] = $data['final_discount_enabled'] ?? 0;

            $serviceController = new ClinicsServiceController();
            $serviceDetailsResponse = $serviceController->ServiceDetails($newRequest);

            $serviceDetailsData = $serviceDetailsResponse->getData();

            $serviceDetails = $serviceDetailsData->data ?? null;

            // Check if serviceDetails is valid before accessing its properties
            if (!$serviceDetails) {
                return response()->json([
                    'status' => false,
                    'message' => 'Service details not found'
                ]);
            }

            $service_id = $serviceDetails->id ?? null;
            $service_price_data = (array) ($serviceDetails->service_price_data ?? []);
            $taxData = json_encode($serviceDetails->tax_data ?? []);

            $billingData = optional($encounter_details->billingrecord)->billingItem ?? collect();
            $total_amount = $billingData->sum('total_amount');

            if ($data['final_discount'] == 1) {
                $discount = 0;
                if ($request->final_discount_type == 'fixed') {
                    $discount = $request->final_discount_value;
                } else {
                    $discount = $total_amount * $request->final_discount_value / 100;
                }
                $total_amount = $total_amount - $discount;
            }

            $tax_data = $this->calculateTaxAmounts(null, $total_amount);



            $data['final_tax_amount'] = array_sum(array_column($tax_data, 'amount'));
            $data['final_total_amount'] = $total_amount + $data['final_tax_amount'];
        }


        $biling_details = [
            'encounter_id' => $data['encounter_id'],
            'user_id' => $data['user_id'],
            'clinic_id' => $encounter_details['clinic_id'],
            'doctor_id' => $encounter_details['doctor_id'],
            'service_id' => $service_id ?? null,
            'total_amount' => $service_price_data['total_amount'] ?? 0,
            'service_amount' => $service_price_data['service_price'] ?? 0,
            'discount_amount' => $service_price_data['discount_amount'] ?? 0,
            'discount_type' => $service_price_data['discount_type'] ?? null,
            'discount_value' => $service_price_data['discount_value'] ?? null,
            'tax_data' => $tax_data,
            'date' => $date,
            'payment_status' => $data['payment_status'],
            'final_discount' => $data['final_discount'] ?? 0,
            'final_discount_value' => $data['final_discount_value'] ?? null,
            'final_discount_type' => $data['final_discount_type'] ?? null,
            'final_tax_amount' => $data['final_tax_amount'] ?? 0,
            'final_total_amount' => $data['final_total_amount'] ?? 0,
            'is_estimate' => $data['is_estimate'] ?? false,
        ];

        $billing_data = BillingRecord::updateOrCreate(
            ['encounter_id' => $data['encounter_id']],
            $biling_details
        );
        $billing_record = $billing_data->where('id', $billing_data->id)->with('clinicservice', 'patientencounter')->first();
        if ($billing_record && !empty($billing_record['service_id'])) {
            $billing_item = $this->generateBillingItem($billing_record);
        }


        if ($encounter_details['appointment_id'] !== null && $data['payment_status'] == 1) {
            $finalTotalAmount = $data['final_total_amount'] ?? 0;
            $paymentStatus = $data['payment_status'];


            // Update the appointment transaction
            AppointmentTransaction::where('appointment_id', $encounter_details['appointment_id'])
                ->update([
                    'total_amount' => $finalTotalAmount,
                    'payment_status' => $paymentStatus,
                ]);

            if ($encounter_details['doctor_id'] && $earning_data = $this->commissionData($encounter_details)) {
                $appointment = Appointment::findOrFail($encounter_details['appointment_id']);

                // Save doctor commission
                $earning_data['commission_data']['user_type'] = 'doctor';
                $earning_data['commission_data']['commission_status'] = $paymentStatus == 1 ? 'unpaid' : 'pending';
                $commissionEarning = new CommissionEarning($earning_data['commission_data']);
                $appointment->commission()->save($commissionEarning);

                $vendor_id = $data['service_details']['vendor_id'] ?? null;

                $vendor = User::find($vendor_id);

                // Determine admin and vendor commission logic
                if (multiVendor() != 1) {
                    // Admin commission when not multi-vendor
                    $adminEarningData = [
                        'user_type' => $vendor->user_type ?? 'admin',
                        'employee_id' => $vendor->id ?? User::where('user_type', 'admin')->value('id'),
                        'commissions' => null,
                        'commission_status' => $paymentStatus == 1 ? 'unpaid' : 'pending',
                        'commission_amount' => $finalTotalAmount - $earning_data['commission_data']['commission_amount'],
                    ];
                    $adminCommissionEarning = new CommissionEarning($adminEarningData);

                    $appointment->commission()->save($adminCommissionEarning);
                } else {
                    // Logic for multi-vendor scenario
                    if ($vendor && $vendor->user_type == 'vendor') {
                        // Admin earning for vendor
                        $adminEarning = $this->AdminEarningData($encounter_details);
                        $adminEarning['user_type'] = 'admin';
                        $adminEarning['commission_status'] = $paymentStatus == 1 ? 'unpaid' : 'pending';

                        $adminCommissionEarning = new CommissionEarning($adminEarning);

                        $appointment->commission()->save($adminCommissionEarning);

                        // Vendor earning
                        $vendorEarningData = [
                            'user_type' => $vendor->user_type,
                            'employee_id' => $vendor->id,
                            'commissions' => null,
                            'commission_status' => $paymentStatus == 1 ? 'unpaid' : 'pending',
                            'commission_amount' => $finalTotalAmount - $adminEarning['commission_amount'] - $earning_data['commission_data']['commission_amount'],
                        ];
                        $vendorCommissionEarning = new CommissionEarning($vendorEarningData);
                        $appointment->commission()->save($vendorCommissionEarning);
                    } else {
                        // Fallback to admin earning if vendor is not found
                        $adminEarningData = [
                            'user_type' => 'admin',
                            'employee_id' => User::where('user_type', 'admin')->value('id'),
                            'commissions' => null,
                            'commission_status' => $paymentStatus == 1 ? 'unpaid' : 'pending',
                            'commission_amount' => $finalTotalAmount - $earning_data['commission_data']['commission_amount'],
                        ];


                        $adminCommissionEarning = new CommissionEarning($adminEarningData);
                        $appointment->commission()->save($adminCommissionEarning);
                    }
                }
            }
        }


        if ($request->has('encounter_status') && $request->encounter_status == 0 && $data['payment_status'] == 1) {

            PatientEncounter::where('id', $data['encounter_id'])->update(['status' => $request->encounter_status]);

            if ($encounter_details['appointment_id'] != null && $data['payment_status'] == 1) {

                $appointment = Appointment::where('id', $encounter_details['appointment_id'])->first();
                $clinic_data = Clinics::where('id', $appointment->clinic_id)->first();

                $data['service_name'] = $service_data->systemservice->name ?? '--';
                $data['clinic_name'] = $clinic_data->name ?? '--';
                if ($appointment && $appointment->status == 'check_in') {
                    $finalTotalAmount = $data['final_total_amount'] ?? 0;
                    $appointment->update([
                        'total_amount' => $finalTotalAmount,
                        'status' => 'checkout',
                    ]);
                    $startDate = Carbon::parse($appointment['start_date_time']);
                    $notification_data = [
                        'id' => $appointment->id,
                        'description' => $appointment->description,
                        'appointment_duration' => $appointment->duration,
                        'user_id' => $appointment->user_id,
                        'user_name' => optional($appointment->user)->first_name ?? default_user_name(),
                        'doctor_id' => $appointment->doctor_id,
                        'doctor_name' => optional($appointment->doctor)->first_name,
                        'appointment_date' => $startDate->format('d/m/Y'),
                        'appointment_time' => $startDate->format('h:i A'),
                        'appointment_services_names' => ClinicsService::with('systemservice')->find($appointment->service_id)->systemservice->name ?? '--',
                        'appointment_services_image' => optional($appointment->clinicservice)->file_url,
                        'appointment_date_and_time' => $startDate->format('Y-m-d H:i'),
                        'clinic_name' => optional($appointment->cliniccenter)->name,
                        'clinic_id' => optional($appointment->cliniccenter)->id,
                        'latitude' => null,
                        'longitude' => null,
                        'clinic_name' => $clinic_data->name,
                        'clinic_id' => $clinic_data->id
                    ];
                    $this->sendNotificationOnBookingUpdate('checkout_appointment', $notification_data);
                }
            }
        }

        $message = __('clinic.save_biiling_form');

        if ($request->is('api/*')) {
            return response()->json(['message' => $message, 'data' => $data, 'status' => true], 200);
        } else {
            return response()->json(['message' => $message, 'status' => true], 200);
        }
    }
    public function billing_detail(Request $request)
    {
        $id = $request->id;
        $appointments = BillingRecord::with('user', 'doctor', 'clinicservice', 'clinic', 'billingItem', 'patientencounter')
            ->where('id', $id)
            ->first();
        $billing = $appointments;
        
        // Debug logging to inspect the billing object and is_estimate value
        \Log::info('billing_detail - Billing object:', [
            'billing_id' => $billing->id ?? 'null',
            'is_estimate' => $billing->is_estimate ?? 'null',
            'is_estimate_type' => gettype($billing->is_estimate ?? null),
            'payment_status' => $billing->payment_status ?? 'null',
            'encounter_id' => $billing->encounter_id ?? 'null'
        ]);
        
        // Set appropriate title based on whether it's an estimate or invoice
        $module_action = $billing->is_estimate ? 'Estimate Detail' : 'Billing Detail';
        
        $timezone = Setting::where('name', 'default_time_zone')->value('val') ?? 'UTC';
        $setting = Setting::where('name', 'date_formate')->first();
        $dateformate = $setting ? $setting->val : 'Y-m-d';
        $setting = Setting::where('name', 'time_formate')->first();
        $timeformate = $setting ? $setting->val : 'h:i A';
        $combinedFormat = $dateformate . ' ' . $timeformate;
        return view('appointment::backend.billing_record.billing_detail', compact('module_action', 'billing', 'dateformate', 'timeformate', 'timezone', 'combinedFormat'));
    }

    public function EditBillingDetails(Request $request)
    {

        $encounter_id = $request->encounter_id;

        $data = [];

        $encounter_details = PatientEncounter::where('id', $encounter_id)->with('appointmentdetail', 'billingrecord')->first();

        if ($encounter_details->appointmentdetail) {

            $data['service_id'] = optional($encounter_details->appointmentdetail)->service_id ?? null;
            $data['payment_status'] = optional($encounter_details->appointmentdetail)->appointmenttransaction->payment_status ?? 0;
        } else {

            $data['service_id'] = optional($encounter_details->billingrecord)->service_id ?? null;
            $data['payment_status'] = optional($encounter_details->billingrecord)->payment_status ?? 0;
        }
        $data['billing_id'] = optional($encounter_details->billingrecord)->id ?? null;
        $data['final_discount'] = optional($encounter_details->billingrecord)->final_discount ?? 0;
        $data['final_discount_type'] = optional($encounter_details->billingrecord)->final_discount_type ?? 0;
        $data['final_discount_value'] = optional($encounter_details->billingrecord)->final_discount_value ?? 0;

        $data['appointment'] = $encounter_details->appointmentdetail;

        return response()->json(['data' => $data, 'status' => true]);
    }

    public function encounter_billing_detail(Request $request)
    {
        $encouter_id = $request->id;

        $appointments = BillingRecord::with('user', 'doctor', 'clinicservice', 'clinic', 'billingItem', 'patientencounter')
            ->where('encounter_id', $encouter_id)
            ->first();
        $billing = $appointments;
        
        // Debug logging to inspect the billing object and is_estimate value
        \Log::info('encounter_billing_detail - Billing object:', [
            'billing_id' => $billing->id ?? 'null',
            'is_estimate' => $billing->is_estimate ?? 'null',
            'is_estimate_type' => gettype($billing->is_estimate ?? null),
            'payment_status' => $billing->payment_status ?? 'null',
            'encounter_id' => $billing->encounter_id ?? 'null'
        ]);
        
        // Set appropriate title based on whether it's an estimate or invoice
        $module_action = $billing->is_estimate ? 'Estimate Detail' : 'Billing Detail';
        
        $setting = Setting::where('name', 'date_formate')->first();
        $dateformate = $setting ? $setting->val : 'Y-m-d';
        $setting = Setting::where('name', 'time_formate')->first();
        $timeformate = $setting ? $setting->val : 'h:i A';
        $timezone = Setting::where('name', 'default_time_zone')->value('val') ?? 'UTC';
        $combinedFormat = $dateformate . ' ' . $timeformate;
        return view('appointment::backend.billing_record.billing_detail', compact('module_action', 'billing', 'dateformate', 'timeformate', 'timezone', 'combinedFormat'));
    }
    public function saveBillingItems(Request $request)
    {
        $data = $request->all();
        
        // Log the incoming data for debugging
        \Log::info('saveBillingItems called with data:', $data);
        
        // Simple test to ensure method is working
        if (empty($data)) {
            \Log::error('saveBillingItems called with empty data');
            return response()->json(['message' => 'No data received', 'status' => false], 400);
        }
        
        // Validate required fields
        $requiredFields = ['billing_id', 'item_id', 'service_amount'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === null) {
                $errorMessage = "Required field '{$field}' is missing";
                \Log::error('Billing item validation failed: ' . $errorMessage, ['data' => $data]);
                
                if ($request->is('api/*')) {
                    return response()->json(['message' => $errorMessage, 'status' => false], 400);
                } else {
                    return response()->json(['message' => $errorMessage, 'status' => false], 400);
                }
            }
        }
        
        $quantity = $data['quantity'] ?? 1;
        $serviceAmount = $data['total_amount'] ?? 0; // total before tax and discount
        $unitPrice = $data['service_amount'] ?? 0;

        $discountAmount = 0;
        if (
            isset($data['discount_value'], $data['discount_type']) &&
            $data['discount_value'] != null && $data['discount_type'] != null
        ) {

            if ($data['discount_type'] == 'fixed') {
                $discountAmount = $data['discount_value'];
            } elseif ($data['discount_type'] == 'percentage') {
                $discountAmount = ($unitPrice * $data['discount_value']) / 100;
            }
        }

        $discountedUnitPrice = $unitPrice - $discountAmount;
        
        // Tax calculations removed - set to 0
        $totalInclusiveTax = 0;
        $data['inclusive_tax_amount'] = 0;
        $data['inclusive_tax'] = null;

        $finalTotal = $discountedUnitPrice * $quantity;
        $data['total_amount'] = $finalTotal;

        // Check if billing record exists
        $billingRecord = BillingRecord::find($data['billing_id']);
        if (!$billingRecord) {
            $errorMessage = "Billing record with ID {$data['billing_id']} not found";
            \Log::error('Billing item save failed: ' . $errorMessage, ['data' => $data]);
            
            if ($request->is('api/*')) {
                return response()->json(['message' => $errorMessage, 'status' => false], 404);
            } else {
                return response()->json(['message' => $errorMessage, 'status' => false], 404);
            }
        }

        $item = ClinicsService::where('id', $data['item_id'])->first();
        if (!$item) {
            $errorMessage = "Service with ID {$data['item_id']} not found";
            \Log::error('Billing item save failed: ' . $errorMessage, ['data' => $data]);
            
            if ($request->is('api/*')) {
                return response()->json(['message' => $errorMessage, 'status' => false], 404);
            } else {
                return response()->json(['message' => $errorMessage, 'status' => false], 404);
            }
        }

        $html = '';

        $data['item_name'] = $item->name;

        try {
            $billing_item = BillingItem::updateOrCreate(
                [
                    'billing_id' => $data['billing_id'],
                    'item_id' => $data['item_id'],
                ],
                $data
            );
        } catch (\Exception $e) {
            \Log::error('Billing item save failed: ' . $e->getMessage(), [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Failed to save billing item: ' . $e->getMessage(), 'status' => false], 500);
            } else {
                return response()->json(['message' => 'Failed to save billing item: ' . $e->getMessage(), 'status' => false], 500);
            }
        }

        $message = __('clinic.save_billing_item');

        if ($request->is('api/*')) {
            return response()->json(['message' => $message, 'data' => $data, 'status' => true], 200);
        } else {

            if ($data['type'] === 'encounter_details') {

                $service_details = [];
                $html = '';

                $data = BillingRecord::where('id', $data['billing_id'])->with('billingItem')->first();
                $encounter = PatientEncounter::where('id', $data['encounter_id'])->first();

                if (!empty($data)) {
                    $html = view('appointment::backend.patient_encounter.component.service_list', [
                        'data' => $data,
                        'status' => $encounter['status']
                    ])->render();
                }

                $service_details['service_total'] = 0; // Default value
                $service_details['total_tax'] = 0;
                $service_details['total_amount'] = 0;
                $service_details['final_discount'] =  0;
                $service_details['final_discount_value'] =  0;
                $service_details['final_discount_type'] =  null;
                $service_details['final_discount_amount'] = 0;

                if (!empty($data->billingItem) && is_array($data->billingItem->toArray())) {

                    $service_details['service_total'] = array_sum(array_column($data->billingItem->toArray(), 'total_amount'));

                    if ($data['final_discount'] == 1 && $data['final_discount_value'] > 0) {


                        $service_details['final_discount'] =  $data['final_discount'];
                        $service_details['final_discount_value'] =  $data['final_discount_value'];
                        $service_details['final_discount_type'] =  $data['final_discount_type'];


                        if ($data['final_discount_type'] == 'fixed') {

                            $service_details['final_discount_amount'] = $data['final_discount_value'];
                        } else {

                            $service_details['final_discount_amount'] = ($data['final_discount_value'] * $service_details['service_total']) / 100;
                        }
                    }


                    // Tax calculations completely removed
                    $service_details['total_tax'] = 0;
                    $service_details['total_amount'] = $service_details['service_total'] - $service_details['final_discount_amount'];
                }

                $total_paid = Installment::where('billing_record_id', $data->id)->sum('amount');
                $remaining_amount = $service_details['total_amount'] - $total_paid;
                $service_details['total_paid_amount'] = $total_paid;
                $service_details['remaining_amount'] = $remaining_amount > 0 ? $remaining_amount : 0;

                return response()->json([
                    'html' => $html,
                    'service_details' => $service_details,
                ]);
            } else {

                return response()->json(['message' => $message, 'data' => $data, 'status' => true], 200);
            }
        }
    }

    public function billing_item_list(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        $query = BillingItem::with('clinicservice');
        if ($request->has('filter')) {
            $filters = $request->input('filter');
            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }
        }
        $billingItems = $query->orderBy('updated_at', 'desc')->paginate($perPage);
        $billingitemCollection = BillingItemResource::collection($billingItems);

        return response()->json([
            'status' => true,
            'data' => $billingitemCollection,
            'message' => __('appointment.lbl_billing_item_list'),
        ], 200);
    }

    public function billing_item_detail(Request $request)
    {
        $billingid = $request->billing_id;
        $data = [];
        if ($billingid != null) {
            $billingItems = BillingItem::with('clinicservice')
                ->where('billing_id', $billingid)
                ->get();

            foreach ($billingItems as $billingItem) {
                $name = optional($billingItem->clinicservice)->name;
                // $servicePricedata = [
                //     'service_price' => $billingItem->service_amount,
                //     'total_amount' => $billingItem->total_amount,
                // ];
                $data[] = [
                    'id' => $billingItem->id,
                    'billing_id' => $billingItem->billing_id,
                    'name' => $name,
                    'item_id' => $billingItem->item_id,
                    'service_price' => $billingItem->service_amount,
                    'total_amount' => $billingItem->total_amount,
                    'discount_value' => $billingItem->discount_value,
                    'discount_type' => $billingItem->discount_type,
                    'quantity' => $billingItem->quantity,
                ];
            }
        }

        return response()->json(['data' => $data, 'status' => true]);
    }
    public function editBillingItem(Request $request, $id)
    {

        $billing_item = BillingItem::where('id', $id)->first();


        return response()->json(['data' => $billing_item, 'status' => true]);
    }

    public function updateBillingItem(Request $request)
    {
        $billing_item_id = $request->get('billing_item_id');
        $billing_item = BillingItem::where('id', $billing_item_id)->first();

        if (!$billing_item) {
            return response()->json([
                'status' => false,
                'message' => 'Billing item not found'
            ]);
        }

        // Update the billing item
        $billing_item->update([
            'item_id' => $request->get('item_id'),
            'service_amount' => $request->get('service_amount'),
            'quantity' => $request->get('quantity'),
            'total_amount' => $request->get('total_amount'),
            'discount_value' => $request->get('discount_value'),
            'discount_type' => $request->get('discount_type'),
            'inclusive_tax_amount' => $request->get('inclusive_tax_amount'),
            'inclusive_tax' => $request->get('inclusive_tax'),
        ]);

        // Get updated billing record data
        $billing_id = $billing_item->billing_id;
        $data = BillingRecord::where('id', $billing_id)->with('billingItem')->first();
        $encounter = PatientEncounter::where('id', $data['encounter_id'])->first();

        $html = '';
        if (!empty($data)) {
            $html = view('appointment::backend.patient_encounter.component.service_list', [
                'data' => $data,
                'status' => $encounter['status']
            ])->render();
        }

        // Calculate service details
        $service_details = [];
        $service_details['service_total'] = 0;
        $service_details['total_tax'] = 0;
        $service_details['total_amount'] = 0;
        $service_details['final_discount'] = 0;
        $service_details['final_discount_value'] = 0;
        $service_details['final_discount_type'] = null;
        $service_details['final_discount_amount'] = 0;

        if (!empty($data->billingItem) && is_array($data->billingItem->toArray())) {
            $service_details['service_total'] = array_sum(array_column($data->billingItem->toArray(), 'total_amount'));

            if ($data['final_discount'] == 1 && $data['final_discount_value'] > 0) {
                $service_details['final_discount'] = $data['final_discount'];
                $service_details['final_discount_value'] = $data['final_discount_value'];
                $service_details['final_discount_type'] = $data['final_discount_type'];

                if ($data['final_discount_type'] == 'fixed') {
                    $service_details['final_discount_amount'] = $data['final_discount_value'];
                } else {
                    $service_details['final_discount_amount'] = ($data['final_discount_value'] * $service_details['service_total']) / 100;
                }
            }

            // $taxDetails = getBookingTaxamount($service_details['service_total'] - $service_details['final_discount_amount'], null); // Tax calculation disabled
            $service_details['total_tax'] = 0; // Tax calculation disabled

            $service_details['total_amount'] = $service_details['service_total'] - $service_details['final_discount_amount']; // Tax calculation disabled
        }

        // Calculate paid and remaining amounts
        $total_paid = Installment::where('billing_record_id', $data->id)->sum('amount');
        $remaining_amount = $service_details['total_amount'] - $total_paid;
        $service_details['total_paid_amount'] = $total_paid;
        $service_details['remaining_amount'] = $remaining_amount > 0 ? $remaining_amount : 0;

        return response()->json([
            'status' => true,
            'message' => 'Billing item updated successfully',
            'html' => $html,
            'service_details' => $service_details,
        ]);
    }
    public function deleteBillingItem(Request $request, $id)
    {
        $billing_item = BillingItem::where('id', $id)->first();

        $billing_id = $billing_item->billing_id;

        $billing_item->forceDelete();

        if ($request->is('api/*')) {

            $message = __('appointment.billing_item_delete');

            return response()->json(['message' => $message, 'status' => true], 200);
        } else {

            // $billing_item = BillingItem::where('billing_id', $billing_item->billing_id)->get();

            $service_details = [];
            $html = '';

            $data = BillingRecord::where('id', $billing_id)->with('billingItem')->first();
            $encounter = PatientEncounter::where('id', $data['encounter_id'])->first();

            if (!empty($data)) {
                $html = view('appointment::backend.patient_encounter.component.service_list', [
                    'data' => $data,
                    'status' => $encounter['status']
                ])->render();
            }

            $service_details['service_total'] = 0; // Default value
            $service_details['total_tax'] = 0;
            $service_details['total_amount'] = 0;
            $service_details['final_discount'] =  0;
            $service_details['final_discount_value'] =  0;
            $service_details['final_discount_type'] =  null;
            $service_details['final_discount_amount'] = 0;


            if (!empty($data->billingItem) && is_array($data->billingItem->toArray())) {
                $service_details['service_total'] = array_sum(array_column($data->billingItem->toArray(), 'total_amount'));

                if ($data['final_discount'] == 1 && $data['final_discount_value'] > 0) {


                    $service_details['final_discount'] =  $data['final_discount'];
                    $service_details['final_discount_value'] =  $data['final_discount_value'];
                    $service_details['final_discount_type'] =  $data['final_discount_type'];

                    if ($data['final_discount_type'] == 'fixed') {

                        $service_details['final_discount_amount'] = $data['final_discount_value'];
                    } else {

                        $service_details['final_discount_amount'] = ($data['final_discount_value'] * $service_details['service_total']) / 100;
                    }
                }

                // $taxDetails = getBookingTaxamount($service_details['service_total'] - $service_details['final_discount_amount'], null); // Tax calculation disabled
                $service_details['total_tax'] = 0; // Tax calculation disabled


                $service_details['total_amount'] = $service_details['service_total'] - $service_details['final_discount_amount']; // Tax calculation disabled
            }

            // Calculate paid and remaining amounts
            $total_paid = Installment::where('billing_record_id', $data->id)->sum('amount');
            $remaining_amount = $service_details['total_amount'] - $total_paid;
            $service_details['total_paid_amount'] = $total_paid;
            $service_details['remaining_amount'] = $remaining_amount > 0 ? $remaining_amount : 0;

            return response()->json([
                'html' => $html,
                'service_details' => $service_details,
            ]);
        }
    }

    public function getBillingItem($id)
    {
        $service_details = [];
        $html = '';

        $data = BillingRecord::where('id', $id)->with('billingItem')->first();
        $encounter = PatientEncounter::where('id', $data['encounter_id'])->first();

        $service_details['service_total'] = 0; // Default value
        $service_details['total_tax'] = 0;
        $service_details['total_amount'] = 0;


        $service_details['final_discount'] = 0;
        $service_details['final_discount_value'] = 0;
        $service_details['final_discount_type'] = 'percentage';
        $service_details['final_discount_amount'] = 0;

        if (!empty($data->billingItem) && is_array($data->billingItem->toArray())) {

            $service_details['service_total'] = array_sum(array_column($data->billingItem->toArray(), 'total_amount'));

            if ($data['final_discount'] == 1 && $data['final_discount_value'] > 0) {
                $service_details['final_discount'] =  $data['final_discount'];
                $service_details['final_discount_value'] =  $data['final_discount_value'];
                $service_details['final_discount_type'] =  $data['final_discount_type'];

                if ($data['final_discount_type'] == 'fixed') {

                    $service_details['final_discount_amount'] = $data['final_discount_value'];

                    // Tax calculation removed
                    $service_details['total_tax'] = 0;
                } else {

                    $service_details['final_discount_amount'] = ($data['final_discount_value'] * $service_details['service_total']) / 100;

                    // Tax calculation removed
                    $service_details['total_tax'] = 0;
                }
            }

            // Tax calculation removed
            $service_details['total_tax'] = 0;

            $service_details['total_amount'] = $service_details['service_total'] - $service_details['final_discount_amount'];
        }

        $service_details['service_total'] = $service_details['service_total'];
        $service_details['total_amount'] = $service_details['total_amount'];

        $total_paid = Installment::where('billing_record_id', $data->id)->sum('amount');
        $remaining_amount = $service_details['total_amount'] - $total_paid;
        $service_details['total_paid_amount'] = $total_paid;
        $service_details['remaining_amount'] = $remaining_amount > 0 ? $remaining_amount : 0;

        return response()->json([
            'service_details' => $service_details,
        ]);
    }

    public function CalculateDiscount(Request $request)
    {

        $service_details = [];

        $data = BillingRecord::where('id', $request->billing_id)->with('billingItem')->first();
        $encounter = PatientEncounter::where('id', $data['encounter_id'])->first();


        $service_details['service_total'] = 0; // Default value
        $service_details['total_tax'] = 0;
        $service_details['total_amount'] = 0;
        $service_details['final_discount_amount'] = 0;


        if (!empty($data->billingItem) && is_array($data->billingItem->toArray())) {
            $service_details['service_total'] = array_sum(array_column($data->billingItem->toArray(), 'total_amount'));
            // $taxDetails = getBookingTaxamount($service_details['service_total'], null); // Tax calculation disabled
            $service_details['total_tax'] = 0; // Tax calculation disabled

            if ($request->discount_value > 0) {

                if ($request->discount_type == 'fixed') {

                    $service_details['final_discount_amount'] = $request->discount_value;

                    // $taxDetails = getBookingTaxamount($service_details['service_total'] - $service_details['final_discount_amount'], null); // Tax calculation disabled
                    $service_details['total_tax'] = 0; // Tax calculation disabled
                    $service_details['service_total'] = $service_details['service_total'];
                } else {

                    $service_details['final_discount_amount'] = ($request->discount_value * $service_details['service_total']) / 100;

                    // $taxDetails = getBookingTaxamount($service_details['service_total'] - $service_details['final_discount_amount'], null); // Tax calculation disabled
                    $service_details['total_tax'] = 0; // Tax calculation disabled
                    $service_details['service_total'] = $service_details['service_total'];
                }
            }

            $service_details['total_amount'] = $service_details['service_total'] - $service_details['final_discount_amount']; // Tax calculation disabled
            $total_paid = Installment::where('billing_record_id', $data->id)->sum('amount');
            $remaining_amount = $service_details['total_amount'] - $total_paid;
            $service_details['total_paid_amount'] = $total_paid;
            $service_details['remaining_amount'] = $remaining_amount > 0 ? $remaining_amount : 0;
        }

        return response()->json([

            'service_details' => $service_details,
        ]);
    }

    public function SaveBillingData(Request $request)
    {
        try {
            $data = $request->all();
            
            // Validate that encounter_id exists
            if (!isset($data['encounter_id']) || empty($data['encounter_id'])) {
                throw new \Exception('Encounter ID is required');
            }
            
            $billingData = BillingRecord::where('encounter_id', $data['encounter_id'])->with('billingItem')->first();
            if (!$billingData) {
                throw new \Exception('Billing record not found for encounter ID: ' . $data['encounter_id']);
            }
            
            $encounter = PatientEncounter::find($data['encounter_id']);
            if (!$encounter) {
                throw new \Exception('Patient encounter not found for ID: ' . $data['encounter_id']);
            }

        $serviceDetails = [
            'service_total' => 0,
            'total_tax' => 0,
            'total_amount' => 0,
            'final_discount_amount' => 0,
        ];

        if (!empty($billingData->billingItem)) {
            $billingItems = $billingData->billingItem->toArray();
            $serviceDetails['service_total'] = array_sum(array_column($billingItems, 'total_amount'));

            $discountValue = $request->final_discount_value ?? 0;
            $discountType = $request->final_discount_type ?? 'fixed';

            $serviceDetails['final_discount_amount'] = $discountType === 'fixed'
                ? $discountValue
                : ($discountValue * $serviceDetails['service_total']) / 100;

            $netTotal = $serviceDetails['service_total'] - $serviceDetails['final_discount_amount'];
            // $taxDetails = getBookingTaxamount($netTotal, null); // Tax calculation disabled

            $serviceDetails['total_tax'] = 0; // Tax calculation disabled

            $serviceDetails['total_amount'] = $netTotal; // Tax calculation disabled
        }

        $billingData->update([
            'payment_status' => $data['payment_status'],
            'notes' => $data['notes'],
            'final_discount' => $data['final_discount'],
            'final_discount_type' => $data['final_discount_type'],
            'final_discount_value' => $data['final_discount_value'],
            'final_tax_amount' => $serviceDetails['total_tax'],
            'final_total_amount' => $serviceDetails['total_amount'],
            'is_estimate' => $data['is_estimate'] ?? false,
        ]);

        // Check if this is an estimate
        if ($data['is_estimate'] ?? false) {
            // For estimates, always set payment status to 0 but DON'T close encounter
            $data['payment_status'] = 0;
            $billingData->update(['payment_status' => 0]);
            // DON'T close encounter: $encounter->update(['status' => 0]);
            // DON'T change appointment status: keep it as check_in
        } else {
            // Check if payable amount is less than installments and update payment status
            $totalPaidInstallments = Installment::where('billing_record_id', $billingData->id)->sum('amount');
            $payableAmount = $serviceDetails['total_amount'] ?? 0;

            if ($totalPaidInstallments >= $payableAmount && $payableAmount > 0) {
                $data['payment_status'] = 1;
                // Update payment status to paid (1) when installments cover the payable amount
                $billingData->update(['payment_status' => 1]);

                // Update appointment transaction if encounter has appointment
                if ($encounter && $encounter->appointment_id !== null) {
                    AppointmentTransaction::where('appointment_id', $encounter->appointment_id)
                        ->update([
                            'payment_status' => 1,
                        ]);
                }
            }

            // update encounter status
            if ($data['payment_status'] == 1) {
                $encounter?->update(['status' => 0]);
            }
        }

        $encounter_details = PatientEncounter::find($data['encounter_id']);

        if ($encounter_details['appointment_id'] !== null && $data['payment_status'] == 1) {
            $finalTotalAmount = $serviceDetails['total_amount'] ?? 0;
            $paymentStatus = $data['payment_status'];

            // Update the appointment transaction
            AppointmentTransaction::where('appointment_id', $encounter_details['appointment_id'])
                ->update([
                    'total_amount' => $finalTotalAmount,
                    'payment_status' => $paymentStatus,
                ]);

            // Only process commission if doctor exists and commission data is available
            if ($encounter_details['doctor_id'] && $earning_data = $this->commissionData($encounter_details)) {
                try {
                    $appointment = Appointment::findOrFail($encounter_details['appointment_id']);

                    // Save doctor commission
                    $earning_data['commission_data']['user_type'] = 'doctor';
                    $earning_data['commission_data']['commission_status'] = $paymentStatus == 1 ? 'unpaid' : 'pending';
                    $commissionEarning = new CommissionEarning($earning_data['commission_data']);
                    $appointment->commission()->save($commissionEarning);

                    // Safely get vendor_id - check if service_details exists first
                    $vendor_id = null;
                    if (isset($data['service_details']) && is_array($data['service_details'])) {
                        $vendor_id = $data['service_details']['vendor_id'] ?? null;
                    }

                    $vendor = User::find($vendor_id);

                // Determine admin and vendor commission logic
                if (multiVendor() != 1) {
                    // Admin commission when not multi-vendor
                    $adminEarningData = [
                        'user_type' => $vendor->user_type ?? 'admin',
                        'employee_id' => $vendor->id ?? User::where('user_type', 'admin')->value('id'),
                        'commissions' => null,
                        'commission_status' => $paymentStatus == 1 ? 'unpaid' : 'pending',
                        'commission_amount' => $finalTotalAmount - $earning_data['commission_data']['commission_amount'],
                    ];
                    $adminCommissionEarning = new CommissionEarning($adminEarningData);

                    $appointment->commission()->save($adminCommissionEarning);
                } else {
                    // Logic for multi-vendor scenario
                    if ($vendor && $vendor->user_type == 'vendor') {
                        // Admin earning for vendor
                        $adminEarning = $this->AdminEarningData($encounter_details);
                        $adminEarning['user_type'] = 'admin';
                        $adminEarning['commission_status'] = $paymentStatus == 1 ? 'unpaid' : 'pending';

                        $adminCommissionEarning = new CommissionEarning($adminEarning);

                        $appointment->commission()->save($adminCommissionEarning);

                        // Vendor earning
                        $vendorEarningData = [
                            'user_type' => $vendor->user_type,
                            'employee_id' => $vendor->id,
                            'commissions' => null,
                            'commission_status' => $paymentStatus == 1 ? 'unpaid' : 'pending',
                            'commission_amount' => $finalTotalAmount - $adminEarning['commission_amount'] - $earning_data['commission_data']['commission_amount'],
                        ];
                        $vendorCommissionEarning = new CommissionEarning($vendorEarningData);
                        $appointment->commission()->save($vendorCommissionEarning);
                    } else {
                        // Fallback to admin earning if vendor is not found
                        $adminEarningData = [
                            'user_type' => 'admin',
                            'employee_id' => User::where('user_type', 'admin')->value('id'),
                            'commissions' => null,
                            'commission_status' => $paymentStatus == 1 ? 'unpaid' : 'pending',
                            'commission_amount' => $finalTotalAmount - $earning_data['commission_data']['commission_amount'],
                        ];


                        $adminCommissionEarning = new CommissionEarning($adminEarningData);
                        $appointment->commission()->save($adminCommissionEarning);
                    }
                }
                } catch (\Exception $e) {
                    // Log the error but don't fail the entire operation
                    \Log::error('Commission processing failed: ' . $e->getMessage(), [
                        'encounter_id' => $data['encounter_id'],
                        'appointment_id' => $encounter_details['appointment_id'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }


        // Handle regular invoice processing (non-estimate)
        if (!$data['is_estimate'] && $data['payment_status'] == 1) {

            // PatientEncounter::where('id', $data['encounter_id'])->update(['status' => $request->encounter_status]);

            if ($encounter_details['appointment_id'] != null && $data['payment_status'] == 1) {

                $appointment = Appointment::where('id', $encounter_details['appointment_id'])->first();

                if ($appointment && $appointment->status == 'check_in') {
                    $finalTotalAmount = $data['final_total_amount'] ?? 0;
                    $appointment->update([
                        'total_amount' => $finalTotalAmount,
                        'status' => 'checkout',
                    ]);
                    $startDate = Carbon::parse($appointment['start_date_time']);
                    $notification_data = [
                        'id' => $appointment->id,
                        'description' => $appointment->description,
                        'appointment_duration' => $appointment->duration,
                        'user_id' => $appointment->user_id,
                        'user_name' => optional($appointment->user)->first_name ?? default_user_name(),
                        'doctor_id' => $appointment->doctor_id,
                        'doctor_name' => optional($appointment->doctor)->first_name,
                        'clinic_name' => optional($appointment->cliniccenter)->name,
                        'clinic_id' => optional($appointment->cliniccenter)->id,
                        'appointment_date' => $startDate->format('d/m/Y'),
                        'appointment_time' => $startDate->format('h:i A'),
                        'appointment_services_names' => ClinicsService::with('systemservice')->find($appointment->service_id)->systemservice->name ?? '--',
                        'appointment_services_image' => optional($appointment->clinicservice)->file_url,
                        'appointment_date_and_time' => $startDate->format('Y-m-d H:i'),
                        'latitude' => null,
                        'longitude' => null,
                    ];
                    $this->sendNotificationOnBookingUpdate('checkout_appointment', $notification_data);
                }
            }
        }

        return response()->json([
            'message' => 'Billing details saved successfully',
            'status' => true,
        ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('SaveBillingData failed: ' . $e->getMessage(), [
                'encounter_id' => $data['encounter_id'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'An error occurred while saving billing data: ' . $e->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    public function storeInstallment(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|in:cash,card,online',
            'installment_date' => 'required|date', // validate the form field
        ]);

        $totalAmount = $request->total_payable_amount ?? 0;

        // ✅ Only sum installments for this billing record
        $totalPaid = Installment::where('billing_record_id', $request->billing_record_id)->sum('amount');
        $remainingAmount = $totalAmount - $totalPaid;

        if ($request->amount > $remainingAmount) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'amount' => ['The entered amount exceeds the remaining due amount.'],
                ]
            ], 422);
        }

        // ✅ Create new installment
        Installment::create([
            'billing_record_id' => $request->billing_record_id,
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'date' => $request->installment_date, // map form field to DB column
            'created_at' => now(),
        ]);

        // ✅ Recalculate total paid after inserting new installment
        $updatedTotalPaid = Installment::where('billing_record_id', $request->billing_record_id)->sum('amount');
        $updatedRemaining = $totalAmount - $updatedTotalPaid;

        $saveBillingInfo = false;
        if ($updatedRemaining <= 0) {
            $saveBillingInfo = true;
        }

        $data = Installment::where('billing_record_id', $request->billing_record_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $view = view('appointment::backend.patient_encounter.component.installment_list', ['data' => $data])->render();

        return response()->json([
            'success' => true,
            'html' => $view,
            'message' => 'Installment added successfully!',
            'total_paid' => Currency::format($updatedTotalPaid),
            'remaining_amount' => Currency::format($updatedRemaining > 0 ? $updatedRemaining : 0),
            'save_billing' => $saveBillingInfo,
        ]);
    }

    public function downloadInstallmentPDF($installment_id)
    {
        $installment = Installment::with('billingrecord')->findOrFail($installment_id);
        $billingRecord = $installment->billingrecord;

        if (!$billingRecord || !$billingRecord->patientencounter || !$billingRecord->patientencounter->appointmentdetail) {
            return abort(404, 'Appointment not found');
        }

        $appointment = $billingRecord->patientencounter->appointmentdetail;

        $appointments = Appointment::with([
            'user',
            'doctor',
            'clinicservice',
            'cliniccenter.media', // Load media relationship
            'patientEncounter.billingrecord.installments', // include installments
        ])
            ->where('id', $appointment->id)
            ->get();

        if ($appointments->isEmpty()) {
            return abort(404, 'Appointment not found or unpaid');
        }

        // Add extra fields
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

        // Common settings
        $dateformate = \App\Models\Setting::where('name', 'date_formate')->first()->val ?? 'Y-m-d';
        $timeformate = \App\Models\Setting::where('name', 'time_formate')->first()->val ?? 'h:i A';


        // Render PDF using the same variables as downloadPDF()
        $pdf = PDF::loadView("appointment::backend.clinic_appointment.installment_invoice", [
            'data' => $data,
            'dateformate' => $dateformate,
            'timeformate' => $timeformate,
            'installment' => $installment,
            'billingRecord' => $billingRecord,
        ]);

        return $pdf->download("installment-invoice-{$installment_id}.pdf");
    }

    public function downloadBillingPDF($id)
    {
        try {
            $data = BillingRecord::with([
                'user',
                'user.cities',
                'user.states',
                'user.countries',
                'clinic',
                'clinic.cities',
                'clinic.states',
                'clinic.countries',
                'doctor',
                'doctor.doctor',
                'patientencounter',
                'patientencounter.appointmentdetail',
                'billingItem'
            ])->findOrFail($id);

            // Check if this is an estimate or invoice
            $is_estimate = $data->is_estimate ?? false;
            $template = $is_estimate ? "appointment::backend.encounter_template.estimate" : "appointment::backend.encounter_template.invoice";
            
            $pdf = PDF::loadHTML(view($template, ['data' => $data])->render())
                ->setOptions(['defaultFont' => 'sans-serif']);

            $filename = ($is_estimate ? 'estimate_' : 'invoice_') . $id . '.pdf';
            return $pdf->download($filename);
            // return $pdf->stream($filename);

        } catch (\Exception $e) {
            \Log::error('Failed to download billing PDF: ' . $e->getMessage(), [
                'billing_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return abort(500, 'Failed to generate PDF');
        }
    }
}
