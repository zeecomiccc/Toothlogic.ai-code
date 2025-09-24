@extends('frontend::layouts.master')

@section('title', __('frontend.appointment_detail'))

@push('after-styles')
    <style>
        /* Encounter Details Modal Styles */
        .modal-xl .modal-dialog {
            max-width: 95%;
            margin: 1.75rem auto;
        }

        @media (max-width: 768px) {
            .modal-xl .modal-dialog {
                max-width: 98%;
                margin: 0.5rem auto;
            }
        }

        .encounter-section .card {
            /* border: 1px solid #e9ecef; */
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .encounter-section .card-header {
            background-color: rgb(204, 242.8, 244.6);
            /* color: white; */
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .encounter-section .card-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .encounter-section .card-body {
            padding: 1rem;
        }

        .encounter-section .table {
            margin-bottom: 0;
        }

        .encounter-section .table th {
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .encounter-section .table td {
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        .encounter-section .table-striped>tbody>tr:nth-of-type(odd)>td {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .encounter-section .table-hover>tbody>tr:hover>td {
            background-color: rgba(0, 194, 203, 0.1);
        }

        .encounter-section .badge {
            font-size: 0.75rem;
        }

        .encounter-section .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .encounter-section .btn-outline-primary {
            /* border-color: white; */
            /* color: white; */
            background-color: transparent;
        }

        .encounter-section .btn-outline-primary:hover {
            /* background-color: white; */
            color: #00C2CB;
        }

        .clinical-details h5 {
            color: #00C2CB;
            font-weight: 600;
            border-bottom: 2px solid rgb(204, 242.8, 244.6);
            padding-bottom: 0.5rem;
        }

        .prescription-item {
            /* background-color: white; */
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .followup-note-item {
            /* background-color: white; */
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .avatar {
            width: 48px;
            height: 48px;
            object-fit: cover;
        }

        .avatar-48 {
            width: 48px;
            height: 48px;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .table-sm th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .encounter-section .card-header .btn {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }

        .encounter-section .card-header .btn i {
            font-size: 0.875rem;
        }

        /* Collapse functionality styles */
        .encounter-section .card-header[data-bs-toggle="collapse"] {
            transition: background-color 0.3s ease;
        }

        .encounter-section .card-header[data-bs-toggle="collapse"]:hover {
            background-color: #00a8b1;
        }

        .encounter-section .collapse-icon {
            transition: transform 0.3s ease;
            font-size: 0.875rem;
        }

        .encounter-section .card-header[aria-expanded="false"] .collapse-icon {
            transform: rotate(-90deg);
        }

        .encounter-section .card-header[aria-expanded="true"] .collapse-icon {
            transform: rotate(0deg);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .clinical-details h5 {
                font-size: 1.25rem;
            }

            .encounter-section .card-header {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .encounter-section .card-header .btn {
                align-self: flex-end;
            }
        }

        /* Animation for modal */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, -50px);
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        /* Custom scrollbar for modal body */
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Specific styles for encounter tables to override any conflicts */
        .encounter-patient-history-table {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 0 !important;
        }

        .encounter-patient-history-table thead {
            display: table-header-group !important;
        }

        .encounter-patient-history-table tbody {
            display: table-row-group !important;
        }

        .encounter-table-row {
            display: table-row !important;
        }

        .encounter-table-header,
        .encounter-table-cell {
            display: table-cell !important;
            padding: 0.5rem !important;
            border: 1px solid #dee2e6 !important;
            text-align: left !important;
            vertical-align: middle !important;
        }

        .encounter-table-header {
            font-weight: 600 !important;
            background-color: #f8f9fa !important;
        }

        .encounter-table-row:nth-child(even) .encounter-table-cell {
            background-color: rgba(0, 0, 0, 0.02) !important;
        }

        .encounter-table-row:hover .encounter-table-cell {
            background-color: rgba(0, 194, 203, 0.1) !important;
        }

        .encounter-patient-records-table {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 0 !important;
        }

        .encounter-patient-records-table thead {
            display: table-header-group !important;
        }

        .encounter-patient-records-table tbody {
            display: table-row-group !important;
        }

        .encounter-table-row {
            display: table-row !important;
        }

        .encounter-table-header,
        .encounter-table-cell {
            display: table-cell !important;
            padding: 0.5rem !important;
            border: 1px solid #dee2e6 !important;
            text-align: left !important;
            vertical-align: middle !important;
        }

        .encounter-table-header {
            font-weight: 600 !important;
            background-color: #f8f9fa !important;
        }

        .encounter-table-row:nth-child(even) .encounter-table-cell {
            background-color: rgba(0, 0, 0, 0.02) !important;
        }

        .encounter-table-row:hover .encounter-table-cell {
            background-color: rgba(0, 194, 203, 0.1) !important;
        }

        .encounter-orthodontic-table {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 0 !important;
        }

        .encounter-orthodontic-table thead {
            display: table-header-group !important;
        }

        .encounter-orthodontic-table tbody {
            display: table-row-group !important;
        }

        .encounter-stl-table {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 0 !important;
        }

        .encounter-stl-table thead {
            display: table-header-group !important;
        }

        .encounter-stl-table tbody {
            display: table-row-group !important;
        }
    </style>
@endpush

@section('content')
    @include('frontend::components.section.breadcrumb')
    <div class="list-page section-spacing px-0">
        <div class="page-title" id="page_title">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between gap-5 flex-wrap mb-5">
                    <h6 class="font-size-18 mb-0">{{ __('frontend.appointment_detail') }}
                    </h6>
                    @php
                        $id = $appointment ? $appointment->id : 0;
                        $status = $appointment ? $appointment->status : null;
                        $pay_status = $appointment ? optional($appointment->appointmenttransaction)->payment_status : 0;
                    @endphp
                    @if ($pay_status == 1 && $status == 'checkout')
                        <div class="d-flex justify-content-end align-items-center ">
                            <a class="btn btn-secondary" href="{{ route('download_invoice', ['id' => $appointment->id]) }}">
                                <i class="fa-solid fa-download"></i>
                                {{ __('frontend.lbl_download_invoice') }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-8">

                        @if (empty($appointment->serviceRating) &&
                                $appointment->status == 'checkout' &&
                                optional($appointment->appointmenttransaction)->payment_status)
                            <div class="d-flex align-items-center justify-content-between gap-5 flex-wrap mb-5 pb-3">
                                <h6 class="font-size-18 mb-0">{{ __('frontend.havent_rated') }}
                                </h6>
                                <button class="btn btn-secondary d-flex gap-2 align-items-center" data-bs-toggle="modal"
                                    data-service-id="{{ optional($appointment->clinicservice)->id }}"
                                    data-doctor-id="{{ optional($appointment->doctor)->id }}"
                                    data-bs-target="#review-service">
                                    <i class="ph-fill ph-star"></i>{{ __('frontend.rate_us') }}
                                </button>
                            </div>
                        @endif
                        <div class="section-bg payment-box rounded">
                            <div class="d-flex align-items-center justify-content-between gap-5 flex-wrap">
                                <h6 class="mb-0">{{ __('frontend.appointment_id') }}
                                </h6>
                                <h6 class="mb-0 text-primary">#{{ $appointment->id }}</h6>
                            </div>
                        </div>
                        <div class="mt-5 pt-3">
                            <h6 class="font-size-18">{{ __('frontend.booking_detail') }}
                            </h6>
                            <div class="section-bg payment-box rounded">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="font-size-14">{{ __('frontend.appointment_date_time') }}
                                        </span>
                                        <p class="mb-0"> <span
                                                class="mb-0 h6">{{ DateFormate($appointment->appointment_date) }}</span> at
                                            <span
                                                class="mb-0 h6 text-uppercase">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format(setting('time_formate') ?? 'h:i A') }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4 mt-md-0 mt-2">
                                        <span class="font-size-14">{{ __('frontend.service_name') }}
                                        </span>

                                        <a
                                            href="{{ route('service-details', ['id' => optional($appointment->clinicservice)->id]) }}">
                                            <h6 class="mb-0">{{ optional($appointment->clinicservice)->name ?? '-' }}
                                            </h6>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mt-md-0 mt-2">
                                        <span class="font-size-14">{{ __('frontend.doctor') }}</span>

                                        @if ($appointment->doctor === null)
                                            <h6 class="m-0">-</h6>
                                        @else
                                            <div class="d-flex gap-3 align-items-center">
                                                <img src="{{ optional($appointment->doctor)->profile_image ?? default_user_avatar() }}"
                                                    alt="avatar" class="avatar avatar-50 rounded-pill">
                                                <div class="text-start">
                                                    <h6 class="m-0">

                                                        {{ getDisplayName($appointment->doctor) }}

                                                    </h6>
                                                    @php
                                                        $doctorEmail = optional($appointment->doctor)->email;
                                                    @endphp

                                                    @if ($doctorEmail)
                                                        <a href="mailto:{{ $doctorEmail }}">{{ $doctorEmail }}</a>
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="clinic-desc-box mt-4 pt-4 border-top">
                                    <div class="row">
                                        <div class="col-md-4 mt-md-0 mt-2">
                                            <span class="font-size-14">{{ __('frontend.clinic_name') }}</span>
                                            <h6 class="m-0 line-count-1"> <img
                                                    src="{{ optional($appointment->cliniccenter)->file_url ?? 'default_file_url()' }}"
                                                    alt="avatar" class="avatar avatar-50 rounded-pill me-2">
                                                {{ $appointment->cliniccenter ? optional($appointment->cliniccenter)->name : '-' }}
                                            </h6>
                                        </div>
                                        <div class="col-md-4 mt-md-0 mt-2">
                                            <span class="font-size-14">{{ __('frontend.booking_status') }}</span>
                                            <h6
                                                class="mb-0 {{ $appointment->status === 'cancelled' ? 'text-danger' : 'text-success' }}">
                                                {{ $appointment->status === 'checkout' ? 'Complete' : \Illuminate\Support\Str::title(str_replace('_', ' ', $appointment->status)) }}
                                            </h6>

                                        </div>
                                        <div class="col-md-4 mt-md-0 mt-2">
                                            <span class="font-size-14">{{ __('frontend.payment_status') }}</span>
                                            <h6 class="mb-0">
                                                @if ($appointment->appointmenttransaction && $appointment->appointmenttransaction->payment_status)
                                                    @if ($appointment->status == 'cancelled')
                                                        @if ($appointment->advance_paid_amount > 0)
                                                            <span
                                                                class="text-warning">{{ __('frontend.advance_refunded') }}
                                                            </span>
                                                        @else
                                                            <span
                                                                class="text-warning">{{ __('frontend.payment_refunded') }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        @if ($appointment->appointmenttransaction->payment_method == 'cash')
                                                            <span class="text-danger">{{ __('frontend.pending') }}
                                                            </span>
                                                        @else
                                                            <span class="text-success">{{ __('frontend.paid') }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                @elseif($advancePaid)
                                                    @if ($appointment->status == 'cancelled')
                                                        <span class="text-warning">{{ __('frontend.advance_refunded') }}
                                                        </span>
                                                    @else
                                                        <span class="text-success">{{ __('frontend.advance_paid') }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">{{ __('frontend.pending') }}
                                                    </span>
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-top">
                                    <div class="row">
                                        <div class="col-md-4 mt-md-0 mt-2">
                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                                                <span class="font-size-14">{{ __('frontend.booked_for') }}
                                                </span>
                                            </div>

                                            @if ($appointment->user === null)
                                                <h6 class="m-0">-</h6>
                                            @elseif($appointment->otherPatient)
                                                <div class="d-flex gap-3 align-items-center">
                                                    <img src="{{ optional($appointment->otherPatient)->profile_image ?? default_user_avatar() }}"
                                                        alt="avatar" class="avatar avatar-50 rounded-pill">
                                                    <div class="text-start">
                                                        <h6 class="m-0">
                                                            {{ optional($appointment->otherPatient)->first_name . ' ' . optional($appointment->otherPatient)->last_name ?? '-' }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="d-flex gap-3 align-items-center">
                                                    <img src="{{ optional($appointment->user)->profile_image ?? default_user_avatar() }}"
                                                        alt="avatar" class="avatar avatar-50 rounded-pill">
                                                    <div class="text-start">
                                                        <h6 class="m-0">
                                                            {{ optional($appointment->user)->first_name . ' ' . optional($appointment->user)->last_name ?? '-' }}
                                                        </h6>
                                                        @php
                                                            $userEmail = optional($appointment->user)->email;
                                                        @endphp

                                                        @if ($userEmail)
                                                            <a href="mailto:{{ $userEmail }}">{{ $userEmail }}</a>
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="mt-5 pt-3">
                            <h6 class="font-size-18">{{ __('frontend.service_detail') }}
                            </h6>
                            <div class="section-bg payment-box rounded">

                                @if ($appointment->patientEncounter == null)
                                    <div
                                        class="d-flex align-items-md-center bg-body p-4 rounded flex-md-row flex-column gap-3 payment-box-info">
                                        <div class="detail-box">
                                            <img src="{{ optional($appointment->clinicservice)->file_url ?? default_file_url() }}"
                                                alt="avatar" class="avatar avatar-80 rounded-pill">
                                        </div>

                                        <div class="row">
                                            <div class="">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <a
                                                            href="{{ route('service-details', ['id' => optional($appointment->clinicservice)->id]) }}">
                                                            <b>{{ optional($appointment->clinicservice)->name ?? '-' }}</b></a>
                                                        {{ optional($appointment->clinicservice)->description ?? ' ' }}
                                                    </span>
                                                </div>
                                                @php
                                                    if (
                                                        optional($appointment->appointmenttransaction)
                                                            ->discount_type === 'percentage'
                                                    ) {
                                                        $payable_Amount =
                                                            $appointment->service_price -
                                                            $appointment->service_price *
                                                                (optional($appointment->appointmenttransaction)
                                                                    ->discount_value /
                                                                    100);
                                                    } else {
                                                        $payable_Amount =
                                                            $appointment->service_price -
                                                            optional($appointment->appointmenttransaction)
                                                                ->discount_value;
                                                    }
                                                    // $total_tax = 0; // Tax calculation disabled
                                                    $sub_total = $payable_Amount; // Tax calculation disabled
                                                    // $inclusive_tax_data = json_decode(
                                                    //     $appointment->appointmenttransaction->inclusive_tax,
                                                    //     true,
                                                    // ); // decode tax details - Tax calculation disabled
                                                @endphp
                                                @if (optional($appointment->appointmenttransaction)->discount_value > 0)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <h6 class="mb-0">
                                                            {{ Currency::format($sub_total) }}

                                                            <span class="text-primary">

                                                                @if (optional($appointment->appointmenttransaction)->discount_type === 'percentage')
                                                                    (<span>{{ optional($appointment->appointmenttransaction)->discount_value ?? '--' }}%
                                                                        </<span> off)
                                                                    @else
                                                                        (<span>{{ Currency::format(optional($appointment->appointmenttransaction)->discount_value) ?? '--' }}
                                                                            </<span> off)
                                                                @endif

                                                            </span>

                                                        </h6>
                                                        <del>{{ Currency::format($appointment->service_price) }}</del>

                                                    </div>
                                                    {{-- @if ($appointment->appointmenttransaction->inclusive_tax_price != null && $appointment->patientEncounter == null)
                                                    @php
                                                        $total_tax = 0;
                                                        $sub_total = $payable_Amount + $appointment->appointmenttransaction->inclusive_tax_price;
                                                        $inclusive_tax_data = json_decode($appointment->appointmenttransaction->inclusive_tax, true); // decode tax details
                                                    @endphp
                                                    <li class="d-flex align-items-center justify-content-between pb-2 mb-2 mt-2 border-bottom">
                                                        <span>{{ __('appointment.service_price') }}</span>
                                                        <span class="text-primary">{{ Currency::format($payable_Amount) }}</span>
                                                    </li>
                                                    @if (!empty($inclusive_tax_data))
                                                        @foreach ($inclusive_tax_data as $t)
                                                            @if ($t['type'] == 'percent')
                                                                @php
                                                                    $tax_amount = $payable_Amount * $t['value'] / 100 ; // for inclusive, this is reverse calculated
                                                                    $total_tax += $tax_amount;
                                                                @endphp
                                                                <li class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                                                    <span>{{ $t['title'] }} ({{ $t['value'] }}%)</span>
                                                                    <span class="text-primary">{{ Currency::format($tax_amount) }}</span>
                                                                </li>
                                                            @elseif($t['type'] == 'fixed')
                                                                @php
                                                                    $tax_amount = $t['value'];
                                                                    $total_tax += $tax_amount;
                                                                @endphp
                                                                <li class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                                                    <span>{{ $t['title'] }}</span>
                                                                    <span class="text-primary">{{ Currency::format($tax_amount) }}</span>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                        <li class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                                            <span>{{ __('messages.sub_total') }}</span>
                                                            <span class="text-primary">{{ Currency::format($sub_total) }}</span>
                                                        </li>
                                                    @endif
                                            @endif   --}}
                                                @else
                                                    <h6 class="mb-0">
                                                        {{ Currency::format($appointment->service_amount) }}</h6>
                                                @endif
                                                {{-- @if ($appointment->appointmenttransaction->inclusive_tax_price != null && $appointment->patientEncounter == null)
                                                    <small
                                                        class="text-secondary"><i>{{ __('messages.lbl_with_inclusive_tax') }}</i></small>
                                                @endif --}}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (
                                    $appointment->patientEncounter !== null &&
                                        optional(optional($appointment->patientEncounter)->billingrecord)->billingItem != null)
                                    @foreach (optional(optional($appointment->patientEncounter)->billingrecord)->billingItem as $billingItem)
                                        <div
                                            class="d-flex align-items-md-center bg-body p-4 rounded flex-md-row flex-column gap-3 payment-box-info">
                                            <div class="detail-box rounded">
                                                <img src="{{ optional($billingItem->clinicservice)->file_url ?? default_file_url() }}"
                                                    alt="avatar" class="avatar avatar-80 rounded-pill">
                                            </div>

                                            <div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span><b>{{ optional($billingItem->clinicservice)->name }}</b>
                                                        {{ optional($billingItem->clinicservice)->description ?? ' ' }}</span>

                                                </div>
                                                @php
                                                    if ($billingItem->discount_type === 'percentage') {
                                                        $payable_Amount =
                                                            $billingItem->service_amount -
                                                            $billingItem->service_amount *
                                                                ($billingItem->discount_value / 100);
                                                    } else {
                                                        $payable_Amount =
                                                            $billingItem->service_amount - $billingItem->discount_value;
                                                    }
                                                @endphp
                                                @if ($billingItem->discount_value > 0)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <h6 class="mb-0">
                                                            <span
                                                                class="fw-normal">{{ Currency::format($billingItem->total_amount) }}
                                                                *
                                                                {{ $billingItem->quantity }} = </span>
                                                            {{ Currency::format($billingItem->total_amount * $billingItem->quantity) }}


                                                            <span>

                                                                @if ($billingItem->discount_type === 'percentage')
                                                                    (<span>{{ $billingItem->discount_value ?? '--' }}%
                                                                        </<span> off)
                                                                    @else
                                                                        (<span>{{ Currency::format($billingItem->discount_value) ?? '--' }}
                                                                            </<span> off)
                                                                @endif
                                                                {{-- @if ($billingItem->inclusive_tax_amount > 0)
                                                                    <small
                                                                        class="text-secondary"><i>{{ __('messages.lbl_with_inclusive_tax') }}</i></small>
                                                                @endif --}}

                                                            </span>


                                                        </h6>


                                                        <del>{{ Currency::format($billingItem->service_amount * $billingItem->quantity) }}</del>
                                                    </div>
                                                @else
                                                    <h6 class="mb-0"> <span
                                                            class="fw-normal">{{ Currency::format($billingItem->service_amount) }}
                                                            *
                                                            {{ $billingItem->quantity }} = </span>
                                                        {{ Currency::format($billingItem->service_amount * $billingItem->quantity) }}
                                                        {{-- @if ($billingItem->inclusive_tax_amount > 0)
                                                            <small
                                                                class="text-secondary"><i>{{ __('messages.lbl_with_inclusive_tax') }}</i></small>
                                                    </h6>
                                                @endif --}}
                                    @endif

                                    {{-- @if (!empty($billingItem->clinicservice->inclusive_tax_price))
                                        @php
                                            $quantity = $billingItem->quantity;
                                            $inclusive_tax_price_per_unit = $billingItem->clinicservice->inclusive_tax_price;
                                            $inclusive_tax_price = $inclusive_tax_price_per_unit * $quantity;
                                            $inclusive_tax_data = json_decode($billingItem->clinicservice->inclusive_tax, true);

                                            $service_total = $payable_Amount * $quantity;
                                            $item_subtotal = ($payable_Amount + $inclusive_tax_price_per_unit) * $quantity;
                                            $total_item_tax = 0;
                                        @endphp

                                        <ul class="ps-0 w-100 mt-1">
                                            <li class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                                                <span>{{ __('appointment.service_price') }}</span>
                                                <span class="text-primary">{{ Currency::format($service_total) }}</span>
                                            </li>

                                            @if (!empty($inclusive_tax_data))
                                                @foreach ($inclusive_tax_data as $t)
                                                    @if ($t['type'] == 'percent')
                                                        @php
                                                            $tax_per_unit = $payable_Amount * $t['value'] / 100 ;
                                                            $tax_total = $tax_per_unit * $quantity;
                                                            $total_item_tax += $tax_total;
                                                        @endphp
                                                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                                                            <span>{{ $t['title'] }} ({{ $t['value'] }}% of {{ Currency::format($payable_Amount) }} × {{ $quantity }})</span>
                                                            <span class="text-primary">{{ Currency::format($tax_total) }}</span>
                                                        </li>
                                                    @elseif ($t['type'] == 'fixed')
                                                        @php
                                                            $tax_total = $t['value'] * $quantity;
                                                            $total_item_tax += $tax_total;
                                                        @endphp
                                                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                                                            <span>{{ $t['title'] }} ({{ Currency::format($t['value']) }} × {{ $quantity }})</span>
                                                            <span class="text-primary">{{ Currency::format($tax_total) }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                <li class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                                                    <span>{{ __('appointment.sub_total') }}</span>
                                                    <span class="text-primary">{{ Currency::format($item_subtotal) }}</span>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif --}}
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                <!-- Installment Section -->
                @if ($appointment->patientEncounter !== null && optional($appointment->patientEncounter)->billingrecord !== null)
                    @php
                        $installments = Modules\Appointment\Models\Installment::where(
                            'billing_record_id',
                            optional($appointment->patientEncounter)->billingrecord->id,
                        )->get();
                    @endphp
                    @if ($installments->isNotEmpty())
                        <div class="mt-5 pt-3">
                            <div
                                class="d-flex align-items-center justify-content-between gap-3 section-bg p-3 rounded mb-3">
                                <h6 class="font-size-18 mb-0">
                                    <i class="ph ph-credit-card me-2 text-primary"></i>
                                    {{ __('frontend.installments') }}
                                </h6>
                                <span class="badge bg-primary-subtle text-primary rounded-pill">
                                    {{ $installments->count() }}
                                    {{ __('frontend.payment') }}{{ $installments->count() > 1 ? 's' : '' }}
                                </span>
                            </div>
                            <div class="section-bg payment-box rounded p-4">
                                @php
                                    $timezone =
                                        App\Models\Setting::where('name', 'default_time_zone')->value('val') ?? 'UTC';
                                    $setting = App\Models\Setting::where('name', 'date_formate')->first();
                                    $dateformate = $setting ? $setting->val : 'Y-m-d';
                                    $setting = App\Models\Setting::where('name', 'time_formate')->first();
                                    $timeformate = $setting ? $setting->val : 'h:i A';
                                    $totalPaid = $installments->sum('amount');
                                @endphp

                                @foreach ($installments as $index => $installment)
                                    <div class="row mb-4 {{ $index > 0 ? 'border-top pt-4' : '' }}">
                                        <div class="col-md-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 48px; height: 48px; min-width: 48px;">
                                                    <span class="text-primary fw-bold">{{ $index + 1 }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">Payment {{ $index + 1 }}</div>
                                                    <div class="text-muted small">Installment</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success-subtle rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 48px; height: 48px; min-width: 48px;">
                                                    {!! getCurrencySymbol('text-success', '18px') !!}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-success">
                                                        {{ Currency::format($installment->amount) }}</div>
                                                    <div class="text-muted small">Amount Paid</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 48px; height: 48px; min-width: 48px;">
                                                    <i
                                                        class="ph ph-{{ strtolower($installment->payment_mode) == 'cash' ? 'money' : 'credit-card' }} text-info"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">
                                                        {{ ucfirst($installment->payment_mode) }}</div>
                                                    <div class="text-muted small">Payment Method</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 48px; height: 48px; min-width: 48px;">
                                                    <i class="ph ph-calendar text-warning"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">
                                                        {{ isset($installment->date) ? \Carbon\Carbon::parse($installment->date)->timezone($timezone)->format($dateformate) : '--' }}
                                                    </div>
                                                    <div class="text-muted small">Payment Date</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 48px; height: 48px; min-width: 48px;">
                                                    <i class="ph ph-download text-secondary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">
                                                        <a href="{{ route('download.installment.pdf', $installment->id) }}"
                                                            class="text-decoration-none text-primary">
                                                            {{ __('appointment.invoice_detail') }}
                                                        </a>
                                                    </div>
                                                    <div class="text-muted small">Download Installment Invoice</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="border-top pt-4 mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fw-semibold text-dark">{{ __('frontend.total_paid') }}</div>
                                            <div class="text-muted small">{{ $installments->count() }}
                                                payment{{ $installments->count() > 1 ? 's' : '' }} completed</div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="fw-bold text-success h5 mb-0">{{ Currency::format($totalPaid) }}
                                            </div>
                                            <div class="text-muted small">Total Amount</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if ($appointment->status == 'checkout' || $appointment->status == 'check_in')
                    <div class="mt-5 pt-3">
                        <div class="d-flex align-items-center justify-content-between gap-3 section-bg p-3 rounded">
                            <h6 class="font-size-18 mb-0">{{ __('frontend.encounter_details') }}
                            </h6>
                            <a data-bs-toggle="modal" data-bs-target="#encounter-details-view"
                                class="font-size-14 fw-semibold text-secondary">View</a>
                        </div>
                    </div>
                @endif
                <!-- review -->
                <!-- rate us modal -->

            </div>

            @php
                $service_total_amount = 0; // Initialize outside the loop
                // $total_tax = 0; // Tax calculation disabled
            @endphp
            @if ($appointment->patientEncounter !== null)
                @foreach (optional(optional($appointment->patientEncounter)->billingrecord)->billingItem as $item)
                    @php
                        $quantity = $item->quantity ?? 1;

                        $service_total_amount += $item->total_amount; // Sum up service amounts
                        if ($quantity > 1) {
                            // if (isset($item->inclusive_tax_amount) && $item->inclusive_tax_amount > 0) {
                            //     $service_total_amount += $item->inclusive_tax_amount * $quantity;
                            // } // Tax calculation disabled

                            if (!empty($item->discount_type)) {
                                if ($item->discount_type === 'fixed') {
                                    $service_total_amount -= $item->discount_value * $quantity;
                                } elseif ($item->discount_type === 'percentage') {
                                    $service_total_amount -= ($item->total_amount * $item->discount_value) / 100;
                                }
                            }
                        }
                    @endphp
                @endforeach
            @endif

            <?php
            $transaction = $appointment->appointmenttransaction ? $appointment->appointmenttransaction : null;
            // if ($transaction != null) {
            //     $tax = json_decode(optional($transaction)->tax_percentage, true);
            // } // Tax calculation disabled
            $total_amount = 0;
            $discount_amount = 0;
            if ($appointment->patientEncounter !== null) {
                $transaction = optional($appointment->patientEncounter)->billingrecord ? optional($appointment->patientEncounter)->billingrecord : null;
                if ($transaction['final_discount_type'] == 'percentage') {
                    $discount_amount = $service_total_amount * ($transaction['final_discount_value'] / 100);
                } else {
                    $discount_amount = $transaction['final_discount_value'];
                }
                if ($transaction != null) {
                    foreach (optional(optional($appointment->patientEncounter)->billingrecord)->billingItem as $billingItem) {
                        $total_amount += $billingItem->total_amount;
                    }

                    // $tax = json_decode(optional($transaction)->tax_data, true); // Tax calculation disabled
                }
                $sub_total = $service_total_amount - $discount_amount;
            } else {
                $sub_total = $appointment->service_amount;
            }

            if ($appointment->appointmenttransaction == null) {
                // $tax = Modules\Tax\Models\Tax::active()->whereNull('module_type')->orWhere('module_type', 'services')->where('status', 1)->get(); // Tax calculation disabled
                $tax = []; // Tax calculation disabled
            }

            ?>

            <div class="col-lg-4 mt-lg-0 mt-5">
                <h6 class="pb-1">{{ __('frontend.payment_details') }}</h6>
                @if ($appointment->status == 'cancelled')
                    @php
                        $refundAmount = $appointment->getRefundAmount(); // Assumes this returns positive or negative amount
                    @endphp
                    <div class="payment-box section-bg rounded">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-muted small">{{ formatDate($appointment->updated_at) }}</div>
                                <span
                                    class="badge {{ $refundAmount >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                    {{ $refundAmount >= 0 ? __('frontend.refund_completed') : __('frontend.wallet_deducted') }}
                                </span>
                            </div>

                            <h6 class="fw-bold mb-4">
                                {{ $refundAmount >= 0 ? __('messages.refund_of') . ' ' . \Currency::format($refundAmount) : __('messages.wallet_deduction') . ' ' . \Currency::format(abs($refundAmount)) }}
                            </h6>

                            <div class="row mb-2">
                                <div class="col-6 text-muted">{{ __('earning.lbl_payment_method') }}</div>
                                <div class="col-6 text-end text-primary">{{ __('messages.wallet') }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6 text-muted">{{ __('clinic.price') }}</div>
                                <div class="col-6 text-end">{{ \Currency::format($appointment->total_amount) }}</div>
                            </div>
                            @if ($appointment->advance_paid_amount != 0)
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">{{ __('messages.advanced_payment') }} </div>
                                    <div class="col-6 text-end">{{ \Currency::format($appointment->advance_paid_amount) }}
                                    </div>
                                </div>
                            @endif

                            @if ($appointment->cancellation_charge_amount != 0)
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">
                                        {{ __('messages.cancellation_fee') }}
                                        @if ($appointment->cancellation_type === 'percentage')
                                            ({{ $appointment->cancellation_charge }}%)
                                        @else
                                            ({{ Currency::format($appointment->cancellation_charge) }})
                                        @endif
                                    </div>
                                    <div class="col-6 text-end">
                                        {{ Currency::format($appointment->cancellation_charge_amount) }}
                                    </div>
                                </div>
                            @endif
                            <hr class="my-3">

                            <div class="row">
                                <div class="d-flex justify-content-between align-items-center px-4 py-2 rounded"
                                    style="background-color: {{ $refundAmount >= 0 ? '#e6f4ea' : '#fdecea' }};">

                                    <span class="fw-semibold {{ $refundAmount >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $refundAmount >= 0 ? __('messages.refund_amount') : __('frontend.wallet_deducted') }}
                                    </span>

                                    <span class="fw-semibold text-dark">
                                        {{ \Currency::format(abs($refundAmount)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <hr>
                @php
                    // Calculate total paid from installments
                    $total_paid_from_installments = 0;
                    $installments = collect();

                    if ($appointment->patientEncounter && $appointment->patientEncounter->billingrecord) {
                        $installments = Modules\Appointment\Models\Installment::where(
                            'billing_record_id',
                            $appointment->patientEncounter->billingrecord->id,
                        )->get();

                        if (count($installments) > 0) {
                            foreach ($installments as $installment) {
                                $total_paid_from_installments += $installment['amount'];
                            }
                        }
                    }
                @endphp

                <div class="payment-box section-bg rounded">
                    @if ($transaction !== null)
                        @if ($appointment->patientEncounter !== null)
                            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between mb-2 pb-1">
                                <p class="mb-0 font-size-14">{{ __('messages.total') }} </p>
                                <h6 class="mb-0 font-size-14">{{ Currency::format($service_total_amount) ?? '--' }}</h6>
                            </div>
                            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between mb-2 pb-1">
                                <p class="mb-0 font-size-14">{{ __('messages.discount') }}
                                    ( <span class="text-success">
                                        @if ($transaction->final_discount_type === 'percentage')
                                            {{ $transaction->final_discount_value ?? '--' }}%
                                        @else
                                            {{ Currency::format($transaction->final_discount_value) ?? '--' }}
                                        @endif
                                    </span>)
                                    </span>
                                </p>
                                <h6 class="mb-0 font-size-14 text-success">-
                                    {{ Currency::format($discount_amount) ?? '--' }}</h6>
                            </div>
                            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between mb-2 pb-1">
                                <p class="mb-0 font-size-14">{{ __('frontend.sub_total') }}</p>
                                <h6 class="mb-0 font-size-14">{{ Currency::format($sub_total) ?? '--' }}</h6>
                            </div>
                        @endif

                        @if ($appointment->patientEncounter == null)
                            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between mb-2 pb-1">
                                <p class="mb-0 font-size-14">{{ __('messages.total') }}
                                    {{-- @if ($appointment->appointmenttransaction && $appointment->appointmenttransaction->inclusive_tax_price > 0)
                                        <small
                                            class="text-secondary"><i>{{ __('messages.lbl_with_inclusive_tax') }}</i></small>
                                    @endif --}}
                                </p>
                                <h6 class="mb-0 font-size-14">{{ Currency::format($sub_total) ?? '--' }}</h6>
                            </div>
                        @endif
                    @endif

                    {{-- @foreach ($tax as $t)
                        @if ($t['type'] == 'percent')
                            <li class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                <span>{{ $t['title'] }} ({{ $t['value'] }}%)</span>
                                <?php
                                // $tax_amount = ($sub_total * $t['value']) / 100;
                                // if ($sub_total > 0) {
                                //     $tax_amount = ($sub_total * $t['value']) / 100;
                                // }
                                // $total_tax += $tax_amount;
                                ?>
                                <span class="text-primary">{{ Currency::format($tax_amount) }}</span>
                            </li>
                        @elseif($t['type'] == 'fixed')
                            @php $total_tax += $t['value']; @endphp
                            <li class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                    <span>{{ $t['title'] }}</span>
                                    <span classtext-primary">{{ Currency::format($t['value']) }}</span>
                                </li>
                            @endif
                    @endforeach--}}

                    {{-- <div class="d-flex align-items-center gap-3 mt-3 flex-wrap justify-content-between mb-2 pb-1">
                        <p class="mb-0 font-size-14">{{ __('appointment.tax') }}</p>
                        <div class="d-flex align-items-center gap-2">

                            <i class="ph ph-info align-middle" data-bs-toggle="modal" data-bs-target="#taxDetailsModal"
                                style="cursor: pointer;"></i>

                            <h6 class="mb-0 font-size-14 text-secondary">{{ Currency::format($total_tax) ?? '--' }}</h6>
                        </div>
                    </div> --}}

                    {{-- <div class="modal" id="taxDetailsModal">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content section-bg position-relative rounded">
                                <div class="modal-body modal-body-inner">
                                    <div class="close-modal-btn" data-bs-dismiss="modal">
                                        <i class="ph ph-x align-middle"></i>
                                    </div>
                                    <h5 class="mb-3" id="taxDetailsModalLabel">{{ __('frontend.tax_detail') }}</h5>
                                    <ul id="taxBreakdownList" class="p-0 mb-3 list-inline">
                                        @foreach ($tax_percentage as $tax)
                                            @php
                                                if ($tax['type'] == 'percent' && $sub_total > 0) {
                                                    $tax_amount = ($sub_total * $tax['value']) / 100;
                                                } else {
                                                    $tax_amount = $tax['value'];
                                                }
                                            @endphp
                                            <li class=" d-flex justify-content-between gap-3">
                                                <strong>
                                                    {{ $tax['title'] }}
                                                    @if ($tax['type'] == 'percent')
                                                        ({{ $tax['value'] }}%)
                                                    @endif

                                                </strong>
                                                <span id="{{ strtolower(str_replace(' ', '', $tax['title'])) }}">
                                                    {{ Currency::format($tax_amount) ?? '--' }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p class="mb-0 mt-3 d-flex flex-wrap justify-content-between gap-3">
                                        <strong>{{ __('frontend.total_tax') }}
                                        </strong> <span id="totalTaxAmount"
                                            class="fw-bold text-secondary">{{ Currency::format($total_tax) ?? '--' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    @php
                        // Calculate final amounts - matching backend logic
                        $grand_total = $appointment->patientEncounter
                            ? $service_total_amount - $discount_amount // Tax calculation disabled
                            : $appointment->total_amount;

                        // Determine payment status and amounts
                        $is_paid = optional($appointment->appointmenttransaction)->payment_status == 1;
                        $total_paid = $transaction['total_paid'] ?? ($appointment->advance_paid_amount ?? 0);
                        $actual_total_paid =
                            $total_paid_from_installments > 0 ? $total_paid_from_installments : $total_paid;

                        // Calculate paid and remaining amounts
                        $paid_amount = $actual_total_paid;
                        if ($is_paid) {
                            $remaining_amount = 0;
                        } else {
                            $remaining_amount = max(0, $grand_total - $paid_amount);
                        }

                        // Calculate refund if overpaid
                        $showRefundNote = false;
                        $refundAmount = 0;
                        $final_total = $transaction['final_total_amount'] ?? $grand_total;

                        if ($actual_total_paid > $final_total) {
                            $showRefundNote = true;
                            $refundAmount = $actual_total_paid - $final_total;
                        }
                    @endphp

                    <div class="mt-3 pt-4 mb-1 border-top">
                        <div class="d-flex align-items-center mb-3 gap-3 flex-wrap justify-content-between">
                            <h6 class="mb-0">{{ __('appointment.total') }}</h6>
                            <h6 class="mb-0 text-primary">{{ Currency::format($grand_total) }}</h6>
                        </div>

                        <!-- Paid Amount -->
                        <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between mb-2">
                            <h6 class="mb-0">{{ __('appointment.paid_amount') }}</h6>
                            <h6 class="mb-0 text-success">{{ Currency::format($paid_amount) }}</h6>
                        </div>

                        <!-- Remaining Amount -->
                        <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between mb-2">
                            <h6 class="mb-0">{{ __('frontend.remaining_amount') }}
                            </h6>
                            <h6 class="mb-0 text-secondary">{{ Currency::format($remaining_amount) }}</h6>
                        </div>

                        <!-- Refundable Amount -->
                        @if ($showRefundNote)
                            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between mb-2 p-2 rounded"
                                style="background-color: #fff3cd; border: 1px solid #ffeaa7;">
                                <h6 class="mb-0 text-warning">{{ __('appointment.refundable_amount') }}</h6>
                                <h6 class="mb-0 text-warning">{{ Currency::format($refundAmount) }}</h6>
                            </div>
                        @endif

                        @if ($advancePaid)
                            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between">
                                <h6 class="mb-3">{{ __('frontend.advance_paid_amount') }}
                                    ({{ $appointment->advance_payment_amount }}%)</h6>
                                <h6 class="mb-3 text-success">{{ Currency::format($appointment->advance_paid_amount) }}
                                </h6>
                            </div>

                            @if ($appointment->status != 'cancelled')
                                <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between">
                                    <h6 class="mb-0">{{ __('frontend.remaining_amount') }}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0 text-secondary">
                                            {{ Currency::format($grand_total - $appointment->advance_paid_amount) }}</h6>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    @if (
                        $advancePaid &&
                            $appointment->status == 'check_in' &&
                            optional($appointment->appointmenttransaction)->payment_status == 0 &&
                            optional($appointment->patientEncounter)->status == 1)
                        <a href="#" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#paymentModal">{{ __('frontend.pay_now') }}
                            {{ Currency::format($grand_total - $appointment->advance_paid_amount) }}</a>
                    @elseif(
                        $appointment->status == 'check_in' &&
                            optional($appointment->appointmenttransaction)->payment_status == 0 &&
                            optional($appointment->patientEncounter)->status == 1)
                        <a href="#" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#paymentModal">{{ __('frontend.pay_now') }}
                            {{ Currency::format($grand_total) }}</a>
                    @endif
                </div>
            </div>

            <x-frontend::section.review />
            @if ($review)
                <div class="row">
                    <div class="col-lg-8 mt-lg-0 mt-5">
                        <div class="mt-5 pt-3">
                            <div class="d-flex align-items-center justify-content-between gap-5 flex-wrap mb-2">
                                <div>
                                    <h6 class="font-size-18">{{ __('frontend.your_review') }}
                                    </h6>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap rate-us-btn">
                                    <button class="btn p-0" data-bs-toggle="modal"
                                        data-service-id="{{ optional($appointment->clinicservice)->id }}"
                                        data-doctor-id="{{ optional($appointment->doctor)->id }}"
                                        data-review-id="{{ $review->id }}" {{-- data-rating="{{ $review->experience_rating }}" --}}
                                        data-review-msg="{{ $review->review_msg }}" data-name="{{ $review->name }}"
                                        data-email="{{ $review->email }}" data-phone="{{ $review->phone }}"
                                        data-age="{{ $review->age }}" data-treatments="{{ $review->treatments }}"
                                        data-clinic-location="{{ $review->clinic_location }}"
                                        data-referral-source="{{ $review->referral_source }}"
                                        data-referral-source-other="{{ $review->referral_source_other }}"
                                        data-experience-rating="{{ $review->experience_rating }}"
                                        data-dentist-explanation="{{ $review->dentist_explanation }}"
                                        data-pricing-satisfaction="{{ $review->pricing_satisfaction }}"
                                        data-staff-courtesy="{{ $review->staff_courtesy }}"
                                        data-treatment-satisfaction="{{ $review->treatment_satisfaction }}"
                                        data-bs-target="#review-service">
                                        <i class="ph ph-pencil-simple-line"></i>
                                    </button>
                                    <!-- rate us modal -->
                                    <button class="delete-rating-btn btn p-0" data-review-id="{{ $review->id }}">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <ul class="list-inline m-0 p-0">
                                <li class="review-card">
                                    <div class="review-detail section-bg rounded">
                                        <div
                                            class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="d-flex align-items-center gap-2 rounded-pill bg-primary-subtle badge">
                                                    <i class="ph-fill ph-star text-warning"></i>
                                                    <span
                                                        class="font-size-14 fw-bold">{{ $review->experience_rating }}</span>
                                                </div>
                                                <h6 class="m-0">{{ $review->title }}</h6>
                                            </div>
                                            <span
                                                class="bg-secondary-subtle badge rounded-pill">{{ optional($review->clinic_service)->name }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between flex-column flex-wrap gap-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ optional($review->user)->profile_image }}" alt="user"
                                                    class="img-fluid user-img rounded-circle">
                                                <div>
                                                    <h6 class="line-count-1 font-size-14">By
                                                        {{ optional($review->user)->gender == 'female' ? 'Miss.' : 'Mr.' }}
                                                        {{ optional($review->user)->first_name . ' ' . optional($review->user)->last_name }}
                                                    </h6>
                                                    <small
                                                        class="mb-0 font-size-14">{{ $review->updated_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <p class="mb-0 mt-2 font-size-14">{{ $review->review_msg }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <x-frontend::section.review />
        </div>
    </div>
    </div>
    </div>

    <!-- Encounter Details Modal -->
    <div class="modal fade" id="encounter-details-view">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content section-bg position-relative rounded">
                <div class="close-modal-btn" data-bs-dismiss="modal">
                    <i class="ph ph-x align-middle"></i>
                </div>
                <div class="modal-body modal-body-inner">
                    <div class="row">
                        <!-- Right Content - Clinical Details -->

                        <div class="clinical-details">
                            <h5 class="mb-4">{{ __('frontend.clinical_details') }}</h5>

                            <!-- 1. Patient History & Examination -->
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.history_and_exam_form') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @if ($patientHistoryRecords && $patientHistoryRecords->isNotEmpty())
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-striped table-hover encounter-patient-history-table"
                                                    style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.name') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.date') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.treatment_details') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.radiograph_type') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.radiograph_findings') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.is_complete') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($patientHistoryRecords as $record)
                                                            <tr class="encounter-table-row"
                                                                style="display: table-row !important;">
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ optional($record->demographic)->full_name ?? (optional($record->user)->full_name ?? '--') }}
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ formatDate($record->created_at) }}</td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ Illuminate\Support\Str::limit(optional($record->medicalHistory)->treatment_details ?? '', 30, '...') }}
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if (optional($record->radiographicExamination)->radiograph_type)
                                                                        @foreach ($record->radiographicExamination->radiograph_type as $type)
                                                                            <span
                                                                                class="badge bg-primary">{{ $type }}</span>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ optional($record->radiographicExamination)->radiograph_findings ?? '-' }}
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    <span
                                                                        class="badge {{ $record->is_complete ? 'bg-success' : 'bg-warning' }}">
                                                                        {{ $record->is_complete ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </td>
                                                                <td class="small">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-success"
                                                                        onclick="downloadPatientHistoryPDF({{ $record->id }})"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('frontend.download_pdf') }}">
                                                                        <i class="ph ph-file-pdf"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted text-center mb-0">
                                                {{ __('frontend.no_patient_history_records_found') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- 2. Problems/Complaints -->
                                <div class="col-md-4">
                                    @php
                                        $problems = $medical_history->get('encounter_problem', collect());
                                    @endphp
                                    @if ($problems->isNotEmpty())
                                        <div class="encounter-section mb-4">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">{{ __('frontend.problems') }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($problems as $problem)
                                                        <div class="d-flex align-items-start mb-2">
                                                            <span
                                                                class="badge bg-primary me-2">{{ $loop->iteration }}</span>
                                                            <span class="small">{{ $problem->title }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- 3. Observations -->
                                <div class="col-md-4">
                                    @php
                                        $observations = $medical_history->get('encounter_observations', collect());
                                    @endphp
                                    @if ($observations->isNotEmpty())
                                        <div class="encounter-section mb-4">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">{{ __('frontend.observations') }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($observations as $observation)
                                                        <div class="d-flex align-items-start mb-2">
                                                            <span
                                                                class="badge bg-info me-2">{{ $loop->iteration }}</span>
                                                            <span class="small">{{ $observation->title }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- 4. Notes -->
                                <div class="col-md-4">
                                    @php
                                        $notes = $medical_history->get('encounter_notes', collect());
                                    @endphp
                                    @if ($notes->isNotEmpty())
                                        <div class="encounter-section mb-4">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">{{ __('frontend.notes') }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($notes as $note)
                                                        <div class="d-flex align-items-start mb-2">
                                                            <span
                                                                class="badge bg-warning me-2">{{ $loop->iteration }}</span>
                                                            <span class="small">{{ $note->title }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- 5. Patient Records/Medical Reports -->
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.patient_records') }}</h6>

                                    </div>
                                    <div class="card-body">
                                        @php
                                            $medicalReports =
                                                optional($appointment->patientEncounter)->medicalReports ?? collect();
                                        @endphp
                                        @if ($medicalReports->isNotEmpty())
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-striped table-hover encounter-patient-records-table"
                                                    style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.radiographs') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.date') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($medicalReports as $report)
                                                            <tr class="encounter-table-row"
                                                                style="display: table-row !important;">
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if ($report->radiographs && is_array($report->radiographs))
                                                                        @foreach ($report->radiographs as $radiograph)
                                                                            <span
                                                                                class="badge bg-primary me-1">{{ $radiograph }}</span>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ formatDate($report->date) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @elseif($medical_reports && $medical_reports->isNotEmpty())
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-striped table-hover encounter-patient-records-table"
                                                    style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.radiographs') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.date') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($medical_reports as $report)
                                                            <tr class="encounter-table-row"
                                                                style="display: table-row !important;">
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if ($report->radiographs && is_array($report->radiographs))
                                                                        @foreach ($report->radiographs as $radiograph)
                                                                            <span
                                                                                class="badge bg-primary me-1">{{ $radiograph }}</span>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ formatDate($report->date) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @elseif($appointment->media && $appointment->media->isNotEmpty())
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-striped table-hover encounter-patient-records-table"
                                                    style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.radiographs') }}</th>
                                                            <th class="text-muted small encounter-table-header"
                                                                style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                {{ __('frontend.date') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($appointment->media as $media)
                                                            <tr class="encounter-table-row"
                                                                style="display: table-row !important;">
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    <span
                                                                        class="badge bg-primary">{{ $media->name ?? 'Medical Report' }}</span>
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ formatDate($media->created_at) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted text-center mb-0">
                                                {{ __('frontend.no_medical_reports_found') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Follow-up Notes -->
                            @if (isset($followup_notes) && $followup_notes->isNotEmpty())
                                <div class="encounter-section mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ __('frontend.follow_up_notes') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($followup_notes as $note)
                                                <div class="followup-note-item pb-3 mb-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-0">{{ $note->title ?? 'Follow-up Note' }}</h6>
                                                        <small
                                                            class="text-muted">{{ formatDate($note->created_at) }}</small>
                                                    </div>
                                                    <div class="small mb-0">{!! $note->description ?? 'No description available' !!}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- 7. Prescriptions -->
                            @if ($prescriptions->isNotEmpty())
                                <div class="encounter-section mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ __('frontend.prescriptions') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($prescriptions as $prescription)
                                                <div class="prescription-item pb-3 mb-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-0 text-primary">{{ $prescription->name }}</h6>
                                                    </div>
                                                    @if ($prescription->instruction)
                                                        <div class="small text-muted mb-2">{!! $prescription->instruction !!}</div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small
                                                                class="text-muted d-block">{{ __('frontend.frequency') }}</small>
                                                            <span
                                                                class="small fw-medium">{{ $prescription->frequency }}</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small
                                                                class="text-muted d-block">{{ __('frontend.duration') }}</small>
                                                            <span class="small fw-medium">{{ $prescription->duration }}
                                                                {{ __('frontend.days') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- 8. Orthodontic Treatment Daily Records -->
                            @if ($orthodonticRecords->isNotEmpty())
                                <div class="encounter-section mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ __('frontend.orthodontic_treatment_daily_record') }}
                                            </h6>
                                            <a href="{{ route('frontend.download-orthodontic-records', ['appointment_id' => $appointment->id]) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="ph ph-download me-1"></i>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $orthodonticRecords =
                                                    optional($appointment->patientEncounter)->orthodonticDailyRecords ??
                                                    collect();
                                            @endphp
                                            @if ($orthodonticRecords->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-striped table-hover encounter-orthodontic-table"
                                                        style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                        <thead class="table-light">
                                                            <tr style="display: table-row !important;">
                                                                <th class="text-muted small encounter-table-header"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ __('frontend.date') }}</th>
                                                                <th class="text-muted small encounter-table-header"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ __('frontend.procedure_performed') }}</th>
                                                                <th class="text-muted small encounter-table-header"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ __('frontend.oral_hygiene_status') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($orthodonticRecords as $record)
                                                                <tr class="encounter-table-row"
                                                                    style="display: table-row !important;">
                                                                    <td class="small encounter-table-cell"
                                                                        style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                        {{ formatDate($record->treatment_date) }}</td>
                                                                    <td class="small encounter-table-cell"
                                                                        style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                        {{ $record->procedure_performed ?? '-' }}</td>
                                                                    <td class="small encounter-table-cell"
                                                                        style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                        <span>
                                                                            {{ $record->oral_hygiene_status ?? '-' }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted text-center mb-0">
                                                    {{ __('frontend.no_orthodontic_records_found') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- 9. STL Records -->
                            {{-- <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.stl_records') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $stlRecords = $stl_records ?? collect();
                                        @endphp
                                        @if ($stlRecords->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="text-muted small">{{ __('frontend.date') }}</th>
                                                            <th class="text-muted small">{{ __('frontend.stl_files') }}
                                                            </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($stlRecords as $record)
                                                            <tr class="encounter-table-row"
                                                                style="display: table-row !important;">
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    {{ formatDate($record['date'] ?? $record->created_at) }}
                                                                </td>
                                                                <td class="small encounter-table-cell"
                                                                    style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if (isset($record['files']) && is_array($record['files']) && count($record['files']) > 0)
                                                                        @foreach (array_chunk($record['files'], 3) as $fileChunk)
                                                                            <div class="mb-1">
                                                                                @foreach ($fileChunk as $file)
                                                                                    <span
                                                                                        class="badge bg-primary me-1">{{ $file['name'] ?? 'STL File' }}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted text-center mb-0">
                                                {{ __('frontend.no_stl_records_found') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div> --}}

                            <!-- 10. Post-Operative Instructions -->
                            @php
                                $postInstructions = Modules\Appointment\Models\PostInstructions::all();
                            @endphp
                            @if ($postInstructions->count() > 0)
                                <div class="encounter-section mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#post-operative-collapse"
                                            aria-expanded="false" aria-controls="post-operative-collapse"
                                            style="cursor: pointer;">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0 me-2">
                                                    {{ __('frontend.post_operative_instructions_for_dental_procedures') }}
                                                </h6>
                                                <i class="ph ph-caret-down collapse-icon"
                                                    style="transition: transform 0.3s ease;"></i>
                                            </div>

                                        </div>
                                        <div class="collapse" id="post-operative-collapse">
                                            <div class="card-body">
                                                @foreach($postInstructions as $instruction)
                                                    <div class="mb-3">
                                                        <h6 class="mb-2 text-primary">{{ $instruction->title }}</h6>
                                                        @if($instruction->procedure_type)
                                                            <small class="text-muted d-block mb-2">{{ $instruction->procedure_type }}</small>
                                                        @endif
                                                        <div class="mb-0 small">{!! $instruction->post_instructions !!}</div>
                                                    </div>
                                                    @if(!$loop->last)
                                                        <hr class="my-3">
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- 11. Other Information -->
                            @if (optional($appointment->patientEncounter)->other_details)
                                <div class="encounter-section mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ __('frontend.other_information') }}</h6>
                                            <a href="{{ route('frontend.download-other-details', ['appointment_id' => $appointment->id]) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="ph ph-download me-1"></i>

                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-0 small">{!! $appointment->patientEncounter->other_details !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content section-bg rounded">
                <div class="close-modal-btn" data-bs-dismiss="modal">
                    <i class="ph ph-x align-middle"></i>
                </div>
                <div class="modal-body modal-payemnt-inner">
                    <h6 class="mb-3 font-size-18" id="paymentModalLabel">{{ __('frontend.payment_method') }}</h6>
                    <div class="payment-modal-box rounded">
                        @foreach ($paymentMethods as $method)
                            <div
                                class="form-check payment-method-items ps-0 d-flex justify-content-between align-items-center gap-3">
                                <label class="form-check-label d-flex gap-2 align-items-center"
                                    for="method-{{ $method }}">
                                    <img src="{{ asset('dummy-images/payment_icons/' . strtolower($method) . '.svg') }}"
                                        alt="{{ $method }}" style="width: 20px; height: 20px;">
                                    <span class="h6 fw-semibold m-0">{{ $method }}</span>
                                </label>
                                <input class="form-check-input" type="radio" name="payment_method"
                                    value="{{ $method }}" id="method-{{ $method }}"
                                    @if ($method === 'cash') checked @endif>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-end mt-5">
                        <button class="btn btn-secondary" id="pay_now"
                            data-bs-dismiss="modal">{{ __('frontend.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Post-Operative Instructions collapse state
            $('#post-operative-collapse').on('show.bs.collapse', function() {
                $(this).closest('.card').find('.collapse-icon').css('transform', 'rotate(0deg)');
            });

            $('#post-operative-collapse').on('hide.bs.collapse', function() {
                $(this).closest('.card').find('.collapse-icon').css('transform', 'rotate(-90deg)');
            });

            // Initialize collapse icon state
            $('#post-operative-collapse').on('shown.bs.collapse hidden.bs.collapse', function() {
                const isExpanded = $(this).hasClass('show');
                const header = $(this).closest('.card').find('.card-header');
                header.attr('aria-expanded', isExpanded);
            });

            // Set initial state for collapsed section
            $(document).ready(function() {
                const postOperativeHeader = $('#post-operative-collapse').closest('.card').find(
                    '.card-header');
                const postOperativeIcon = postOperativeHeader.find('.collapse-icon');

                // Since it starts collapsed, set the icon to rotated position
                postOperativeIcon.css('transform', 'rotate(-90deg)');
                postOperativeHeader.attr('aria-expanded', 'false');
            });

            $('.delete-rating-btn').on('click', function() {
                const reviewId = $(this).data('review-id');

                Swal.fire({
                    title: 'Are you sure you want to remove your review?',
                    text: 'Once deleted, your review cannot be recovered',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--bs-secondary)',
                    cancelButtonColor: 'var(--bs-gray-500)',
                    confirmButtonText: 'Delete Review',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('delete-rating') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: reviewId
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: data.message
                                });
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'There was an error deleting the review. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
        });

        // Ensure function is available globally
        // window.viewStlFiles = viewStlFiles;

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('paymentDetails'))
                const paymentDetails = @json(session('paymentDetails'));
                Swal.fire({
                    title: 'Payment Success',
                    html: `
                    <p>Your appointment with <strong>Dr. ${paymentDetails.doctorName}</strong> at
                    <strong>${paymentDetails.clinicName}</strong> has been confirmed on
                    <strong>${new Date(paymentDetails.appointmentDate).toLocaleDateString()}</strong> at
                    <strong>${new Date('1970-01-01T' + paymentDetails.appointmentTime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</strong>.</p>
                    <div>
                        <p><strong>Booking ID:</strong> #${paymentDetails.bookingId}</p>
                        <p><strong>Payment via:</strong>${paymentDetails.paymentVia}</p>
                        <p><strong>Total Payment:</strong>${paymentDetails.currency} ${paymentDetails.totalAmount}</p>
                    </div>
                `,
                    icon: 'success',
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#FF6F61',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('appointment-list') }}";
                    }
                });
            @endif
        });

        document.querySelector('#pay_now').addEventListener('click', async function() {
            const appointmentId = "{{ $appointment->id }}";
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const baseUrl = "{{ url('/') }}";
            const totalAmount = parseFloat("{{ $appointment->total_amount }}");
            const advancePaymentAmount = parseFloat("{{ $appointment->advance_payment_amount }}");
            const advancePaymentStatus = parseInt("{{ $appointment->advance_payment_status }}");

            // Check wallet balance if wallet is selected payment method
            if (selectedPaymentMethod === 'Wallet') {
                try {
                    const response = await fetch("{{ route('check.wallet.balance') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            totalAmount: advancePaymentStatus === 1 ? advancePaymentAmount :
                                totalAmount
                        })
                    });

                    const data = await response.json();

                    if (!data.success || (advancePaymentStatus === 1 ? data.balance < advancePaymentAmount :
                            data.balance < totalAmount)) {
                        successSnackbar('Insufficient balance. Please add funds in wallet')
                        return;
                    }
                } catch (error) {
                    console.error('Error checking wallet balance:', error);
                    return;
                }
            }

            fetch(`${baseUrl}/pay-now`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        appointment_id: appointmentId,
                        transaction_type: selectedPaymentMethod
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.status) {
                        if (selectedPaymentMethod === 'Wallet') {
                            const paymentDetails = {
                                doctorName: "{{ optional($appointment->doctor)->first_name }} {{ optional($appointment->doctor)->last_name }}",
                                clinicName: "{{ optional($appointment->cliniccenter)->name }}",
                                appointmentDate: "{{ $appointment->appointment_date }}",
                                appointmentTime: "{{ $appointment->appointment_time }}",
                                bookingId: appointmentId,
                                paymentVia: selectedPaymentMethod,
                                currency: "{{ $appointment->currency_symbol }}",
                                totalAmount: advancePaymentStatus === 1 ? advancePaymentAmount.toFixed(
                                    2) : totalAmount.toFixed(2)
                            };

                            Swal.fire({
                                title: 'Payment Success',
                                html: `
                            <p>Your appointment with <strong>Dr. ${paymentDetails.doctorName}</strong> at
                            <strong>${paymentDetails.clinicName}</strong> has been confirmed on
                            <strong>${new Date(paymentDetails.appointmentDate).toLocaleDateString()}</strong> at
                            <strong>${new Date('1970-01-01T' + paymentDetails.appointmentTime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</strong>.</p>
                            <div>
                                <p><strong>Booking ID:</strong> #${paymentDetails.bookingId}</p>
                                <p><strong>Payment via:</strong>${paymentDetails.paymentVia}</p>
                                <p><strong>Total Payment:</strong>${paymentDetails.currency} ${paymentDetails.totalAmount}</p>
                            </div>
                        `,
                                icon: 'success',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#FF6F61',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = `${baseUrl}/appointment-list`;
                                }
                            });
                        } else {
                            window.location.href = `${baseUrl}/appointment-list`;
                        }
                    } else {
                        alert(data.message || 'Payment failed.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during payment processing.');
                });
        });

        // Download individual patient history PDF
        function downloadPatientHistoryPDF(id) {
            var baseUrl = '{{ url('/') }}';
            window.open(baseUrl + '/download-patient-history-pdf/' + id, '_blank');
        }

        // View STL files functionality
    </script>
@endpush
