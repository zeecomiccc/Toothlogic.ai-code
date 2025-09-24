@extends('backend.layouts.app')

@section('title')
{{ __($module_title) }}
@endsection
@section('content')


<style type="text/css" media="print">
    @page :footer {
        display: none !important;
    }

    @page :header {
        display: none !important;
    }

    @page {
        size: landscape;
    }

    /* @page { margin: 0; } */

    .pr-hide {
        display: none;
    }


    .order_table tr td div {
        white-space: normal;
    }


    * {
        -webkit-print-color-adjust: none !important;
        /* Chrome, Safari 6 – 15.3, Edge */
        color-adjust: none !important;
        /* Firefox 48 – 96 */
        print-color-adjust: none !important;
        /* Firefox 97+, Safari 15.4+ */
    }
</style>

<b-row>
    <b-col sm="12">
        <div id="bill">
            <div class="row pr-hide mb-4">
                <div class="d-flex justify-content-end align-items-center gap-2">
                    {{-- <a href="{{ route('backend.billing-record.download.pdf', ['id' => $billing['id']]) }}" target="_blank" class="btn btn-success">
                        <i class="fa-solid fa-file-pdf"></i>
                        {{ $billing['is_estimate'] ? __('appointment.estimate') : __('clinic.invoice_detail') }}
                    </a> --}}
                    <a class="btn btn-primary" onclick="invoicePrint(this)">
                        <i class="fa-solid fa-download"></i>
                        {{ __('messages.print') }}
                    </a>
                </div>
            </div>
            @php
            use Carbon\Carbon;
            @endphp

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <p class="mb-1">{{ $billing['is_estimate'] ? __('appointment.estimate') : __('messages.invoice_id') }}:<span class="text-secondary">
                                        #{{ $billing['encounter_id'] ?? '--' }}</span></p>
                                <p class="mb-1">
                                    {{ __('messages.payment_status') }}
                                    @if ($billing['is_estimate'])
                                        <span class="badge booking-status bg-info-subtle p-2">{{ __('appointment.estimate') }}</span>
                                    @elseif ($billing['payment_status'] == 1)
                                        <span class="badge booking-status bg-success-subtle p-2">{{ __('messages.paid') }}</span>
                                    @elseif(optional(optional(optional($billing->patientencounter)->appointmentdetail)->appointmenttransaction)->advance_payment_status)
                                        <span class="badge booking-status bg-success-subtle py-2 px-3">{{
                                            __('appointment.advance_paid') }}
                                        </span>
                                    @elseif(optional(optional(optional($billing->patientencounter)->appointmentdetail)->appointmenttransaction)
                                        == null)
                                        <span class="badge booking-status bg-danger-subtle py-2 px-3">{{
                                            __('appointment.failed') }}
                                        </span>
                                    @else
                                        <span class="badge booking-status bg-warning-subtle p-2">{{ __('messages.unpaid') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <p class="mt-1 mb-1">{{ __('messages.date') }}: <span
                                        class="font-weight-bold text-dark">
                                        {{ isset($billing['created_at'])
                                        ?
                                        Carbon::parse($billing['created_at'])->timezone($timezone)->format($dateformate)
                                        .
                                        ' At ' .
                                        Carbon::parse($billing['created_at'])->timezone($timezone)->format($timeformate)
                                        : '-- At --' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-3">
                <div class="col-md-12 col-lg-12">
                    <h5 class="mb-3">Clinic Info</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-3">
                                <div class="image-block">
                                    <img src="{{ $billing['clinic']['file_url'] ?? '--' }}"
                                        class="img-fluid avatar avatar-50 rounded-circle" alt="image">
                                </div>
                                <div class="content-detail">
                                    <h5 class="mb-2">{{ $billing['clinic']['name'] ?? '--' }}</h5>
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <i class="ph ph-envelope text-dark"></i>
                                            <u class="text-secondary">{{ $billing['clinic']['email'] ?? '--' }}</u>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <i class="ph ph-map-pin text-dark"></i>
                                            <span>{{ $billing['clinic']['address'] ?? '--' }}</span>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <i class="ph ph-phone-call text-dark"></i>
                                            <span>{{ $billing['clinic']['contact_number'] ?? '--' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <h5 class="mb-3">{{ __('messages.doctor_details') }}</h5>
                    <div class="card card-block card-stretch card-height mb-0">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center h-100 gap-3">
                                <div class="image-block">
                                    <img src="{{ $billing['doctor']['profile_image'] ?? '--' }}"
                                        class="img-fluid avatar avatar-50 rounded-circle" alt="image">
                                </div>
                                <div class="content-detail">
                                    <h5 class="mb-2">Dr. {{ $billing['doctor']['full_name'] ?? '--' }}</h5>
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <i class="ph ph-envelope text-dark"></i>
                                            <u class="text-secondary">{{ $billing['doctor']['email'] ?? '--' }}</u>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <i class="ph ph-phone-call text-dark"></i>
                                            <span>{{ $billing['doctor']['mobile'] ?? '--' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <h5 class="mb-3">{{ __('messages.patient_detail') }}</h5>
                    <div class="card card-block card-stretch card-height mb-0">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center h-100 gap-3">
                                <div class="image-block">
                                    <img src="{{ $billing['user']['profile_image'] ?? '--' }}"
                                        class="img-fluid avatar avatar-50 rounded-circle" alt="image">
                                </div>
                                <div class="content-detail">
                                    <h5 class="mb-2">
                                        {{ $billing['user']['first_name'] . '' . $billing['user']['last_name'] ?? '--' }}
                                    </h5>
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                                        @if ($billing['user']['gender'] !== null)
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ph ph-user text-dark"></i>
                                            <span class="">{{ $billing['user']['gender'] ?? '--' }}</span>
                                        </div>
                                        @endif
                                        @if ($billing['user']['date_of_birth'] !== null)
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ph ph-cake text-dark"></i>
                                            <span class="">{{ date($dateformate,
                                                strtotime($billing['user']['date_of_birth'])) ?? '--' }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <h5 class="mb-3">{{ __('messages.service') }}</h5>
                    <div class="card card-block card-stretch card-height mb-0">
                        <div class="card-body">
                            <div class="content-detail">
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                    <span>{{ __('messages.service_name') }}</span>
                                    <span class="text-dark">{{ $billing['clinicservice']['name'] ?? '--' }}</span>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                    <span>{{ __('messages.price') }}</span>
                                                                            <span class="text-dark">{{ Currency::format($billing['service_amount']) ?? '--' }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-3" />
            @if (!empty($billing['billingItem']) && $billing['billingItem']->isNotEmpty())
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mb-3">{{ __('messages.service') }}</h5>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered border-top order_table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('messages.sr_no') }}</th>
                                    <th>{{ __('messages.service_name') }}</th>
                                    @if (!empty($billing['patientencounter']->appointmentdetail->filling))
                                    <th>{{ __('product.quantity') }}</th>
                                    @endif
                                    <th>{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.discount') }}</th>
                                    {{-- <th>{{ __('service.inclusive_tax') }}</th> --}}
                                    <th>{{ __('messages.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $index = 1 @endphp
                                @foreach ($billing['billingItem'] as $item)
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>{{ $item->item_name ?? '--' }}</td>
                                    @if (!empty($billing['patientencounter']->appointmentdetail->filling))
                                    <td>{{ $billing['patientencounter']->appointmentdetail->filling ?? '--' }}
                                    </td>
                                    @endif
                                    <td class="text-end">
                                        {{ Currency::format($item->service_amount) . ' * ' . $item->quantity ?? '--' }}
                                    </td>
                                    @if ($item->discount_type === 'percentage')
                                    <td class="text-end">{{ $item->discount_value ?? '--' }}%</td>
                                    @else
                                    <td class="text-end">
                                        {{ Currency::format($item->discount_value) ?? '--' }}</td>
                                    @endif
                                    {{-- <td class="text-end">
                                        {{ Currency::format($item->inclusive_tax_amount) ?? '--' }}</td> --}}
                                    <td class="text-end">{{ Currency::format($item->total_amount) ?? '--' }}
                                    </td>
                                </tr>
                                @php $index++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-primary mb-0">{{ __('messages.no_record_found') }}</h4>
                </div>
            </div>
            @endif

            {{-- Installment Table Section --}}
            @if (isset($billing['installments']) && count($billing['installments']) > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="mb-3">{{ __('appointment.installments') }}</h5>
                </div>
                <div class="col-md-12">
                    @include('appointment::backend.patient_encounter.component.installment_list', [
                    'data' => $billing['installments'],
                    ])
                </div>
            </div>
            @endif

            <hr class="my-3" />
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mb-3">{{ __('clinic.notes') }}</h5>
                </div>

                <div class="col-md-12">
                    <textarea class="form-control h-auto" rows="3" placeholder="Enter Notes" name="notes" id="notes"
                        readonly disabled
                        style="min-height: max-content">{{ old('notes', $billing['notes'] ?? '') }}</textarea>
                </div>
            </div>

            @php
            $total_amount = 0; // Initialize total amount
            @endphp

            @foreach ($billing['billingItem'] as $item)
            @php
            $total_amount += $item->total_amount; // Sum up service amounts
            @endphp
            @endforeach
            <div class="row gy-3 mt-4">
                <div class="col-sm-12">
                    <h5 class="mb-3">{{ __('report.lbl_taxes') }}</h5>
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span><strong>{{ __('messages.total') }}</strong></span>
                                <span>{{ Currency::format($total_amount) }}</span>
                            </div>
                            @php
                            $discount_total = $billing['final_discount_value'] ?? 0;
                            if ($billing['final_discount_type'] == 'percentage') {
                                $discount_amount = $total_amount * ($billing['final_discount_value'] / 100);
                            } else {
                                $discount_amount = $billing['final_discount_value'];
                            }
                            $subtotal = $total_amount - $discount_amount;
                            $amount_due = $subtotal; // Tax calculation disabled
                            @endphp

                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span><strong>{{ __('messages.discount') }}
                                        @if ($billing['final_discount_type'] === 'percentage')
                                        ({{ $discount_total ?? '--' }}%)
                                        @else
                                        ({{ Currency::format($discount_total) ?? '--' }})
                                        @endif
                                    </strong></span>
                                <span class="text-dark">-{{ Currency::format($discount_amount) ?? '--' }}</span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span><strong>{{ __('booking.sub_total') }}</strong></span>
                                <span>{{ Currency::format($subtotal) }}</span>
                            </div>
                            {{-- @foreach ($taxData as $taxPercentage)
                            @php
                            $taxTitle = $taxPercentage['title'];
                            $percentagetax = ($subtotal * $taxPercentage['value']) / 100;

                            $taxAmount =
                            $taxPercentage['type'] == 'fixed'
                            ? Currency::format($taxPercentage['value'])
                            : $percentagetax;
                            @endphp
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span><strong>
                                        @if ($taxPercentage['type'] == 'fixed')
                                        {{ $taxTitle }} ({{ $taxAmount ?? '--' }})
                                        @else
                                        {{ $taxTitle }} ({{ $taxPercentage['value'] ?? '--' }}%)
                                        @endif
                                    </strong></span>
                                <span>
                                    @if ($taxPercentage['type'] == 'fixed')
                                    {{ $taxAmount ?? '--' }}
                                    @else
                                    {{ Currency::format($taxAmount) ?? '--' }}
                                    @endif
                                </span>
                            </div>
                            @endforeach --}}

                            @if ($billing['final_total_amount'] == null)
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span><strong>{{ __('messages.grand_total') }}</strong></span>
                                <span>
                                    {{
                                    Currency::format(optional(optional($billing->patientencounter)->appointmentdetail)->total_amount)
                                    ?? '--' }}
                                </span>
                            </div>
                            @else
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span><strong>{{ __('messages.grand_total') }}</strong></span>
                                <span>{{ Currency::format($billing['final_total_amount']) ?? '--' }}</span>
                            </div>
                            @endif
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span><strong>{{ __('Paid Amount') }}</strong></span>
                                <span>{{ Currency::format($billing['total_paid'] ?? 0) }}</span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                @php
                                $remainigAmount = $amount_due - $billing['total_paid'];
                                // Ensure remaining amount is not negative
                                $remainigAmount = max(0, $remainigAmount);
                                @endphp
                                <span><strong>{{ __('Remaining Amount') }}</strong></span>
                                <span>{{ Currency::format($remainigAmount) }}</span>
                            </div>

                            @php
                            $showRefundNote = false;
                            $refundAmount = 0;
                            $total_paid_so_far = $billing['total_paid'] ?? 0;
                            $grand_total = $billing['final_total_amount'] ?? $amount_due;

                            if ($total_paid_so_far > $grand_total) {
                            $showRefundNote = true;
                            $refundAmount = $total_paid_so_far - $grand_total;
                            }
                            @endphp

                            @if ($showRefundNote)
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3"
                                style="padding: 10px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; width: 100%;">
                                <span><strong>{{ __('appointment.refundable_amount') }}</strong></span>
                                <span>{{ Currency::format($refundAmount) }}</span>
                            </div>
                            @endif

                            @php
                            $advance_paid_amount = optional()->advance_paid_amount;
                            $remaining_payable_amount = $amount_due - $advance_paid_amount;
                            @endphp

                            @if(optional(optional(optional($billing->patientencounter)->appointmentdetail)->appointmenttransaction)->advance_payment_status == 1)
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span>{{ __('service.advance_payment_amount') }}({{ optional(optional($billing->patientencounter)->appointmentdetail)->advance_payment_amount }}%)</span>
                                <div>
                                    {{ Currency::format(optional(optional($billing->patientencounter)->appointmentdetail)->advance_paid_amount) ?? '--' }}
                                </div>
                            </div>
                            @if(optional(optional(optional($billing->patientencounter)->appointmentdetail)->appointmenttransaction)->advance_payment_status == 1 && optional(optional($billing->patientencounter)->appointmentdetail)->status == 'checkout')
                            <li class="d-flex align-items-center justify-content-between pb-2 mb-2">
                                <span>{{ __('service.remaining_amount') }}<span
                                        class="text-capitalize badge bg-success p-2">{{ __('appointment.paid') }}</span></span>
                                <span class="text-dark">{{ Currency::format($remaining_payable_amount) ?? '--' }}</span>
                            </li>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
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
<script>
    function invoicePrint() {
            window.print()
        }

        function updateStatusAjax(__this, url) {
            console.log(url);
            console.log($billing);
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    id: {{ $billing['id'] }},
                    status: __this.val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.status) {
                        window.successSnackbar(res.message)
                        setTimeout(() => {
                            location.reload()
                        }, 100);
                    }
                }
            });
        }
</script>
@endpush