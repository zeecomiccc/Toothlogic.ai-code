<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" 
    rel="stylesheet"> --}}

    <title>{{ __('patient_history.patient_history_summary') }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 10px;
        }

        /* 🔄 Updated Header */
        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .logo-clinic-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            flex: 0 0 auto;
        }

        .logo {
            height: 65px;
            width: auto;
            display: block;
        }

        .clinic-info-text {
            text-align: right;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .main-content {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .patient-receipt-wrapper {
            display: flex;
            gap: 20px;
            width: 100%;
        }

        .left-section,
        .right-section {
            flex: 1;
        }

        .patient-info {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background: #f5f5f5;
        }

        .patient-info h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .patient-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .patient-details div {
            display: flex;
            justify-content: space-between;
        }

        .receipt-details {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background: #f5f5f5;
        }

        .receipt-details h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .receipt-details div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .services-table th,
        .services-table td,
        {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        }

        .services-table th,
        .installments-table th,
        {
        background: #00C2CB;
        color: white;
        font-weight: bold;
        text-align: center;
        }

        .services-table .text-right {
            text-align: right;
        }

        .services-table .text-center {
            text-align: center;
        }

        .notes-section {
            margin-bottom: 20px;
        }

        .notes-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .notes-box {
            border: 1px solid #ddd;
            padding: 10px;
            min-height: 60px;
            background: #f5f5f5;
        }

        .summary-section {
            border: 1px solid #ddd;
            padding: 15px;
            background: #f5f5f5;
        }

        .summary-section h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 3px 0;
        }

        .summary-row.total {
            border-top: 2px solid #333;
            padding-top: 8px;
            font-weight: bold;
            font-size: 13px;
        }

        .summary-row.paid {
            color: #28a745;
            font-weight: bold;
        }

        .summary-row.unpaid {
            color: #dc3545;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        /* Patient History Specific Styles */
        .history-section {
            margin-bottom: 25px;
        }

        .history-section h4 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            font-weight: bold;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .history-table th,
        .history-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .history-table th {
            background: #00C2CB;
            color: white;
            font-weight: bold;
            width: 30%;
        }

        .history-table td {
            background: #f9f9f9;
        }

        .consent-section {
            margin-top: 30px;
            padding: 20px;
            border: 2px solid #00C2CB;
            background: #f5f5f5;
        }

        .consent-section h4 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }

        .signature-line {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .signature-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .signature-line hr {
            width: 150px;
            border: none;
            border-top: 1px solid #333;
            margin: 5px 0;
        }

        @media print {
            .container {
                max-width: none;
                margin: 0;
                padding: 0;
            }

            body {
                font-size: 11px;
            }
        }

        /* Dental Chart Styles for Print */
        .dental-chart {
            display: flex;
            flex-direction: column;
            gap: 30px;
            margin: 20px 0;
        }

        .dental-chart .tooth-number-container {
            position: relative;
            width: 30px;
            height: 30px;
            margin: 5px;
        }

        .dental-chart .tooth-svg {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: white;
            border: 2px solid #989898;
            position: relative;
            transition: all 0.3s ease;
        }

        /* Add cross lines */
        .dental-chart .tooth-svg::before,
        .dental-chart .tooth-svg::after {
            content: "";
            position: absolute;
            background: #989898;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .dental-chart .tooth-svg::before {
            width: 2px;
            height: 100%;
        }

        .dental-chart .tooth-svg::after {
            width: 100%;
            height: 2px;
        }

        /* Inner circle */
        .dental-chart .tooth-svg-inner {
            position: absolute;
            width: 60%;
            height: 60%;
            border-radius: 50%;
            background: white;
            border: 1px solid #989898;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            padding: 1px;
        }

        .adult-upper-stack,
        .adult-bottom-stack {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .upper-layer-box,
        .bottom-layer-box {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
        }

        .adult-upper-tooth,
        .adult-bottom-tooth {
            max-width: 350px;
            width: 100%;
        }

        .adult-upper-tooth> :nth-child(1) {
            width: max-content;
            margin: auto;
        }

        .adult-upper-tooth> :nth-child(2) {
            width: 44%;
            margin: -30px auto 0;
        }

        .adult-upper-tooth> :nth-child(3) {
            width: 64%;
            margin: -14px auto 0;
        }

        .adult-upper-tooth> :nth-child(4) {
            width: 80%;
            margin: -12px auto 0;
        }

        .adult-upper-tooth> :nth-child(5) {
            width: 90%;
            margin: -5px auto 0;
        }

        .adult-upper-tooth> :nth-child(6) {
            width: 97%;
            margin: -5px auto 0;
        }

        .adult-upper-tooth> :nth-child(7) {
            width: 100%;
            margin: -5px auto 0;
        }

        .adult-bottom-tooth> :nth-child(8) {
            width: max-content;
            margin: -30px auto 0;
        }

        .adult-bottom-tooth> :nth-child(7) {
            width: 44%;
            margin: -20px auto 0;
        }

        .adult-bottom-tooth> :nth-child(6) {
            width: 64%;
            margin: -12px auto 0;
        }

        .adult-bottom-tooth> :nth-child(5) {
            width: 80%;
            margin: -5px auto 0;
        }

        .adult-bottom-tooth> :nth-child(4) {
            width: 90%;
            margin: -2px auto 0;
        }

        .adult-bottom-tooth> :nth-child(3) {
            width: 95%;
            margin: -2px auto 0;
        }

        .adult-bottom-tooth> :nth-child(2) {
            width: 98%;
            margin: -02px auto 0;
        }

        .adult-bottom-tooth> :nth-child(1) {
            width: 100%;
            margin: -2px auto 0;
        }

        .child-upper-tooth {
            max-width: 250px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .child-upper-stack,
        .child-bottom-stack {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .child-upper-tooth> :nth-child(1) {
            width: max-content;
            margin: 0 auto -0px;
        }

        .child-upper-tooth> :nth-child(2) {
            width: 62%;
            margin: -28px auto 0;
        }

        .child-upper-tooth> :nth-child(3) {
            width: 85%;
            margin: -15px auto 0;
        }

        .child-upper-tooth> :nth-child(4) {
            width: 98%;
            margin: -5px auto 0;
        }

        .child-upper-tooth> :nth-child(5) {
            width: 100%;
            margin: -5px auto 0;
        }

        .child-bottom-tooth {
            max-width: 250px;
            width: 100%;
            position: absolute;
            top: 0;
        }

        .child-bottom-tooth> :nth-child(1) {
            width: 100%;
            margin: auto;
        }

        .child-bottom-tooth> :nth-child(2) {
            width: 98%;
            margin: -2px auto 0;
        }

        .child-bottom-tooth> :nth-child(3) {
            width: 85%;
            margin: -4px auto 0;
        }

        .child-bottom-tooth> :nth-child(4) {
            width: 62%;
            margin: -10px auto 0;
        }

        .child-bottom-tooth> :nth-child(5) {
            width: max-content;
            margin: -30px auto -0px;
        }

        /* @media print { */
            .dental-chart {
                gap: 20px;
                align-items: center;
            }

            .upper-layer-box,
            .bottom-layer-box {
                transform: scale(0.9);
                max-width: unset;
                width: 320px;
            }
        /* } */

        /* Download button styles */
        #downloadBtn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 20px auto;
            display: block;
        }
        
        #downloadBtn:hover {
            background: #0056b3;
        }
        
        /* Hide download button when printing */
        @media print {
            #downloadBtn {
                display: none;
            }
        }
    </style>
</head>

<body>
    @php
        function displayList($value)
        {
            if (is_null($value) || $value === '') {
                return '';
            }
            if (is_array($value)) {
                return implode(', ', $value);
            }
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return implode(', ', $decoded);
                }
                return $value;
            }
            return (string) $value;
        }
    @endphp

    <div id="pdf-content">
        <div class="container">
            <!-- Header Section -->
            <div style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #333;">
                @php
                    $imagePath = public_path('img/logo/invoice_logo.png');
                    if (file_exists($imagePath)) {
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $site_logo = 'data:image/png;base64,' . $imageData;
                    } else {
                        $site_logo = '';
                    }
                @endphp

                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <!-- Logo (Left) -->
                        <td width="50%" valign="top">
                            @if(isset($history->encounter->clinic->brand_mark_url) && !empty($history->encounter->clinic->brand_mark_url))
                                <img src="{{ $history->encounter->clinic->brand_mark_url }}" alt="Clinic Brand Mark" style="height: 75px; width: auto;">
                            @else
                                <img src="{{ $site_logo }}" alt="logo" style="height: 100px; width: auto;">
                            @endif
                        </td>

                        <!-- Clinic Info (Right) -->
                        <td width="50%" valign="top"
                            style="text-align: right; font-size: 12px; line-height: 1.6; color: #333;">
                            <strong>{{ $history->encounter->clinic->name ?? '--' }}</strong><br>
                            {{ __('patient_history.phone') }}: {{ $history->encounter->clinic->contact_number ?? '--' }}<br>
                            {{ __('patient_history.email') }}: {{ $history->encounter->clinic->email ?? '--' }}<br>
                            {{ $history->encounter->clinic->address ?? '' }}<br>
                            {{ $history->encounter->clinic->city ?? '' }},
                            {{ $history->encounter->clinic->state ?? '' }},
                            {{ $history->encounter->clinic->pincode ?? '' }},
                            {{ $history->encounter->clinic->country ?? '' }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <div style="width: 100%;">
                    <h2 style="text-align: center; color: #00C2CB; margin-bottom: 25px; font-size: 20px; padding-bottom: 10px;">
                        {{ __('patient_history.patient_history_summary') }}
                    </h2>

                    <!-- I. Demographic Information -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.patient_demographic_info') }}</h4>
                        <table class="history-table">
                            <tbody>
                                <tr>
                                    <th>{{ __('patient_history.full_name') }}</th>
                                    <td>{{ $history->demographic->full_name ?? ($history->encounter->user->first_name ?? '') . ' ' . ($history->encounter->user->last_name ?? '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('patient_history.date_of_birth') }}</th>
                                    <td>{{ $history->demographic->dob ?? ($history->encounter->user->date_of_birth ? date('Y-m-d', strtotime($history->encounter->user->date_of_birth)) : '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('patient_history.gender') }}</th>
                                    <td>{{ $history->demographic->gender ?? ($history->encounter->user->gender ?? '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('patient_history.occupation') }}</th>
                                    <td>{{ $history->demographic->occupation ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('patient_history.address') }}</th>
                                    <td>{{ $history->demographic->address ?? ($history->encounter->user->address ?? '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('patient_history.phone') }}</th>
                                    <td>{{ $history->demographic->phone ?? ($history->encounter->user->mobile ?? '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('patient_history.email') }}</th>
                                    <td>{{ $history->demographic->email ?? ($history->encounter->user->email ?? '') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('patient_history.emergency_contact') }}</th>
                                    <td>{{ ($history->demographic->emergency_contact_name ?? '') .
                                        ' ' .
                                        ($history->demographic->emergency_contact_phone ?? '') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- II. Medical History -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.medical_history') }}</h4>
                        @if ($history->medicalHistory)
                            <table class="history-table">
                                <tbody>
                                    <tr>
                                        <th>{{ __('patient_history.under_medical_treatment') }}</th>
                                        <td>{{ $history->medicalHistory->under_medical_treatment ? __('patient_history.yes') : __('patient_history.no') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.treatment_details') }}</th>
                                        <td>{{ $history->medicalHistory->treatment_details }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.hospitalized_last_year') }}</th>
                                        <td>{{ $history->medicalHistory->hospitalized_last_year ? __('patient_history.yes') : __('patient_history.no') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.hospitalization_reason') }}</th>
                                        <td>{{ $history->medicalHistory->hospitalization_reason }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.diseases') }}</th>
                                        <td>{{ displayList($history->medicalHistory->diseases ?? null) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.pregnant_or_breastfeeding') }}</th>
                                        <td>{{ $history->medicalHistory->pregnant_or_breastfeeding }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.taking_medications') }}</th>
                                        <td>{{ displayList($history->medicalHistory->taking_medications ?? null) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.known_allergies') }}</th>
                                        <td>{{ displayList($history->medicalHistory->known_allergies ?? null) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <em>{{ __('patient_history.no_medical_history_recorded') }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- III. Dental History -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.dental_history') }}</h4>
                        @if ($history->dentalHistory)
                            <table class="history-table">
                                <tbody>
                                    <tr>
                                        <th>{{ __('patient_history.last_dental_visit') }}</th>
                                        <td>{{ $history->dentalHistory->last_dental_visit_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.reason_for_last_visit') }}</th>
                                        <td>{{ $history->dentalHistory->reason_for_last_visit }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.issues_experienced') }}</th>
                                        <td>{{ displayList($history->dentalHistory->issues_experienced ?? null) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.dental_anxiety_level') }}</th>
                                        <td>{{ $history->dentalHistory->dental_anxiety_level }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <em>{{ __('patient_history.no_dental_history_recorded') }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- IV. Chief Complaint -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.chief_complaint') }}</h4>
                        @if ($history->chiefComplaint)
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <strong>{{ __('patient_history.notes') }}:</strong> {{ $history->chiefComplaint->complaint_notes }}
                            </div>
                        @else
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <em>{{ __('patient_history.no_chief_complaint_recorded') }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- V. Clinical Examination -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.clinical_exam') }}</h4>
                        @if ($history->clinicalExamination)
                            <table class="history-table">
                                <tbody>
                                    <tr>
                                        <th>{{ __('patient_history.face_symmetry') }}</th>
                                        <td>{{ $history->clinicalExamination->face_symmetry }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.tmj_status') }}</th>
                                        <td>{{ displayList($history->clinicalExamination->tmj_status ?? null) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.soft_tissue_status') }}</th>
                                        <td>{{ $history->clinicalExamination->soft_tissue_status }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.teeth_status') }}</th>
                                        <td>{{ $history->clinicalExamination->teeth_status }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.gingival_health') }}</th>
                                        <td>{{ $history->clinicalExamination->gingival_health }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.bleeding_on_probing') }}</th>
                                        <td>{{ $history->clinicalExamination->bleeding_on_probing ? __('patient_history.yes') : __('patient_history.no') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.pocket_depths') }}</th>
                                        <td>{{ $history->clinicalExamination->pocket_depths }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.mobility') }}</th>
                                        <td>{{ $history->clinicalExamination->mobility ? __('patient_history.present') : __('patient_history.absent') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.malocclusion_class') }}</th>
                                        <td>{{ displayList($history->clinicalExamination->malocclusion_class ?? null) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.bruxism') }}</th>
                                        <td>{{ $history->clinicalExamination->bruxism ? __('patient_history.yes') : __('patient_history.no') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <em>{{ __('patient_history.no_clinical_examination_recorded') }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- VI. Radiographic Examination -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.radiographic_examination') }}</h4>
                        @if ($history->radiographicExamination)
                            <table class="history-table">
                                <tbody>
                                    <tr>
                                        <th>{{ __('patient_history.radiograph_type') }}</th>
                                        <td>{{ displayList($history->radiographicExamination->radiograph_type ?? null) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.findings') }}</th>
                                        <td>{{ $history->radiographicExamination->radiograph_findings }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <em>{{ __('patient_history.no_radiographic_examination_recorded') }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- VII. Diagnosis & Treatment Plan -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.diagnosis_plan') }}</h4>
                        @if ($history->diagnosisAndPlan)
                            <table class="history-table">
                                <tbody>
                                    <tr>
                                        <th>{{ __('patient_history.diagnosis') }}</th>
                                        <td>{{ $history->diagnosisAndPlan->diagnosis }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.proposed_treatments') }}</th>
                                        <td>{{ displayList($history->diagnosisAndPlan->proposed_treatments ?? null) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.planned_timeline') }}</th>
                                        <td>{{ $history->diagnosisAndPlan->planned_timeline }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.alternatives_discussed') }}</th>
                                        <td>{{ $history->diagnosisAndPlan->alternatives_discussed ? __('patient_history.yes') : __('patient_history.no') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.risks_explained') }}</th>
                                        <td>{{ $history->diagnosisAndPlan->risks_explained ? __('patient_history.yes') : __('patient_history.no') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patient_history.questions_addressed') }}</th>
                                        <td>{{ $history->diagnosisAndPlan->questions_addressed ? __('patient_history.yes') : __('patient_history.no') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <em>{{ __('patient_history.no_diagnosis_and_treatment_plan_recorded') }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- VIII. Dental Chart -->
                    <div class="history-section">
                        <h4>{{ __('patient_history.dental_chart') }}</h4>
                        @if ($history->jawTreatment)
                            <!-- Dental Chart Legend -->
                            <div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
                                <h5 style="margin: 0 0 15px 0; color: #00C2CB; font-size: 14px;">{{ __('patient_history.treatment') }}</h5>
                                <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 20px; height: 20px; border-radius: 50%; background: rgba(255, 68, 68, 0.3); border: 2px solid #ff4444;"></div>
                                        <span style="font-size: 12px;">{{ __('patient_history.cavity') }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 20px; height: 20px; border-radius: 50%; background: rgba(66, 133, 244, 0.3); border: 2px solid #4285f4;"></div>
                                        <span style="font-size: 12px;">{{ __('patient_history.crown_restored') }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 20px; height: 20px; border-radius: 50%; background: rgba(255, 187, 51, 0.3); border: 2px solid #ffbb33;"></div>
                                        <span style="font-size: 12px;">{{ __('patient_history.missing') }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 20px; height: 20px; border-radius: 50%; background: rgba(0, 200, 81, 0.3); border: 2px solid #00c851;"></div>
                                        <span style="font-size: 12px;">{{ __('patient_history.retained') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Dental Chart -->
                            <div class="dental-chart" id="dentalChart" style="margin-bottom: 20px;">
                                <div class="upper-layer-box">
                                    <div class="adult-upper-tooth">
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="8">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">8</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="9">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">9</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="7">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">7</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="10">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">10</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="6">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">6</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="11">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">11</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="5">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">5</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="12">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">12</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="4">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">4</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="13">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">13</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="3">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">3</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="14">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">14</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="2">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">2</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="15">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">15</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-upper-stack">
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="1">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">1</div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-tooth-number tooth-number-container" data-tooth="16">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">16</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="child-upper-tooth">
                                        <div class="child-upper-stack">
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="e">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">E</div>
                                                </div>
                                            </div>
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="f">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">F</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-upper-stack">
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="d">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">D</div>
                                                </div>
                                            </div>
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="g">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">G</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-upper-stack">
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="c">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">C</div>
                                                </div>
                                            </div>
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="h">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">H</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-upper-stack">
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="b">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">B</div>
                                                </div>
                                            </div>
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="i">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">I</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-upper-stack">
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="a">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">A</div>
                                                </div>
                                            </div>
                                            <div class="child-upper-tooth-number tooth-number-container" data-tooth="j">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">J</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottom-layer-box">
                                    <div class="child-bottom-tooth">
                                        <div class="child-bottom-stack">
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="t">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">T</div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="k">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">K</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-bottom-stack">
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="s">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">S</div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="l">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">L</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-bottom-stack">
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="r">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">R</div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="m">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">M</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-bottom-stack">
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="q">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">Q</div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="n">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">N</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-bottom-stack">
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="p">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">P</div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-tooth-number tooth-number-container" data-tooth="o">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">O</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adult-bottom-tooth">
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="32">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">32</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="17">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">17</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="31">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">31</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="18">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">18</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="30">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">30</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="19">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">19</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="29">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">29</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="20">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">20</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="28">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">28</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="21">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">21</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="27">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">27</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="22">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">22</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="26">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">26</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="23">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">23</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-stack">
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="25">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">25</div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-tooth-number tooth-number-container" data-tooth="24">
                                                <div class="tooth-svg">
                                                    <div class="tooth-svg-inner">24</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Treatment Summary -->
                            <div style="margin-top: 20px;">
                                <table class="history-table">
                                    <tbody>
                                        <tr>
                                            <th>{{ __('patient_history.upper_jaw_treatment') }}</th>
                                            <td>
                                                @if ($history->jawTreatment->upper_jaw_treatment)
                                                    @php
                                                        $upperData = json_decode($history->jawTreatment->upper_jaw_treatment, true);
                                                        $upperTreatments = [];
                                                        
                                                        if (is_array($upperData)) {
                                                            if (isset($upperData['mark_cavity']) && is_array($upperData['mark_cavity'])) {
                                                                $upperTreatments[] = __('patient_history.cavity') . ': ' . implode(', ', $upperData['mark_cavity']);
                                                            }
                                                            if (isset($upperData['mark_crown']) && is_array($upperData['mark_crown'])) {
                                                                $upperTreatments[] = __('patient_history.crown_restored') . ': ' . implode(', ', $upperData['mark_crown']);
                                                            }
                                                            if (isset($upperData['mark_missing']) && is_array($upperData['mark_missing'])) {
                                                                $upperTreatments[] = __('patient_history.missing') . ': ' . implode(', ', $upperData['mark_missing']);
                                                            }
                                                            if (isset($upperData['mark_retained']) && is_array($upperData['mark_retained'])) {
                                                                $upperTreatments[] = __('patient_history.retained') . ': ' . implode(', ', $upperData['mark_retained']);
                                                            }
                                                        }
                                                        
                                                        if (empty($upperTreatments)) {
                                                            echo __('patient_history.no_treatments_recorded');
                                                        } else {
                                                            echo implode('; ', $upperTreatments);
                                                        }
                                                    @endphp
                                                @else
                                                    {{ __('patient_history.no_treatments_recorded') }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patient_history.lower_jaw_treatment') }}</th>
                                            <td>
                                                @if ($history->jawTreatment->lower_jaw_treatment)
                                                    @php
                                                        $lowerData = json_decode($history->jawTreatment->lower_jaw_treatment, true);
                                                        $lowerTreatments = [];
                                                        
                                                        if (is_array($lowerData)) {
                                                            if (isset($lowerData['mark_cavity']) && is_array($lowerData['mark_cavity'])) {
                                                                $lowerTreatments[] = __('patient_history.cavity') . ': ' . implode(', ', $lowerData['mark_cavity']);
                                                            }
                                                            if (isset($lowerData['mark_crown']) && is_array($lowerData['mark_crown'])) {
                                                                $lowerTreatments[] = __('patient_history.crown_restored') . ': ' . implode(', ', $lowerData['mark_crown']);
                                                            }
                                                            if (isset($lowerData['mark_missing']) && is_array($lowerData['mark_missing'])) {
                                                                $lowerTreatments[] = __('patient_history.missing') . ': ' . implode(', ', $lowerData['mark_missing']);
                                                            }
                                                            if (isset($lowerData['mark_retained']) && is_array($lowerData['mark_retained'])) {
                                                                $lowerTreatments[] = __('patient_history.retained') . ': ' . implode(', ', $lowerData['mark_retained']);
                                                            }
                                                        }
                                                        
                                                        if (empty($lowerTreatments)) {
                                                            echo __('patient_history.no_treatments_recorded');
                                                        } else {
                                                            echo implode('; ', $lowerTreatments);
                                                        }
                                                    @endphp
                                                @else
                                                    {{ __('patient_history.no_treatments_recorded') }}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <script>
                                // Parse dental chart data and apply colors
                                document.addEventListener('DOMContentLoaded', function() {
                                    @if ($history->jawTreatment && ($history->jawTreatment->upper_jaw_treatment || $history->jawTreatment->lower_jaw_treatment))
                                        const upperJawData = @json($history->jawTreatment->upper_jaw_treatment ? json_decode($history->jawTreatment->upper_jaw_treatment, true) : []);
                                        const lowerJawData = @json($history->jawTreatment->lower_jaw_treatment ? json_decode($history->jawTreatment->lower_jaw_treatment, true) : []);
                                        
                                        // Apply colors to teeth based on database data
                                        applyDentalChartColors(upperJawData, lowerJawData);
                                    @endif
                                });

                                function applyDentalChartColors(upperJawData, lowerJawData) {
                                    // Process upper jaw data
                                    Object.keys(upperJawData).forEach(category => {
                                        if (category.startsWith('mark_')) {
                                            const treatmentType = category.replace('mark_', '');
                                            const teeth = upperJawData[category];
                                            
                                            if (Array.isArray(teeth)) {
                                                teeth.forEach(toothId => {
                                                    const toothElement = document.querySelector(`[data-tooth="${toothId.toLowerCase()}"]`);
                                                    if (toothElement) {
                                                        const svg = toothElement.querySelector('.tooth-svg');
                                                        applyToothColor(svg, treatmentType);
                                                    }
                                                });
                                            }
                                        }
                                    });

                                    // Process lower jaw data
                                    Object.keys(lowerJawData).forEach(category => {
                                        if (category.startsWith('mark_')) {
                                            const treatmentType = category.replace('mark_', '');
                                            const teeth = lowerJawData[category];
                                            
                                            if (Array.isArray(teeth)) {
                                                teeth.forEach(toothId => {
                                                    const toothElement = document.querySelector(`[data-tooth="${toothId.toLowerCase()}"]`);
                                                    if (toothElement) {
                                                        const svg = toothElement.querySelector('.tooth-svg');
                                                        applyToothColor(svg, treatmentType);
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }

                                function applyToothColor(svg, treatmentType) {
                                    switch(treatmentType) {
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
                            </script>
                        @else
                            <div style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                <em>{{ __('patient_history.no_dental_chart_recorded') }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- Informed Consent Section -->
                    <div class="consent-section" style="border:1px solid #00C2CB; padding:15px; margin-top:10px;">
                        <h4 style="color:#00C2CB; margin-bottom:10px;">
                            {{ __('patient_history.informed_consent') }}
                        </h4>
                        <p style="line-height:1.6; margin-bottom:15px;">
                            @php
                                $patientName = $history->demographic->full_name ?? 
                                              ($history->encounter->user->first_name ?? '') . ' ' . ($history->encounter->user->last_name ?? '') ?? 
                                              'Patient Name';
                            @endphp
                            {{ __('patient_history.consent_text', ['patient_name' => $patientName]) }}
                        </p>

                        <!-- Signatures -->
                        <table style="width:100%; border-collapse:collapse; margin-top:5px;">
                            <tr>
                                <td style="width:50%; vertical-align:bottom; padding-right:10px;">
                                    @if ($history->informedConsent && $history->informedConsent->patient_signature)
                                        <div style="border-bottom:1px solid #333; height:40px; display:flex; align-items:center; justify-content:flex-start;">
                                            <img src="{{ $history->informedConsent->patient_signature }}" 
                                                 alt="{{ __('patient_history.patient_signature') }}" 
                                                 style="max-height:35px; max-width:120px; object-fit:contain;">
                                        </div>
                                    @else
                                        <div style="border-bottom:1px solid #333; height:40px; width:80px; margin:0;"></div>
                                    @endif
                                    <div style="margin-top:8px; text-align:left;">
                                        <span style="font-weight:bold;">{{ __('patient_history.patient_signature') }}</span>
                                    </div>
                                </td>
                                <td style="width:50%; vertical-align:bottom; text-align:right; padding-left:10px;">
                                    @if ($history->informedConsent && $history->informedConsent->dentist_signature)
                                        <div style="border-bottom:1px solid #333; height:40px; display:flex; align-items:center; justify-content:center;">
                                            <img src="{{ $history->informedConsent->dentist_signature }}" 
                                                 alt="{{ __('patient_history.dentist_signature') }}" 
                                                 style="max-height:35px; max-width:120px; object-fit:contain;">
                                        </div>
                                    @else
                                        <div style="border-bottom:1px solid #333; height:40px; width:80px; margin-left:auto;"></div>
                                    @endif
                                    <div style="margin-top:8px; text-align:right;">
                                        <span style="font-weight:bold;">{{ __('patient_history.dentist_signature') }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- Date -->
                        <table style="width:100%; border-collapse:collapse; margin-top:5px;">
                            <tr>
                                <td style="width:30%; vertical-align:bottom; padding-right:10px;">
                                    <span style="font-weight:bold;">{{ __('patient_history.date') }}: ___________________</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>
                    @if (config('app.name'))
                        <div style="margin-top: 5px; font-size: 14px; font-weight: bold; color: #00C2CB;">
                            {{ config('app.name') }}
                        </div>
                    @endif
                </p>
            </div>
        </div>
    </div>
</body>
</html>
