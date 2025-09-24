@extends('backend.layouts.app')

@section('title')
    @if(isset($data) && count($data) > 0 && isset($data[0]['patient_encounter']['billingrecord']['is_estimate']) && $data[0]['patient_encounter']['billingrecord']['is_estimate'])
        {{ __('appointment.estimate') }} {{ __('clinic.detail') }}
    @else
        {{ __($module_title) }}
    @endif
@endsection
@section('content')
    <b-row>
        <b-col sm="12">
            <div id="bill">

                @foreach ($data as $info)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <p class="mb-0">
                                            @if(optional(optional($info['patient_encounter'])['billingrecord'])['is_estimate'])
                                                {{ __('appointment.estimate') }} :
                                            @else
                                                {{ __('messages.invoice_id') }} :
                                            @endif
                                            <span class="text-secondary">
                                                #{{ $info['id'] ?? '--' }}</span>
                                        </p>
                                        <p class="mb-0">
                                            {{ __('messages.payment_status') }}
                                            @if (optional(optional($info['patient_encounter'])['billingrecord'])['is_estimate'])
                                                <span class="text-capitalize badge bg-info-subtle p-2">{{ __('appointment.estimate') }}</span>
                                            @elseif ($info['appointmenttransaction']['payment_status'] == 1)
                                                <span class="text-capitalize badge bg-success-subtle p-2">{{ __('messages.paid') }}</span>
                                            @else
                                                <span class="text-capitalize badge bg-soft-danger p-2">{{ __('messages.unpaid') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    @php
                                        $setting = App\Models\Setting::where('name', 'date_formate')->first();
                                        $dateformate = $setting ? $setting->val : 'Y-m-d';
                                        $setting = App\Models\Setting::where('name', 'time_formate')->first();
                                        $timeformate = $setting ? $setting->val : 'h:i A';
                                        $createdDate = date($dateformate, strtotime($info['appointment_date'] ?? '--'));
                                        $createdTime = date($timeformate, strtotime($info['appointment_time'] ?? '--'));
                                    @endphp

                                    <p class="mb-0 mt-1">
                                        {{ __('messages.appointment_at') }}: <span class="font-weight-bold text-dark">
                                            {{ $createdDate }}</span>
                                    </p>
                                    <p class="mb-0 mt-1">
                                        {{ __('messages.appointment_time') }}: <span class="font-weight-bold text-dark">
                                            {{ $createdTime }}</span>
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-3">
                        <div class="col-md-6 col-lg-6">
                            <h5 class="mb-3">Clinic Info</h5>
                            <div class="card card-block card-stretch card-height mb-0">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="image-block">
                                            <img src="{{ $info['cliniccenter']['file_url'] ?? '--' }}"
                                                class="img-fluid avatar avatar-50 rounded-circle" alt="image">
                                        </div>
                                        <div class="content-detail">
                                            <h5 class="mb-2">{{ $info['cliniccenter']['name'] ?? '--' }}</h5>
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                <i class="ph ph-envelope"></i>
                                                <u class="text-secondary">{{ $info['cliniccenter']['email'] ?? '--' }}</u>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <i class="ph ph-map-pin"></i>
                                                <span>{{ $info['cliniccenter']['address'] ?? '--' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <h5 class="mb-3">{{ __('messages.patient_detail') }}</h5>
                            <div class="card card-block card-stretch card-height mb-0">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="image-block">
                                            <img src="{{ $info['user']['profile_image'] ?? '--' }}"
                                                class="img-fluid avatar avatar-50 rounded-circle" alt="image">
                                        </div>
                                        <div class="content-detail">
                                            <h5 class="mb-2">
                                                {{ $info['user']['first_name'] . '' . $info['user']['last_name'] ?? '--' }}
                                            </h5>
                                            <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                                                @if ($info['user']['gender'] !== null)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="ph ph-user text-dark"></i>
                                                        <span class="">{{ $info['user']['gender'] ?? '--' }}</span>
                                                    </div>
                                                @endif
                                                @if ($info['user']['date_of_birth'] !== null)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="ph ph-cake text-dark"></i>
                                                        <span
                                                            class="">{{ date($dateformate, strtotime($info['user']['date_of_birth'])) ?? '--' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-3">
                        <div class="col-md-12 col-lg-12">
                            <h5 class="mb-3 mt-3">{{ __('messages.service') }}</h5>
                            <div class="card card-block card-stretch card-height mb-0">
                                <div class="card-body">
                                    @if (!isset($info['patient_encounter']))
                                        <div class="content-detail">
                                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                                <span>{{ __('messages.item_name') }}</span>
                                                <span class="text-dark">{{ $info['clinicservice']['name'] ?? '--' }}</span>
                                            </div>



                                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                                <span>{{ __('messages.price') }}</span>
                                                <span
                                                    class="text-dark">{{ Currency::format($info['service_price']) ?? '--' }}</span>
                                            </div>
                                            {{-- <div class="d-flex flex-wrap align-items-center justify-content-between">
                                    <span>{{ __('messages.total') }}</span>
                                    <span class="text-dark">{{ Currency::format($info['service_amount']) ?? '--'
                                        }}</span>
                                </div> --}}
                                        </div>
                                    @endif

                                    @if (isset($info['patient_encounter']) &&
                                            !empty($info['patient_encounter']['billingrecord']) &&
                                            !empty($info['patient_encounter']['billingrecord']['billing_item']))
                                        @foreach ($info['patient_encounter']['billingrecord']['billing_item'] as $billingItem)
                                            <div class="d-flex align-items-center bg-body p-4 rounded">
                                                <div class="detail-box bg-white rounded">
                                                    <img src="{{ $billingItem['clinicservice']['file_url'] ?? default_file_url() }}"
                                                        alt="avatar" class="avatar avatar-80 rounded-pill">
                                                </div>

                                                <div
                                                    class="ms-3 w-100 d-flex align-items-center justify-content-between flex-wrap">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <b>{{ $billingItem['clinicservice']['name'] ?? 'N/A' }}</b>
                                                            {{ $billingItem['clinicservice']['description'] ?? ' ' }}
                                                        </span>
                                                    </div>

                                                    @php
                                                        // Calculate the payable amount based on discount type
                                                        if ($billingItem['discount_type'] === 'percentage') {
                                                            $payable_Amount =
                                                                $billingItem['service_amount'] -
                                                                $billingItem['service_amount'] *
                                                                    ($billingItem['discount_value'] / 100);
                                                        } else {
                                                            $payable_Amount =
                                                                $billingItem['service_amount'] -
                                                                $billingItem['discount_value'];
                                                        }
                                                    @endphp

                                                    @if ($billingItem['discount_value'] > 0)
                                                        <h5 class="mb-0 w-50 text-lg-end text-sm-start">
                                                            {{ Currency::format($payable_Amount) }}
                                                            @if ($billingItem['discount_type'] === 'percentage')
                                                                (<span>{{ $billingItem['discount_value'] ?? '--' }}%</span>)
                                                                off
                                                            @else
                                                                (<span>{{ Currency::format($billingItem['discount_value']) ?? '--' }}</span>)
                                                                off
                                                            @endif
                                                        </h5>
                                                        <del>{{ Currency::format($billingItem['service_amount']) }}</del>
                                                    @else
                                                        <h5 class="mb-0 w-50 text-lg-end text-sm-start">
                                                            {{ Currency::format($billingItem['total_amount']) }}</h5>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $installments = Modules\Appointment\Models\Installment::where(
                            'billing_record_id',
                            $info['patient_encounter']['billingrecord']['id'],
                        )->get();
                    @endphp
                    
                    {{-- Only show installments for invoices, not estimates --}}
                    @unless(optional(optional($info['patient_encounter'])['billingrecord'])['is_estimate'])
                    <div class="row gy-3">
                        <div class="col-md-12 col-lg-12">
                            <h5 class="mb-3 mt-5">{{ __('appointment.installments') }}</h5>
                            <div class="card card-block card-stretch card-height mb-0">
                                <div class="card-body">
                                    <div class="table-responsive rounded">
                                        <table class="table table-lg m-0">
                                            <thead>
                                                <tr class="text-white">
                                                    <th>{{ __('appointment.sr_no') }}</th>
                                                    <th>{{ __('appointment.amount') }}</th>
                                                    <th>{{ __('clinic.lbl_payment_mode') }}</th>
                                                    <th>{{ __('appointment.date') }}</th>
                                                    <th>{{ __('appointment.invoice_detail') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $timezone =
                                                        App\Models\Setting::where('name', 'default_time_zone')->value(
                                                            'val',
                                                        ) ?? 'UTC';
                                                    $setting = App\Models\Setting::where(
                                                        'name',
                                                        'date_formate',
                                                    )->first();
                                                    $dateformate = $setting ? $setting->val : 'Y-m-d';
                                                    $setting = App\Models\Setting::where(
                                                        'name',
                                                        'time_formate',
                                                    )->first();
                                                    $timeformate = $setting ? $setting->val : 'h:i A';
                                                @endphp
                                                @if (count($installments) > 0)
                                                    @foreach ($installments as $index => $iteam)
                                                        <tr>
                                                            <td>
                                                                <h6 class="text-primary">
                                                                    {{ $index + 1 }}
                                                                </h6>
                                                            </td>
                                                            <td>
                                                                {{ Currency::format($iteam['amount']) }}
                                                            </td>
                                                            <td>
                                                                {{ $iteam['payment_mode'] }}
                                                            </td>
                                                            <td>
                                                                {{ isset($iteam['date'])
                                                                    ? \Carbon\Carbon::parse($iteam['date'])->timezone($timezone)->format($dateformate)
                                                                    : '--' }}
                                                            </td>
                                                            <td>
                                                                <div class="text-end d-flex gap-3 align-items-center">
                                                                    <a href="{{ route('backend.billing-record.download.installment.pdf', $iteam['id']) }}"
                                                                        data-type="ajax" class="btn text-info p-0 fs-5"
                                                                        data-bs-toggle="tooltip" aria-label="Invoice Detail"
                                                                        data-bs-original-title="Invoice Detail">
                                                                        <i class="ph ph-file-pdf"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="my-1 text-danger text-center">
                                                                {{ __('appointment.no_installment_found') }}</div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endunless


                    {{-- Only show price details for invoices, not estimates --}}
                    @unless(optional(optional($info['patient_encounter'])['billingrecord'])['is_estimate'])
                    @php
                        // Calculate service total amount from billing items
                        $service_total_amount = 0;
                        // $total_tax = 0; // Tax calculation disabled
                    @endphp
                    @foreach (optional(optional($info['patient_encounter'])['billingrecord'])['billing_item'] as $item)
                        @php
                            $service_total_amount += $item['total_amount'];
                        @endphp
                    @endforeach
                    {{-- @if ($info['appointmenttransaction']['tax_percentage'] !== null) --}}
                        @php
                            // Get tax data and billing record
                            $tax = $info['appointmenttransaction']['tax_percentage'];
                            $taxData = json_decode($tax, true);
                            $transaction = optional(optional($info['patient_encounter'])['billingrecord'])
                                ? optional(optional($info['patient_encounter'])['billingrecord'])
                                : null;

                            // Calculate discount amount
                            $discount_amount = 0;
                            if ($transaction && $transaction['final_discount_type'] == 'percentage') {
                                $discount_amount = $service_total_amount * ($transaction['final_discount_value'] / 100);
                            } else {
                                $discount_amount = $transaction['final_discount_value'] ?? 0;
                            }

                            // Calculate subtotal after discount
                            $sub_total = $service_total_amount - $discount_amount;

                            // Calculate inclusive tax total for display adjustments
                            $inclusiveTaxTotal = 0;
                            if (is_array($taxData)) {
                                foreach ($taxData as $t) {
                                    if (
                                        (isset($t['tax_type']) && $t['tax_type'] === 'inclusive') ||
                                        (isset($t['tax_scope']) && $t['tax_scope'] === 'inclusive')
                                    ) {
                                        if ($t['type'] === 'percent') {
                                            $inclusiveTaxTotal += $service_total_amount * ($t['value'] / 100);
                                        } elseif ($t['type'] === 'fixed') {
                                            $inclusiveTaxTotal += $t['value'];
                                        }
                                    }
                                }
                            }
                        @endphp
                        <div class="row gy-3 mt-4">
                            <div class="col-sm-12">
                                <h5 class="mb-3">{{ __('Price Details') }}</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Service Total -->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                            <span>{{ __('messages.total') }}</span>
                                            <span class="text-dark">{{ Currency::format($service_total_amount) }}</span>
                                        </div>

                                        @php
                                            // Calculate discount and subtotal - matching billing_detail logic
                                            $discount_total = $transaction['final_discount_value'] ?? 0;
                                            if ($transaction && $transaction['final_discount_type'] == 'percentage') {
                                                $discount_amount =
                                                    $service_total_amount *
                                                    ($transaction['final_discount_value'] / 100);
                                            } else {
                                                $discount_amount = $transaction['final_discount_value'] ?? 0;
                                            }
                                            $subtotal = $service_total_amount - $discount_amount;
                                        @endphp

                                        <!-- Discount Section (if applicable) -->
                                        @if ($transaction && $transaction['final_discount'] == 1)
                                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                                <span>{{ __('messages.discount') }}
                                                    @if ($transaction['final_discount_type'] === 'percentage')
                                                        ({{ $discount_total }}%)
                                                    @else
                                                        ({{ Currency::format($discount_total) }})
                                                    @endif
                                                </span>
                                                <span class="text-dark">-{{ Currency::format($discount_amount) }}</span>
                                            </div>
                                        @endif

                                        <!-- Sub Total -->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                            <span>{{ __('booking.sub_total') }}</span>
                                            <span class="text-dark">{{ Currency::format($subtotal) }}</span>
                                        </div>

                                        <!-- Individual Tax Breakdown -->
                                        @php
                                            $total_tax = 0; // Tax calculation disabled
                                        @endphp
                                        {{-- @foreach ($taxData as $taxPercentage)
                                            @php
                                                $taxTitle = $taxPercentage['title'];
                                                // Calculate tax amount based on type - using subtotal for tax calculation (matching billing_detail)
                                                if ($taxPercentage['type'] == 'fixed') {
                                                    $tax_amount = $taxPercentage['value'];
                                                } else {
                                                    $tax_amount = ($subtotal * $taxPercentage['value']) / 100;
                                                    if ($subtotal <= 0) {
                                                        $tax_amount = 0;
                                                    }
                                                }
                                                $total_tax += $tax_amount;
                                            @endphp
                                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                                <span>
                                                    @if ($taxPercentage['type'] == 'fixed')
                                                        {{ $taxTitle }}
                                                        ({{ Currency::format($taxPercentage['value']) }})
                                                    @else
                                                        {{ $taxTitle }} ({{ $taxPercentage['value'] }}%)
                                                    @endif
                                                </span>
                                                <span class="text-dark">{{ Currency::format($tax_amount) }}</span>
                                            </div>
                                        @endforeach --}}

                                        @php
                                            // Calculate final amounts - matching billing_detail logic
                                            $amount_due = $subtotal; // Tax calculation disabled
                                            $grand_total = $transaction['final_total_amount'] ?? $amount_due;

                                            // Calculate total paid from installments
                                            $total_paid_from_installments = 0;
                                            if (count($installments) > 0) {
                                                foreach ($installments as $installment) {
                                                    $total_paid_from_installments += $installment['amount'];
                                                }
                                            }

                                            // Determine payment status and amounts
                                            $is_paid = $info['appointmenttransaction']['payment_status'] == 1;
                                            $total_paid =
                                                $transaction['total_paid'] ?? ($info['advance_paid_amount'] ?? 0);
                                            $actual_total_paid =
                                                $total_paid_from_installments > 0
                                                    ? $total_paid_from_installments
                                                    : $total_paid;

                                            // Calculate paid and remaining amounts
                                            $paid_amount = $actual_total_paid;
                                            if ($is_paid) {
                                                $remaining_amount = 0;
                                            } else {
                                                $remaining_amount = max(0, $grand_total - $paid_amount);
                                            }

                                            $remaining_payable_amount =
                                                $amount_due - ($info['advance_paid_amount'] ?? 0);
                                        @endphp
                                        <!-- Payment Status Section -->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                            <span>{{ __('Paid Amount') }}</span>
                                            <span class="text-success">{{ Currency::format($paid_amount) }}</span>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                            <span>{{ __('Remaining Amount') }}</span>
                                            <span class="text-secondary">{{ Currency::format($remaining_amount) }}</span>
                                        </div>

                                        <!-- Grand Total Section -->
                                        <hr class="border-top border-gray">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                            <span class="text-dark"><strong>{{ __('Grand Total') }}</strong></span>
                                            <span class="text-dark">
                                                <strong>
                                                    @if ($transaction && $transaction['final_total_amount'])
                                                        {{ Currency::format($transaction['final_total_amount']) }}
                                                    @else
                                                        {{ Currency::format($grand_total) }}
                                                    @endif
                                                </strong>
                                            </span>
                                        </div>

                                        @php
                                            // Calculate refund if overpaid
                                            $showRefundNote = false;
                                            $refundAmount = 0;
                                            $final_total = $transaction['final_total_amount'] ?? $grand_total;

                                            if ($actual_total_paid > $final_total) {
                                                $showRefundNote = true;
                                                $refundAmount = $actual_total_paid - $final_total;
                                            }
                                        @endphp

                                        @if ($showRefundNote)
                                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3"
                                                style="padding: 8px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
                                                <span class="text-dark">{{ __('appointment.refundable_amount') }}</span>
                                                <span class="text-warning">{{ Currency::format($refundAmount) }}</span>
                                            </div>
                                        @endif

                                        @if ($info['appointmenttransaction']['advance_payment_status'] == 1)
                                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                <span>{{ __('service.advance_payment_amount') }}({{ $info['advance_payment_amount'] }}%)</span>
                                                <span>{{ Currency::format($info['advance_paid_amount']) ?? '--' }}</span>
                                            </div>
                                        @endif

                                        @if ($info['appointmenttransaction']['advance_payment_status'] == 1 && $info['status'] == 'checkout')
                                            <li class="d-flex align-items-center justify-content-between pt-2 pb-2 mb-2">
                                                <span>{{ __('service.remaining_amount') }}<span
                                                        class="text-capitalize badge bg-success p-2">{{ __('appointment.paid') }}</span></span>
                                                <span
                                                    class="text-dark">{{ Currency::format($remaining_payable_amount) }}</span>
                                            </li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{-- @endif --}}
                    @endunless

                     {{-- Download button section - positioned after all content --}}
                     <div class="row mt-4">
                         <div class="col-12">
                             <div class="d-flex justify-content-end">
                                 @if(optional(optional($info['patient_encounter'])['billingrecord'])['is_estimate'])
                                     {{-- Download button for estimates --}}
                                     <a class="btn btn-primary"
                                         href="{{ route('backend.billing-record.download.pdf', ['id' => $info['patient_encounter']['billingrecord']['id']]) }}" target="_blank">
                                         <i class="fa-solid fa-download me-2"></i>
                                        {{ __('appointment.estimate') }}
                                     </a>
                                 @else
                                     {{-- Download button for invoices --}}
                                     <a class="btn btn-primary"
                                         href="{{ route('backend.appointments.download_invoice', ['id' => $info['id']]) }}">
                                         <i class="fa-solid fa-download me-2"></i>
                                         {{ __('appointment.lbl_download') }}
                                     </a>
                                 @endif
                             </div>
                         </div>
                     </div>

                    <hr class="my-3" />
                @endforeach
            </div>

        </b-col>
    </b-row>

@endsection

@push('after-styles')
    <style>
        .detail-box {
            padding: 0.625rem 0.813rem;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
    <script src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ mix('modules/appointment/script.js') }}"></script>
    <script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>
@endpush
