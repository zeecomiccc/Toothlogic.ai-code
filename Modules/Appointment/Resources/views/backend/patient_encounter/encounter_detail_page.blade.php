@extends('backend.layouts.app')

@section('title')
    {{ __($module_title) }}
@endsection
@push('after-styles')
    <style>
        .stepper {
            /* display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 0.5rem; */
            /* width: 100%; */
            position: relative;
        }
        .stepper-row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            gap: 0.5rem;
        }
        .stepper-step {
            min-width: 80px;
            flex: 1 1 0;
            position: relative;
            z-index: 2;
            cursor: pointer;
            min-width: 0;
        }
        .stepper-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            transition: background 0.2s, color 0.2s;
        }

        .stepper-step.active .stepper-circle {
            background: #00C2CB;
            color: #fff;
        }

        .stepper-step.completed .stepper-circle {
            background: #22c55e;
            color: #fff;
        }

        .stepper-label {
            font-size: 0.95rem;
            color: #6c757d;
        }

        .stepper-step.active .stepper-label {
            color: #00C2CB;
            font-weight: 600;
        }
        .stepper-line {
            height: 2px;
            background: #e9ecef;
            margin: 0 0.25rem;
            align-self: center;
            /* flex: 0 0 24px;
            width: 24px; */
            z-index: 1;
        }
        .stepper-step.completed + .stepper-line {
            background: #22c55e;
        }
        /* Responsive: 2 rows of 4 steps at <=900px */
        @media (max-width: 900px) {
            .stepper {
                flex-wrap: wrap;
                row-gap: 1.5rem;
            }
            .stepper-step, .stepper-line {
                flex-basis: 12.5%;
                max-width: 12.5%;
            }
            .stepper-step {
                min-width: 60px;
            }
            .stepper-circle {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }
            .stepper-label {
                font-size: 0.8rem;
            }
            /* Hide the line after the 4th step for row break */
            .stepper-line:nth-of-type(4) {
                display: none;
            }
        }
        @media (max-width: 768px) {
            .stepper-step {
                min-width: 48px;
            }
            .stepper-circle {
                width: 28px;
                height: 28px;
                font-size: 0.9rem;
            }
            .stepper-label {
                font-size: 0.75rem;
            }
        }
        @media (max-width: 576px) {
            .stepper-step {
                min-width: 36px;
            }
            .stepper-circle {
                width: 24px;
                height: 24px;
                font-size: 0.8rem;
            }
            .stepper-label {
                font-size: 0.7rem;
            }
        }
        /* Modal containment */
        .modal-xl .modal-dialog {
            margin: 1.75rem auto;
        }
        @media (max-width: 768px) {
            .modal-xl .modal-dialog {
                max-width: 98%;
                margin: 0.5rem auto;
            }
        }
        #stepper-nav {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }
        #stepper-nav::-webkit-scrollbar {
            height: 4px;
        }
        #stepper-nav::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        #stepper-nav::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        #stepper-nav::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        #patient-history-loader {
            display: flex;
        }

        /* Ensure modal content is responsive */
        .modal-xl .modal-dialog {
            margin: 1.75rem auto;
        }

        @media (max-width: 768px) {
            .modal-xl .modal-dialog {
                max-width: 98%;
                margin: 0.5rem auto;
            }
        }

        /* Ensure stepper container doesn't overflow */
        #stepper-nav {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        #stepper-nav::-webkit-scrollbar {
            height: 4px;
        }

        #stepper-nav::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #stepper-nav::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }

        #stepper-nav::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .signature-pad {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            background-color: #fff;
            cursor: crosshair;
            width: 100%;
            height: 120px;
            touch-action: none;
            display: block;
        }
        .signature-pad-container .btn {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }
        .signature-pad:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
    @section('content')
        <div class="row">
            <div class="col-xxl-3 col-lg-4 col-md-5">
                <h4 class=" mb-3"></h4>
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-1">{{ __('appointment.about_clinic') }}</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-inline m-0 p-0">
                            <li class="item mb-5 pb-5 border-bottom">
                                <div>
                                    <div class="d-flex gap-3 align-items-center">
                                        <img src="{{ optional($data->clinic)->file_url }}" alt="avatar"
                                            class="avatar avatar-64 rounded">
                                        <div class="text-start">
                                            <h5 class="m-0">{{ optional($data->clinic)->name ?? '--' }}</h6>
                                                <p class="mb-2"> {{ optional($data->clinic)->email ?? '--' }}</p>
                                                <h5 class="m-0">Dr. {{ optional($data->doctor)->full_name ?? '--' }}</h6>
                                        </div>
                                    </div>
                                    <div class="mb-1">{{ $data->description }}</div>
                                </div>
                            </li>
                            <li class="item mb-1">
                                <div>
                                    <h4 class="mb-3">{{ __('appointment.about_patient') }}</h6>
                                        <div class="d-flex gap-3 align-items-center">
                                            <img src="{{ optional($data->user)->profile_image ?? default_user_avatar() }}"
                                                alt="avatar" class="avatar avatar-64 rounded-pill">
                                            <div class="text-start">
                                                <h5 class="m-0">
                                                    {{ optional($data->user)->full_name ?? default_user_name() }}</h5>
                                                <p class="mb-0">{{ optional($data->user)->email ?? '--' }}</p>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-between flex-wrap gap-1 mt-4 mb-3">
                                            <p class="mb-0">{{ __('appointment.encounter_date') }}:</p>
                                            <span class="heading-color">{{ formatDate($data->encounter_date) ?? '--' }}</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-3">
                                            <p class="mb-0">{{ __('appointment.address') }}:</p>
                                            <span class="heading-color">{{ $data->user->address ?? '' }} </span>
                                            <span class="heading-color">{{ $data->user->cities->name ?? '' }}
                                                {{ $data->user->countries->name ?? '' }}
                                                {{ $data->user->pincode ?? '' }}</span>
                                        </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                    <p class="mb-0">{{ __('appointment.status') }}:</p>
                                    @if ($data->status == 1)
                                        <span class="text-success">{{ __('appointment.open') }}</span>
                                    @else
                                        <span v-else class="text-danger"> {{ __('appointment.close') }}</span>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- @if ($data['status'] == 1)
                    <div class="card encounter-temeplate">
                        <div class="card-body"> --}}
                            {{-- <h6>{{ __('appointment.select_encounter_templates') }}</h6> --}}
                            {{-- <select name="template_id" id="template_id" class="form-control select2"
                                placeholder="{{ __('clinic.lbl_select_template') }}" data-filter="select">
                                <option value="">{{ __('clinic.lbl_select_template') }}</option>
                                @foreach ($template_data as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif --}}
            </div>
            <div class="col-xxl-9 col-lg-8 col-md-7">
                <h4 class=" mb-3"></h4>

                <div>

                    <nav class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        {{-- <div class="nav nav-tabs bg-transparent gap-4" id="nav-tab" role="tablist"> --}}
                        {{-- <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                        aria-selected="true">{{ __('appointment.clinic_details') }}
                    </button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false">{{
                        __('appointment.soap') }}</button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                        type="button" role="tab" aria-controls="nav-contact" aria-selected="false">{{
                        __('appointment.body_chart') }}</button> --}}

                        {{-- @if (count($data['customform']) > 0)
                    <button class="nav-link" id="nav-custom-form-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-custom-form" type="button" role="tab" aria-controls="nav-custom-form"
                        aria-selected="false">Custom Form</button>
                    @endif --}}

                        {{--
                </div> --}}
                        <div>
                            <h3 class="pt-3 text-left">{{ __('clinic.clinical_details') }}</h3>
                        </div>

                        @if ($data['status'] == 1)
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#generate_invoice">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <i class="ph ph-plus"></i>
                                    {{ __('appointment.close_encounter') }} & {{ __('appointment.check_out') }}
                                </div>
                            </button>
                        @else
                            <a href="{{ url('app/billing-record/encounter_billing_detail') }}?id={{ $data['id'] }}">
                                <button class="btn btn-primary">
                                    <i class="ph ph-file-text me-1"></i>
                                    {{ __('appointment.billing_details') }}
                                </button>
                            </a>
                        @endif
                    </nav>
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab" tabindex="0">

                                    <!-- Estimate PDF Section -->
                                    @if(isset($data['billingrecord']) && $data['billingrecord']['is_estimate'])
                                        <div class="mb-4">
                                            <div class="card mb-4 bg-body-tertiary">
                                                <div class="card-header px-0 d-flex align-items-center justify-content-between p-lg-3">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ph ph-file-pdf me-2"></i>
                                                        {{ __('appointment.estimate_pdf') }}
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                            <div class="card h-100">
                                                                <div class="card-body">
                                                                    <h6 class="card-title text-primary">Treatment Estimate</h6>
                                                                    <p class="text-muted small mb-3">
                                                                        This encounter has been saved as an estimate. You can download the detailed treatment estimate PDF.
                                                                    </p>
                                                                    <a href="{{ route('backend.billing-record.download.pdf', ['id' => $data['billingrecord']['id']]) }}" 
                                                                           class="btn btn-sm btn-outline-primary" download>
                                                                            <i class="ph ph-download me-1"></i>
                                                                        </a>
                                                                </div>
                                                            </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-4">

                                        <div class="card-header px-0 mb-3 d-flex justify-content-between flex-wrap gap-3">
                                            <h5 class="card-title">{{ __('appointment.history_and_exam_form') }}</h5>
                                            @if ($data['status'] == 1)
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addPatientHistory">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <i class="ph ph-plus"></i>
                                                        {{ __('appointment.add_patient_history') }}
                                                    </div>
                                                </button>
                                            @endif
                                        </div>

                                        <div class="card-body bg-body" style="padding: 1px">
                                            <div id="medical_report_table">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.patient_history_table',
                                                    ['data' => $data]
                                                )
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        @if ($encounter_data['is_encounter_problem'] == 1)
                                            <div class="col-xl-4 col-lg-6" id="encounter_problem">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.encounter_problem',
                                                    ['data' => $data, 'problem_list' => $problem_list]
                                                )
                                            </div>
                                        @endif

                                        @if ($encounter_data['is_encounter_observation'] == 1)
                                            <div class="col-xl-4 col-lg-6" id="encounter_observation">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.encounter_observation',
                                                    ['data' => $data, 'observation_list' => $observation_list]
                                                )
                                            </div>
                                        @endif

                                        @if ($encounter_data['is_encounter_note'] == 1)
                                            <div class="col-xl-4" id="encounter_note">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.encounter_note',
                                                    ['data' => $data]
                                                )
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <div class="card-header px-0 mb-3 d-flex justify-content-between flex-wrap gap-3">
                                            <h5 class="card-title">{{ __('appointment.patient_records') }}</h5>
                                            @if ($data['status'] == 1)
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addMedicalreport">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <i class="ph ph-plus"></i>
                                                        {{ __('appointment.add_patient_record') }}
                                                    </div>
                                                </button>
                                            @endif
                                        </div>

                                        <div class="card-body bg-body" style="padding: 1px">
                                            <div id="medical_report_table">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.medical_report_table',
                                                    ['data' => $data]
                                                )
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="card-header px-0 mb-3 d-flex justify-content-between flex-wrap gap-3">
                                            <h5 class="card-title">{{ __('appointment.follow_up_notes') }}</h5>
                                            @if ($data['status'] == 1)
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addFollowUpNote">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <i class="ph ph-plus"></i>
                                                        {{ __('appointment.add_follow_up_note') }}
                                                    </div>
                                                </button>
                                            @endif
                                        </div>

                                        <div class="card-body bg-body" style="padding: 1px">
                                            <div id="followup_note_table">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.followup_note_table',
                                                    ['followup_notes' => $followup_notes ?? []]
                                                )
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="card-header d-flex justify-content-between flex-wrap gap-3 px-0 mb-3  ">
                                            <h5 class="card-title">{{ __('appointment.prescription') }}</h5>
                                            <div>
                                                <div class="d-flex align-items-center flex-wrap gap-3">
                                                    @if ($data['status'] == 1)
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#addprescription">
                                                            <div class="d-inline-flex align-items-center gap-1">
                                                                <i class="ph ph-plus"></i>
                                                                {{ __('appointment.add_prescription') }}
                                                            </div>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body bg-body" style="padding: 1px">
                                            <div id="prescription_table">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.prescription_table',
                                                    ['data' => $data]
                                                )
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="card-header d-flex justify-content-between flex-wrap gap-3 px-0 mb-3  ">
                                            <h5 class="card-title">{{ __('appointment.orthodontic_treatment_daily_record') }}
                                            </h5>
                                            <div>
                                                <div class="d-flex align-items-center flex-wrap gap-3">
                                                    @if ($data['status'] == 1)
                                                        @if ($orthodontic_daily_records->count() > 0)
                                                            <button class="btn btn-sm btn-primary"
                                                                onclick="DownloadOrthoDailyRecordsPDF({{ $data['id'] }})">
                                                                <div class="d-inline-flex align-items-center gap-1">
                                                                    <i class="ph ph-download"></i>
                                                                    {{ __('appointment.download_orthodontic_daily_record') }}
                                                                </div>
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#addOrthoDailyRecordModal">
                                                            <div class="d-inline-flex align-items-center gap-1">
                                                                <i class="ph ph-plus"></i>
                                                                {{ __('appointment.add_orthodontic_daily_record') }}
                                                            </div>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body bg-body" style="padding: 1px">
                                            <div id="ortho_daily_record_table">
                                                @include(
                                                    'appointment::backend.patient_encounter.component.orthodontic_treatment_daily_record_table',
                                                    [
                                                        'orthodontic_daily_records' =>
                                                            $orthodontic_daily_records ?? [],
                                                    ]
                                                )
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="mb-4">
                                        <div class="card-header px-0 mb-3 d-flex justify-content-between flex-wrap gap-3">
                                            <h5 class="card-title">{{ __('appointment.stl_records') }}</h5>
                                            @if ($data['status'] == 1)
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStlModal">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <i class="ph ph-plus"></i>
                                                        {{ __('appointment.add_stl_record') }}
                                                    </div>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="card-body bg-body" style="padding: 1px">
                                            <div id="stls_table">
                                                @include('appointment::backend.patient_encounter.component.stls_table', ['data' => $data])
                                            </div>
                                        </div>
                                    </div> --}}

                                    @php
                                        $postInstructions = Modules\Appointment\Models\PostInstructions::all();
                                    @endphp

                                    @if ($postInstructions->count() > 0)
                                        <div class="mb-4">
                                            <div class="card mb-4 bg-body-tertiary">
                                                <div class="card-header px-0 d-flex align-items-center justify-content-between p-lg-3">
                                                    <h5 class="card-title mb-0">{{ __('appointment.post_operative_instructions_for_dental_procedures') }}</h5>
                                                    <button class="btn btn-primary"
                                                        onclick="window.location.href='{{ route('backend.download-instructions', ['encounter_id' => $data['id']]) }}'">
                                                        <i class="ph ph-download"></i> {{ __('Download All') }}
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach ($postInstructions as $instruction)
                                                            <div class="col-md-6 col-lg-4 mb-3">
                                                                <div class="card h-100">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title text-primary">{{ $instruction->title }}</h6>
                                                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                                                            <button class="btn btn-sm btn-outline-primary"
                                                                                onclick="window.location.href='{{ route('backend.download-instructions-by-type', ['encounter_id' => $data['id'], 'procedure_type' => $instruction->procedure_type]) }}'">
                                                                                <i class="ph ph-download me-1"></i> {{ __('Download') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="other-detail">
                                        <div class="card-header px-0 mb-3">
                                            <h6 class="card-title mb-0">{{ __('appointment.other_information') }}</h6>
                                        </div>
                                        <div class="">
                                            <textarea class="form-control h-auto bg-body" rows="3"
                                                placeholder="{{ __('appointment.enter_other_details') }}" name="other_details" id="other_details"
                                                style="min-height: max-content">
                                                {{ old('other_details', $data['EncounterOtherDetails']['other_details'] ?? '') }}
                                            </textarea>
                                        </div>
                                    </div>

                                    @if ($data['status'] == 1)
                                        <div class="offcanvas-footer border-top pt-4" id="save_button">
                                            <div class="d-grid d-sm-flex justify-content-sm-end gap-3">
                                                <button class="btn btn-secondary" type="submit">
                                                    {{ __('messages.save') }}
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                            aria-labelledby="nav-profile-tab" tabindex="0">
                            <div id="soap">

                                @include('appointment::backend.patient_encounter.component.soap', [
                                'data' => $data,
                                ])

                            </div>
                        </div> --}}

                                {{-- <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                            aria-labelledby="nav-contact-tab" tabindex="0">
                            <div id="body_chart_list">
                                @include(
                                'appointment::backend.patient_encounter.component.body_chart_list',
                                ['data' => $data]
                                )
                            </div>

                            <div id="add_body_chart" class="">

                                @include('appointment::backend.clinic_appointment.apointment_bodychartform', [
                                'encounter_id' => $data['id'],
                                'appointment_id' => $data['appointment_id'],
                                'patient_id' => $data['user_id']
                                ])

                            </div>
                        </div> --}}

                                <div class="tab-pane fade" id="nav-custom-form" role="tabpanel"
                                    aria-labelledby="nav-custom-form-tab" tabindex="0">
                                    <div id="custom_form">

                                        @include(
                                            'appointment::backend.patient_encounter.component.custom_form',
                                            [
                                                'data' => $data['customform'],
                                                'encounter_id' => $data['id'],
                                                'appointment_id' => $data['appointment_id'],
                                            ]
                                        )
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('appointment::backend.patient_encounter.component.prescription', ['data' => $data])

            @include('appointment::backend.patient_encounter.component.medical_report', ['data' => $data])

            @include('appointment::backend.patient_encounter.component.billing_details', [
                'data' => $data,
            ])

            @include('appointment::backend.patient_encounter.component.patient_history_form', [
                'data' => $data,
            ])

            @include('appointment::backend.patient_encounter.component.followup_note_form', [
                'data' => $data,
            ])

            @include(
                'appointment::backend.patient_encounter.component.orthodontic_treatment_daily_record_form',
                [
                    'data' => $data,
                ]
            )

            @include('appointment::backend.patient_encounter.component.stls', [
                'data' => $data,
            ])
        </div>
    @endsection

    @push('after-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        
        <script>
            document.getElementById('save_button').addEventListener('click', function() {
                const encounterId = {{ $data->id }};
                const userId = {{ $data->user_id }};
                const templateInput = document.getElementById('template_id');
                const otherDetailsInput = document.getElementById('other_details');

                const template_id = templateInput ? templateInput.value : ({{ $data->encounter_template_id ?? 'null' }});
                const other_details = otherDetailsInput ? otherDetailsInput.value : '';

                const data = {
                    encounter_id: encounterId,
                    template_id: template_id,
                    other_details: other_details,
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                };

                fetch('{{ route('backend.encounter.save-encounter') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': data._token
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data) {

                            window.successSnackbar(`Encounter saved successfully`);

                        } else {
                            window.errorSnackbar('Something went wrong! Please check.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const hash = window.location.hash;
                if (hash) {
                    const tabButton = document.querySelector(`[data-bs-target="${hash}"]`);
                    if (tabButton) {

                        tabButton.click();

                        const tabContent = document.querySelector(hash);
                        if (tabContent) {
                            tabContent.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    }
                }
            });

            $(document).ready(function() {
                var baseUrl = '{{ url('/') }}';
                $('#template_id').change(function() {
                    let templateId = $(this).val();
                    let additionalData = {
                        user_id: '{{ $data['user_id'] ?? '' }}', // Use null coalescing operator for safety
                        encounter_id: '{{ $data['id'] ?? '' }}', // Same for the encounter ID
                        status: '{{ $data['status'] ?? '' }}',

                    };
                    // Clear the components section


                    if (templateId) {
                        $.ajax({
                            url: baseUrl + `/app/encounter/get-template-data/${templateId}`,
                            type: 'GET',
                            data: additionalData,
                            success: function(response) {
                                console.log(response);
                                // Append problems if available
                                if (response.is_encounter_problem) {
                                    $('#encounter_problem').html('');
                                    $('#encounter_problem').append(response.problem_html);
                                }
                                // Append observations if available
                                if (response.is_encounter_observation) {

                                    console.log(response.observation_html);
                                    $('#encounter_observation').html('');
                                    $('#encounter_observation').append(response.observation_html);
                                }
                                // Append notes if available
                                if (response.is_encounter_note) {
                                    $('#encounter_note').html('');
                                    $('#encounter_note').append(response.note_html);
                                }

                                if (response.is_encounter_precreption) {
                                    $('#prescription_table').html('');
                                    $('#prescription_table').append(response.precreption_html);
                                }
                                if (response.is_encounter_otherdetail) {

                                    $('#other_details').val(response.other_detail_html);
                                }
                            },
                            error: function() {
                                console.error('Failed to load template data.');
                            }
                        });
                    }
                });
            });
        </script>

        {{-- patient_history script --}}
        <script>

            // Add global variable to track edit mode
            var editPatientHistoryId = null;

            // Global function to be called from edit button
            window.editPatientHistory = function(id) {
                editPatientHistoryId = id;
                $('#addPatientHistory').modal('show');
            };

              // Function to auto-fill demographic fields with user data
              function autoFillDemographics() {
                try {
                    // Get user data from data attributes
                    const fullName = document.getElementById('full_name').getAttribute('data-user-full-name');
                    const dob = document.getElementById('dob').getAttribute('data-user-dob');
                    const gender = document.getElementById('gender_male').getAttribute('data-user-gender');
                    const address = document.getElementById('address').getAttribute('data-user-address');
                    const phone = document.getElementById('phone').getAttribute('data-user-phone');
                    const email = document.getElementById('email').getAttribute('data-user-email');
                    
                    let fieldsFilled = 0;
                    
                    // Fill the fields if user data exists
                    if (fullName && fullName.trim()) {
                        document.getElementById('full_name').value = fullName.trim();
                        fieldsFilled++;
                    }
                    
                    if (dob && dob.trim()) {
                        document.getElementById('dob').value = dob;
                        fieldsFilled++;
                    }
                    
                    if (gender && gender.trim()) {
                        // Set the appropriate gender radio button
                        const genderValue = gender.toLowerCase();
                        if (genderValue === 'male') {
                            document.getElementById('gender_male').checked = true;
                            fieldsFilled++;
                        } else if (genderValue === 'female') {
                            document.getElementById('gender_female').checked = true;
                            fieldsFilled++;
                        } else if (genderValue === 'other') {
                            document.getElementById('gender_other').checked = true;
                            fieldsFilled++;
                        }
                    }
                    
                    if (address && address.trim()) {
                        document.getElementById('address').value = address.trim();
                        fieldsFilled++;
                    }
                    
                    if (phone && phone.trim()) {
                        document.getElementById('phone').value = phone.trim();
                        fieldsFilled++;
                    }
                    
                    if (email && email.trim()) {
                        document.getElementById('email').value = email.trim();
                        fieldsFilled++;
                    }
                } catch (error) {
                    console.error('Error auto-filling demographics:', error);
                }
            }   

            // Auto-fill demographics when the modal is shown
            document.addEventListener('DOMContentLoaded', function() {
                // Check if this is a new patient history (no existing ID)
                const patientHistoryId = document.getElementById('patient_history_id').value;
                
                // If no existing ID, auto-fill the demographics
                if (!patientHistoryId) {
                    setTimeout(function() {
                        autoFillDemographics();
                    }, 200); // Small delay to ensure DOM is ready
                }
                
                // Also auto-fill when the modal is opened (for Bootstrap modals)
                const modal = document.getElementById('addPatientHistory');
                if (modal) {
                    modal.addEventListener('shown.bs.modal', function() {
                        const patientHistoryId = document.getElementById('patient_history_id').value;
                        if (!patientHistoryId) {
                            setTimeout(function() {
                                autoFillDemographics();
                            }, 100);
                        }
                    });
                }
            });

            $(document).ready(function() {
                var baseUrl = '{{ url('') }}';
                var currentStep = 1;
                var totalSteps = 9;
                var stepKeys = [
                    'demographic',
                    'medical-history',
                    'dental-history',
                    'chief-complaint',
                    'clinical-examination',
                    'radiographic-examination',
                    'diagnosis-plan',
                    'dental-chart',
                    'informed-consent'
                ];
                
                // Debug logging for step configuration
                console.log('Patient history form initialized with:', {
                    currentStep: currentStep,
                    totalSteps: totalSteps,
                    stepKeys: stepKeys
                });
                
                var lastSetPatientHistoryId = null;

                function clearRecordIds() {
                    recordIds = {};
                }

                function updateStepper(step) {
                    $('.stepper-step').removeClass('active completed');
                    $('.stepper-step').each(function(idx) {
                        var s = idx + 1;
                        if (s < step) $(this).addClass('completed');
                        if (s === step) $(this).addClass('active');
                    });
                }

                function showStep(step) {
                    var $form = $('#patient-history-submit');
                    $('.step').hide();
                    $('.step-' + step).show();
                    $('#prev-step').toggle(step > 1);
                    $('#next-step').toggle(step < totalSteps);
                    $('#save-step').show();
                    updateStepper(step);
                    $form.validate().resetForm(); // Clear errors on step change
                    $form.find('.is-invalid').removeClass('is-invalid');
                    $form.find('.text-danger.validation-error').text('');
                    
                    // Initialize signature pads if step 9 is shown
                    if (step === 9 && typeof initializeSignaturePadsForStep9 === 'function') {
                        initializeSignaturePadsForStep9();
                    }
                    
                    // Special handling for dental chart step
                    if (step === 8) {
                        // Wait for dental chart to be ready, then restore data if we have it
                        setTimeout(() => {
                            const patientHistoryId = $('#patient_history_id').val();
                            if (patientHistoryId && recordIds['dental-chart']) {
                                // Fetch and restore dental chart data
                                $.get(getStepFetchEndpoint('dental-chart', recordIds['dental-chart']))
                                    .done(function(response) {
                                        if (response && Object.keys(response).length > 0) {
                                            fillDentalChartData(response);
                                        }
                                    })
                                    .fail(function(xhr) {
                                        console.error('Failed to fetch dental chart data:', xhr);
                                    });
                            }
                            
                            // Also call the auto-restore function to ensure colors are restored
                            if (typeof autoRestoreDentalChartColors === 'function') {
                                autoRestoreDentalChartColors();
                            } else {
                                console.log('autoRestoreDentalChartColors function not found');
                            }
                        }, 1000); // Wait 1 second for dental chart to be fully ready
                    }
                }

                function getStepKey(step) {
                    var stepKey = stepKeys[step - 1];
                    
                    return stepKey;
                }

                function getStepEndpoint(stepKey) {
                    return baseUrl + '/app/appointment/patient-history/' + stepKey;
                }

                function getStepFetchEndpoint(stepKey, id) {
                    return baseUrl + '/app/appointment/patient-history/' + stepKey + '/' + id;
                }

                function collectStepData(stepKey) {
                    var data = {};
                    $('.step-' + currentStep + ' :input[name]').each(function() {
                        var name = $(this).attr('name');
                        if ($(this).attr('type') === 'checkbox') {
                            if (!data[name]) data[name] = [];
                            if ($(this).is(':checked')) data[name].push($(this).val());
                        } else if ($(this).attr('type') === 'radio') {
                            if ($(this).is(':checked')) data[name] = $(this).val();
                        } else {
                            data[name] = $(this).val();
                        }
                    });
                    // Only include id if it is a valid value
                    if (recordIds[stepKey] !== undefined && recordIds[stepKey] !== null && recordIds[stepKey] !== '') {
                        data.id = recordIds[stepKey];
                    }
                    data.patient_history_id = $('#patient_history_id').val();
                    
                    return data;
                }

                function fillStepData(stepKey, data) {
                    for (const key in data) {
                        if (key === 'id' || key === 'patient_history_id') continue;

                        // Select elements that match exact or array-style names
                        const el = $('[name="' + key + '"], [name="' + key + '[]"]');

                        if (el.length) {
                            const type = el.attr('type');

                            if (type === 'checkbox') {
                                let values = data[key];

                                // Normalize input value (null => empty array)
                                if (values === null || values === undefined) {
                                    values = [];
                                }

                                // If string, try to parse JSON
                                if (typeof values === 'string') {
                                    try {
                                        if (values.trim().startsWith('[')) {
                                            values = JSON.parse(values);
                                        } else {
                                            values = [values];
                                        }
                                    } catch (e) {
                                        values = [values];
                                    }
                                }

                                // Wrap non-array as array
                                if (!Array.isArray(values)) values = [values];

                                // Normalize all to strings for comparison
                                values = values.map(v => String(v));

                                // Set checked based on values
                                el.each(function() {
                                    const checkboxVal = String($(this).val());
                                    $(this).prop('checked', values.includes(checkboxVal));
                                });

                            } else if (type === 'radio') {
                                const dataVal = String(data[key]);
                                el.each(function() {
                                    const radioVal = String($(this).val());

                                    // Optional: convert boolean/number to "Yes"/"No"
                                    let normalizedDataVal = dataVal;
                                    if (dataVal === '1' || dataVal === 'true') {
                                        normalizedDataVal = 'Yes';
                                    } else if (dataVal === '0' || dataVal === 'false') {
                                        normalizedDataVal = 'No';
                                    }

                                    $(this).prop('checked', radioVal === normalizedDataVal);
                                });

                            } else {
                                // Set value for other input types
                                el.val(data[key]);
                            }
                        }
                    }
                    
                    // Special handling for informed consent signatures
                    if (stepKey === 'informed-consent') {
                        // Load existing signatures into the signature pads
                        if (data.patient_signature && typeof patientSignaturePad !== 'undefined') {
                            // Load patient signature
                            const patientImg = new Image();
                            patientImg.onload = function() {
                                if (patientSignaturePad) {
                                    patientSignaturePad.clear();
                                    patientSignaturePad.fromDataURL(data.patient_signature);
                                }
                            };
                            patientImg.src = data.patient_signature;
                        }
                        
                        if (data.dentist_signature && typeof dentistSignaturePad !== 'undefined') {
                            // Load dentist signature
                            const dentistImg = new Image();
                            dentistImg.onload = function() {
                                if (dentistSignaturePad) {
                                    dentistSignaturePad.clear();
                                    dentistSignaturePad.fromDataURL(data.dentist_signature);
                                }
                            };
                            dentistImg.src = data.dentist_signature;
                        }
                    }
                }

                async function saveStep(step) {
                    var stepKey = getStepKey(step);
                    var data = collectStepData(stepKey);
                    
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: getStepEndpoint(stepKey),
                            method: 'POST',
                            data: data,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.id) recordIds[stepKey] = response.id;
                                resolve(true);
                            },
                            error: function(xhr) {
                                console.error('Error saving step:', xhr);
                                reject(xhr);
                            }
                        });
                    });
                }
                async function fetchStep(step) {
                    var stepKey = getStepKey(step);
                    if (!recordIds[stepKey]) return;
                    await $.ajax({
                        url: getStepFetchEndpoint(stepKey, recordIds[stepKey]),
                        method: 'GET',
                        success: function(response) {
                            fillStepData(stepKey, response);
                            // If response has an id, store it in recordIds
                            if (response && response.id) {
                                recordIds[stepKey] = response.id;
                            }
                        },
                        error: function(xhr) {
                            // Optionally handle error
                        }
                    });
                }

                $('#next-step').off('click').on('click', async function() {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        await fetchStep(currentStep);
                        showStep(currentStep);
                    } else {
                        console.log('Already at last step, cannot go further');
                    }
                });
                $('#prev-step').off('click').on('click', async function() {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep); // Show the step first
                        await fetchStep(currentStep); // Then fill the data
                    }
                });
                $('#addPatientHistory').off('show.bs.modal').on('show.bs.modal', function() {
                    $('#patient-history-loader').show(); // Show loader at the very start
                    
                    // Check if in edit mode
                    if (editPatientHistoryId) {
                            $('#patient-history-submit')[0].reset();
                            $('#patient_history_id').val(editPatientHistoryId);
                            lastSetPatientHistoryId = editPatientHistoryId;
                            clearRecordIds();
                            currentStep = 1;
                            showStep(currentStep);

                        // Fetch and fill each step's data
                        (async function() {
                            for (let i = 1; i <= totalSteps; i++) {
                                let stepKey = getStepKey(i);
                                let stepData = await $.get(getStepFetchEndpoint(stepKey,
                                    editPatientHistoryId));
                                if (stepData && Object.keys(stepData).length > 0) {
                                    fillStepData(stepKey, stepData);
                                    if (stepData.id) recordIds[stepKey] = stepData
                                        .id;
                                }
                            }
                            $('#patient-history-submit :input').prop('disabled', false);
                            $('#next-step').prop('disabled', false);
                            $('#save-step').prop('disabled', false);
                            editPatientHistoryId = null;
                            $('#patient-history-loader')
                                .hide(); // Hide loader after loading in edit mode
                        })();
                        return;
                    }

                    // Add mode logic
                    $('#patient-history-submit')[0].reset();
                    $('#patient_history_id').val('');
                    currentStep = 1;
                    showStep(currentStep);
                    clearRecordIds();
                    lastSetPatientHistoryId = null;
                    
                    // Auto-fill demographic fields for new patient history
                    if (typeof autoFillDemographics === 'function') {
                        setTimeout(function() {
                            autoFillDemographics();
                        }, 100); // Small delay to ensure form is ready
                    }

                    var encounterId = $('#patient_history_encounter_id').val();
                    var userId = $('#patient_history_user_id').val();

                    // Disable form fields, Save, and Next button until ready
                    $('#patient-history-submit :input').prop('disabled', true);
                    $('#next-step').prop('disabled', true);
                    $('#save-step').prop('disabled', true);

                    // 1. Check if patient history exists for this encounter
                    $.ajax({
                        url: baseUrl + '/app/appointment/patient-history/find-by-encounter/' +
                            encounterId,
                        method: 'GET',
                        success: function(history) {
                            if (history && history.id && history.is_complete == 0) {
                                // Existing incomplete history found, open it for editing
                                $('#patient_history_id').val(history.id);
                                lastSetPatientHistoryId = history.id;
                                (async function() {
                                    let firstIncompleteStep = totalSteps;
                                    for (let i = 1; i <= totalSteps; i++) {
                                        let stepKey = getStepKey(i);
                                        let stepData = await $.get(getStepFetchEndpoint(
                                            stepKey, history.id));
                                        if (stepData && Object.keys(stepData).length > 0) {
                                            fillStepData(stepKey, stepData);
                                            if (stepData.id) recordIds[stepKey] = stepData
                                                .id;
                                        } else {
                                            firstIncompleteStep = i;
                                            break;
                                        }
                                    }
                                    currentStep = firstIncompleteStep;
                                    showStep(currentStep);
                                    $('#patient-history-submit :input').prop('disabled',
                                        false);
                                    $('#next-step').prop('disabled', false);
                                    $('#save-step').prop('disabled', false);
                                    $('#patient-history-loader')
                                        .hide(); // Hide loader after loading in add mode (existing incomplete)
                                })();
                            } else {

                                // No history or last is complete, create new
                                $.ajax({
                                    url: baseUrl +
                                        '/app/appointment/patient-history/create',
                                    method: 'POST',
                                    data: {
                                        user_id: userId,
                                        encounter_id: encounterId,
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        if (response.id) {
                                            $('#patient_history_id').val(response.id);
                                            lastSetPatientHistoryId = response.id;
                                            $('#patient-history-submit :input').prop(
                                                'disabled', false);
                                            $('#next-step').prop('disabled', false);
                                            $('#save-step').prop('disabled', false);
                                            $('#patient-history-loader')
                                                .hide(); // Hide loader after loading in add mode (new)
                                            
                                            // Auto-fill demographic fields with user data for new patient history
                                            if (typeof autoFillDemographics === 'function') {
                                                autoFillDemographics();
                                            }
                                        }
                                    },
                                    error: function(xhr) {
                                        $('#addPatientHistory').modal('hide');
                                        $('#patient-history-loader')
                                            .hide(); // Hide loader on error
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            $('#addPatientHistory').modal('hide');
                            $('#patient-history-submit :input').prop('disabled', false);
                            $('#next-step').prop('disabled', false);
                            $('#save-step').prop('disabled', false);
                            $('#patient-history-loader').hide(); // Hide loader on error
                        }
                    });
                });
                $('#addPatientHistory').on('hidden.bs.modal', function() {
                    $('#patient_history_id').val('');
                    lastSetPatientHistoryId = null;
                    $('#patient-history-submit')[0].reset();
                    clearRecordIds();
                    
                    // Auto-fill demographic fields when form is reset (for new entries)
                    if (typeof autoFillDemographics === 'function') {
                        setTimeout(function() {
                            autoFillDemographics();
                        }, 100); // Small delay to ensure form is reset
                    }
                });
                // Initial step
                showStep(currentStep);

                // Initialize jQuery Validation ONCE on the main form
                var $form = $('#patient-history-submit');
                $form.validate({
                    ignore: ":hidden, [disabled]", // Ignore hidden fields (fields not in current step)
                    errorClass: 'text-danger',
                    errorElement: 'span',
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                    },
                    errorPlacement: function(error, element) {
                        if (element.attr('type') === 'radio' || element.attr('type') === 'checkbox') {
                            var name = element.attr('name').replace(/\[\]$/, '');
                            var $step = element.closest('.step');
                            var $groupError = $step.find('.text-danger.validation-error[data-error-for="' +
                                name + '"]');
                            if ($groupError.length) {
                                $groupError.html(error.text());
                            } else {
                                error.insertAfter(element.closest('.form-group'));
                            }
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    success: function(label, element) {
                        if ($(element).attr('type') === 'radio' || $(element).attr('type') === 'checkbox') {
                            var name = $(element).attr('name').replace(/\[\]$/, '');
                            var $step = $(element).closest('.step');
                            var $groupError = $step.find('.text-danger.validation-error[data-error-for="' +
                                name + '"]');
                            $groupError.html('');
                        } else {
                            $(element).removeClass('is-invalid');
                            $(label).remove();
                        }
                    },
                    rules: {
                        diseases: {
                            required: true
                        },
                        taking_medications: {
                            required: true
                        },
                        known_allergies: {
                            required: true
                        },
                        issues_experienced: {
                            required: true
                        },
                        tmj_status: {
                            required: true
                        },
                        malocclusion_class: {
                            required: true
                        },
                        radiograph_type: {
                            required: true
                        },
                        proposed_treatments: {
                            required: true
                        },
                        // Add other rules as needed
                    },
                    messages: {
                        diseases: {
                            required: "{{ __('patient_history.select_disease') }}"
                        },
                        taking_medications: {
                            required: "{{ __('patient_history.select_medication') }}"
                        },
                        known_allergies: {
                            required: "{{ __('patient_history.select_allergy') }}"
                        },
                        issues_experienced: {
                            required: "{{ __('patient_history.select_issue') }}"
                        },
                        tmj_status: {
                            required: "{{ __('patient_history.select_tmj_status') }}"
                        },
                        malocclusion_class: {
                            required: "{{ __('patient_history.select_malocclusion_class') }}"
                        },
                        radiograph_type: {
                            required: "{{ __('patient_history.select_radiograph_type') }}"
                        },
                        proposed_treatments: {
                            required: "{{ __('patient_history.select_proposed_treatment') }}"
                        },
                        // Add other messages as needed
                    }
                });

                // Clear group error span on radio/checkbox change
                $(document).on('change', 'input[type="radio"], input[type="checkbox"]', function() {
                    var name = $(this).attr('name').replace(/\[\]$/, ''); // remove [] for arrays
                    var $step = $(this).closest('.step');
                    var $errorSpan = $step.find('.text-danger.validation-error[data-error-for="' + name + '"]');
                    $errorSpan.text('');
                });


                // Save button click handler - validates only visible fields in current step
                $('#save-step').off('click').on('click', async function(e) {
                    e.preventDefault();
                    
                    var $visibleFields = $('.step-' + currentStep).find(':input').filter(':visible').not(
                        '[disabled]');
                    var valid = true;
                    $visibleFields.each(function() {
                        if (!$form.validate().element(this)) {
                            valid = false;
                        }
                    });
                    if (!valid) {
                        return;
                    }
                    var phid = $('#patient_history_id').val();
                    if (!phid || isNaN(phid)) {
                        return;
                    }
                    if (lastSetPatientHistoryId === null || String(phid) !== String(
                            lastSetPatientHistoryId)) {
                        return;
                    }
                    
                    // Store the current step before any changes
                    var stepBeingSaved = currentStep;
                    
                    try {
                        await saveStep(currentStep);
                        
                        
                        // Check if this was the final step (step 9 - informed consent)
                        if (stepBeingSaved === 9) {
                            
                            // Double-check that we're actually on the informed consent step
                            var currentStepElement = $('.step-' + stepBeingSaved);
                            if (currentStepElement.length && currentStepElement.find('#patient-signature-pad').length > 0) {
                                
                                // Mark the patient history as complete on last step
                                var patientHistoryId = $('#patient_history_id').val();
                                $.ajax({
                                    url: baseUrl + '/app/appointment/patient-history/mark-complete/' +
                                        patientHistoryId,
                                    method: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        $('#addPatientHistory').modal('hide');
                                        window.location.reload();
                                    },
                                    error: function(xhr) {
                                        console.error('Error marking patient history as complete:', xhr);
                                        window.errorSnackbar(xhr.responseText ||
                                            '{{ __('patient_history.unknown_error') }}');
                                    }
                                });
                            } else {
                                if (currentStep < totalSteps) {
                                    currentStep++;
                                    await fetchStep(currentStep);
                                    showStep(currentStep);
                                }
                            }
                        } else if (stepBeingSaved < 9) {
                            currentStep = stepBeingSaved + 1;
                            await fetchStep(currentStep);
                            showStep(currentStep);
                        } else {
                            console.error('Unexpected step value:', stepBeingSaved, 'Expected 1-9');
                        }
                    } catch (xhr) {
                        console.error('Error in save step:', xhr);
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                // Select both non-array and array field names
                                let $input = $('[name="' + field + '"], [name="' + field + '[]"]');
                                let type = $input.attr('type');
                                let errorMsg = errors[field][0];
                                if (type === 'radio' || type === 'checkbox') {
                                    // Set error message in the group-level span
                                    var $step = $input.closest('.step');
                                    var $errorSpan = $step.find(
                                        '.text-danger.validation-error[data-error-for="' + field + '"]');
                                    $errorSpan.text(errorMsg);
                                } else {
                                    $input.addClass('is-invalid');
                                    $input.next('.text-danger.validation-error').remove();
                                    $input.after('<span class="text-danger validation-error">' + errorMsg +
                                        '</span>');
                                }
                            }
                            // Re-enable fields so user can fix errors
                            $('#patient-history-submit :input').prop('disabled', false);
                            $('#next-step').prop('disabled', false);
                            $('#save-step').prop('disabled', false);
                        } else {
                            window.errorSnackbar(xhr.responseText ||
                                '{{ __('patient_history.unknown_error') }}');
                        }
                    }
                });

                // Prevent form submission - we handle saving via the Save button
                $('#patient-history-submit').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    return false;
                });

                // Allow clicking on stepper steps to navigate
                $('#stepper-nav').on('click', '.stepper-step', async function() {
                    var step = parseInt($(this).data('step'));
                    if (step === currentStep) return; // Already on this step

                    currentStep = step;
                    await fetchStep(currentStep);
                    showStep(currentStep);
                });

                // Add flatpickr to all date fields in the form
                flatpickr('input[type="date"]', {
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });
            });

            // Signature pad functionality
            // COMMENTED OUT - These functions are duplicated in patient_history_form.blade.php
            
            let patientSignaturePad, dentistSignaturePad;
            
            function initializeSignaturePads() {
                // Check if SignaturePad is available
                if (typeof SignaturePad === 'undefined') {
                    console.error('SignaturePad library not loaded');
                    return;
                }
                
                // Initialize patient signature pad
                const patientCanvas = document.getElementById('patient-signature-pad');
                if (patientCanvas) {
                    // Set canvas dimensions properly
                    const rect = patientCanvas.getBoundingClientRect();
                    patientCanvas.width = rect.width;
                    patientCanvas.height = rect.height;
                    
                    try {
                        patientSignaturePad = new SignaturePad(patientCanvas, {
                            backgroundColor: 'rgb(255, 255, 255)',
                            penColor: 'rgb(0, 0, 0)',
                            minWidth: 0.5,
                            maxWidth: 2.5,
                            throttle: 16
                        });
                    } catch (error) {
                        console.error('Error initializing patient signature pad:', error);
                    }
                }
                
                // Initialize dentist signature pad
                const dentistCanvas = document.getElementById('dentist-signature-pad');
                if (dentistCanvas) {
                    // Set canvas dimensions properly
                    const rect = dentistCanvas.getBoundingClientRect();
                    dentistCanvas.width = rect.width;
                    dentistCanvas.height = rect.height;
                    
                    try {
                        dentistSignaturePad = new SignaturePad(dentistCanvas, {
                            backgroundColor: 'rgb(255, 255, 255)',
                            penColor: 'rgb(0, 0, 0)',
                            minWidth: 0.5,
                            maxWidth: 2.5,
                            throttle: 16
                        });
                    } catch (error) {
                        console.error('Error initializing dentist signature pad:', error);
                    }
                }
            }
            
            function clearSignature(type) {
                if (type === 'patient' && patientSignaturePad) {
                    patientSignaturePad.clear();
                    document.getElementById('patient_signature_data').value = '';
                } else if (type === 'dentist' && dentistSignaturePad) {
                    dentistSignaturePad.clear();
                    document.getElementById('dentist_signature_data').value = '';
                }
            }
            
            function saveSignature(type, event) {
                let signaturePad, dataField;
                
                if (type === 'patient') {
                    signaturePad = patientSignaturePad;
                    dataField = 'patient_signature_data';
                } else if (type === 'dentist') {
                    signaturePad = dentistSignaturePad;
                    dataField = 'dentist_signature_data';
                }
                
                if (signaturePad && !signaturePad.isEmpty()) {
                    // Save as JPEG like in DoctorOffcanvas.vue
                    const signatureData = signaturePad.toDataURL('image/jpeg');
                    document.getElementById(dataField).value = signatureData;
                    
                    // Button feedback
                    const button = event.target;
                    const originalText = button.textContent;
                    button.textContent = 'Saved!';
                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-success');
                    
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-outline-primary');
                    }, 2000);
                } else {
                    alert('Please draw a signature first');
                }
            }
            
            // Function to initialize signature pads when step 9 is shown
            function initializeSignaturePadsForStep9() {
                setTimeout(() => {
                    initializeSignaturePads();
                    
                    // Load existing signatures if editing
                    const patientHistoryId = $('#patient_history_id').val();
                    if (patientHistoryId) {
                        loadExistingSignatures();
                    }
                }, 300);
            }
            
            // Function to load existing signatures from hidden inputs
            function loadExistingSignatures() {
                const patientSignatureData = $('#patient_signature_data').val();
                const dentistSignatureData = $('#dentist_signature_data').val();
                
                if (patientSignatureData && patientSignatureData.trim() !== '' && typeof patientSignaturePad !== 'undefined') {
                    try {
                        patientSignaturePad.clear();
                        patientSignaturePad.fromDataURL(patientSignatureData);
                    } catch (e) {
                        console.error('Could not load patient signature:', e);
                    }
                }
                
                if (dentistSignatureData && dentistSignatureData.trim() !== '' && typeof dentistSignaturePad !== 'undefined') {
                    try {
                        dentistSignaturePad.clear();
                        dentistSignaturePad.fromDataURL(dentistSignatureData);
                    } catch (e) {
                        console.error('Could not load dentist signature:', e);
                    }
                }
            }
            
            // Initialize signature pads when modal is shown
            document.addEventListener('shown.bs.modal', function (event) {
                if (event.target.id === 'addPatientHistory') {
                    setTimeout(() => {
                        initializeSignaturePads();
                    }, 300);
                }
            });
            

            function DownloadOrthoDailyRecordsPDF(id) {
                const baseUrl = '{{ url('/') }}'; // Base URL of your application
                const downloadUrl = `${baseUrl}/app/encounter/download-orthodontic-treatment-daily-records?id=${id}`;
                window.open(downloadUrl);
            }

            // Dental Chart Helper Functions
            // COMMENTED OUT - These functions are duplicated in patient_history_form.blade.php
            /*
            function fillDentalChartData(data) {
                
                // Clear any existing selections first
                clearDentalChartSelections();
                
                // Parse the dental chart data
                let upperJawData = {};
                let lowerJawData = {};
                
                try {
                    if (data.upper_jaw_treatment && typeof data.upper_jaw_treatment === 'string') {
                        upperJawData = JSON.parse(data.upper_jaw_treatment);
                    }
                    if (data.lower_jaw_treatment && typeof data.lower_jaw_treatment === 'string') {
                        lowerJawData = JSON.parse(data.lower_jaw_treatment);
                    }
                } catch (e) {
                    console.error('Error parsing dental chart data:', e);
                    return;
                }
                
                
                // Apply the saved data to the dental chart
                applyDentalChartSelections(upperJawData, lowerJawData);
                
                // Update the hidden inputs
                updateDentalChartData();
                
            }
            
            function clearDentalChartSelections() {
                // Clear all tooth selections
                $('.tooth-number-container').each(function() {
                    const svg = $(this).find('.tooth-svg');
                    svg.css({
                        'backgroundColor': '',
                        'borderColor': ''
                    });
                    const checkbox = $(this).find('input[type="checkbox"]');
                    checkbox.prop('checked', false);
                });
                
                // Clear category lists
                $('.tooth-list').empty();
                
                // Reset the selectedTeeth object (this will be recreated in the dental chart script)
                if (typeof selectedTeeth !== 'undefined') {
                    Object.keys(selectedTeeth).forEach(category => {
                        selectedTeeth[category].clear();
                    });
                }
            }
            
            function applyDentalChartSelections(upperJawData, lowerJawData) {
                // Process upper jaw data
                Object.keys(upperJawData).forEach(condition => {
                    const teeth = upperJawData[condition];
                    if (Array.isArray(teeth)) {
                        teeth.forEach(toothId => {
                            applyToothSelection(toothId, condition.replace('mark_', ''));
                        });
                    }
                });
                
                // Process lower jaw data
                Object.keys(lowerJawData).forEach(condition => {
                    const teeth = lowerJawData[condition];
                    if (Array.isArray(teeth)) {
                        teeth.forEach(toothId => {
                            applyToothSelection(toothId, condition.replace('mark_', ''));
                        });
                    }
                });
                
                // Update the category lists
                updateCategoryLists();
            }
            
            function applyToothSelection(toothId, condition) {
                // Find the tooth container
                const container = $(`#adult-upper-tooth-${toothId}, #adult-lower-tooth-${toothId}, #child-upper-tooth-${toothId}, #child-lower-tooth-${toothId}`);
                
                if (container.length > 0) {
                    const svg = container.find('.tooth-svg');
                    const checkbox = container.find('input[type="checkbox"]');
                    
                    // Apply the visual styling
                    applyToothAppearance(svg[0], condition, true);
                    
                    // Check the checkbox
                    checkbox.prop('checked', true);
                    
                    // Add to the selectedTeeth object if it exists
                    if (typeof selectedTeeth !== 'undefined' && selectedTeeth[condition]) {
                        selectedTeeth[condition].add(toothId);
                    }
                }
            }
            
            function applyToothAppearance(svg, action, isActive) {
                if (!isActive) {
                    svg.style.backgroundColor = '';
                    svg.style.borderColor = '';
                    return;
                }

                switch(action) {
                    case 'cavity':
                        svg.style.backgroundColor = 'rgba(255, 68, 68, 0.3)';
                        svg.style.borderColor = '#ff4444';
                        break;
                    case 'crown':
                        svg.style.backgroundColor = 'rgba(66, 133, 244, 0.3)';
                        svg.style.borderColor = '#4285f4';
                        break;
                    case 'missing':
                        svg.style.backgroundColor = 'rgba(255, 187, 51, 0.3)';
                        svg.style.borderColor = '#ffbb33';
                        break;
                    case 'retained':
                        svg.style.backgroundColor = 'rgba(0, 200, 81, 0.3)';
                        svg.style.borderColor = '#00c851';
                        break;
                }
            }
            */
        </script>
    @endpush
