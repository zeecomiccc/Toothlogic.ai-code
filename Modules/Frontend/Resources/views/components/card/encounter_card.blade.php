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
    
    .encounter-section .table-striped > tbody > tr:nth-of-type(odd) > td {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .encounter-section .table-hover > tbody > tr:hover > td {
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
<div class="col">
  <div class="encounters-card section-bg rounded p-4">
      <div class="d-flex justify-content-between align-items-center gap-3">
          <p class=" bg-primary-subtle m-0 px-3 py-1 rounded-pill fw-semibold font-size-12 ">{{ DateFormate($encounter->updated_at) }}</p>
          <span class="text-uppercase text-success fw-bold font-size-12 ">{{ $encounter->status ? 'Active' : 'Close' }}</span>
      </div>
      <div class="my-3 py-1">
          <div class="row gy-2">
              <div class="col-md-4 col-5 pe-0">
                  <p class="mb-0 font-size-14">Appointment ID:</p>
              </div>
              <div class="col-md-8 col-7">
                  <h6 class="mb-0 text-primary">#{{ $encounter->appointment_id }}</h6>
              </div>
              <div class="col-md-4 col-5 pe-0">
                  <p class="mb-0 font-size-14">Doctor Name:</p>
              </div>
              <div class="col-md-8 col-7">
                  <h6 class="mb-0 line-count-1">Dr. {{ optional($encounter->doctor)->first_name . ' ' . optional($encounter->doctor)->last_name ?? '-' }}</h6>
              </div>
              <div class="col-md-4 col-5 pe-0">
                  <p class="mb-0 font-size-14">Clinic Name:</p>
              </div>
              <div class="col-md-8 col-7">
                  <h6 class="mb-0 line-count-2">{{ optional($encounter->clinic)->name ?? '-' }}</h6>
              </div>
          </div>
      </div>
      @if($encounter->description)
        <div class="desc border-top">
            <h6 class="mb-1 fw-normal font-size-14">Description:</h6>
            <p class="mb-5">{{ $encounter->description }}</p>
        </div>
      @endif
      <a data-bs-toggle="modal" data-bs-target="#encounter-details-view-{{ $encounter->id }}"
          class="font-size-12 fw-semibold text-secondary">View Detail</a>
  </div>
</div>


<div class="modal fade" id="encounter-details-view-{{ $encounter->id }}">
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
                                        @if($patientHistoryRecords && $patientHistoryRecords->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover encounter-patient-history-table" style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.name') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.date') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.treatment_details') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.radiograph_type') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.radiograph_findings') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.is_complete') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($patientHistoryRecords as $record)
                                                            <tr class="encounter-table-row" style="display: table-row !important;">
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ optional($record->demographic)->full_name ?? (optional($record->user)->full_name ?? '--') }}</td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ formatDate($record->created_at) }}</td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ Illuminate\Support\Str::limit(optional($record->medicalHistory)->treatment_details ?? '', 30, '...') }}</td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if(optional($record->radiographicExamination)->radiograph_type)
                                                                        @foreach($record->radiographicExamination->radiograph_type as $type)
                                                                            <span class="badge bg-primary">{{ $type }}</span>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ optional($record->radiographicExamination)->radiograph_findings ?? '-' }}</td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    <span class="badge {{ $record->is_complete ? 'bg-success' : 'bg-warning' }}">
                                                                        {{ $record->is_complete ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </td>
                                                                <td class="small">
                                                                    <button type="button" class="btn btn-sm btn-outline-success" 
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
                                            <p class="text-muted text-center mb-0">{{ __('frontend.no_patient_history_records_found') }}</p>
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
                                @if($problems->isNotEmpty())
                                    <div class="encounter-section mb-4">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">{{ __('frontend.problems') }}</h6>
                                        </div>
                                            <div class="card-body">
                                                @foreach($problems as $problem)
                                                <div class="d-flex align-items-start mb-2">
                                                    <span class="badge bg-primary me-2">{{ $loop->iteration }}</span>
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
                                @if($observations->isNotEmpty())
                                    <div class="encounter-section mb-4">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">{{ __('frontend.observations') }}</h6>
                                        </div>
                                            <div class="card-body">
                                                @foreach($observations as $observation)
                                                <div class="d-flex align-items-start mb-2">
                                                    <span class="badge bg-info me-2">{{ $loop->iteration }}</span>
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
                                @if($notes->isNotEmpty())
                                    <div class="encounter-section mb-4">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">{{ __('frontend.notes') }}</h6>
                                        </div>
                                            <div class="card-body">
                                                @foreach($notes as $note)
                                                <div class="d-flex align-items-start mb-2">
                                                    <span class="badge bg-warning me-2">{{ $loop->iteration }}</span>
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
                                            $medicalReports = optional($encounter->patientEncounter)->medicalReports ?? collect();
                                        @endphp
                                        @if($medicalReports->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover encounter-patient-records-table" style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.radiographs') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.date') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($medicalReports as $report)
                                                            <tr class="encounter-table-row" style="display: table-row !important;">
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if($report->radiographs && is_array($report->radiographs))
                                                                        @foreach($report->radiographs as $radiograph)
                                                                            <span class="badge bg-primary me-1">{{ $radiograph }}</span>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ formatDate($report->date) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @elseif($medical_reports && $medical_reports->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover encounter-patient-records-table" style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.radiographs') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.date') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($medical_reports as $report)
                                                            <tr class="encounter-table-row" style="display: table-row !important;">
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if($report->radiographs && is_array($report->radiographs))
                                                                        @foreach($report->radiographs as $radiograph)
                                                                            <span class="badge bg-primary me-1">{{ $radiograph }}</span>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ formatDate($report->date) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @elseif($encounter->media && $encounter->media->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover encounter-patient-records-table" style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.radiographs') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.date') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($encounter->media as $media)
                                                            <tr class="encounter-table-row" style="display: table-row !important;">
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    <span class="badge bg-primary">{{ $media->name ?? 'Medical Report' }}</span>
                                                                </td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ formatDate($media->created_at) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted text-center mb-0">{{ __('frontend.no_medical_reports_found') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Follow-up Notes -->
                            @if(isset($followup_notes) && $followup_notes->isNotEmpty())
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.follow_up_notes') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($followup_notes as $note)
                                            <div class="followup-note-item pb-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0">{{ $note->title ?? 'Follow-up Note' }}</h6>
                                                    <small class="text-muted">{{ formatDate($note->created_at) }}</small>
                                                </div>
                                                <div class="small mb-0">{!! $note->description ?? 'No description available' !!}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- 7. Prescriptions -->
                            @if($prescriptions->isNotEmpty())
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.prescriptions') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($prescriptions as $prescription)
                                            <div class="prescription-item pb-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0 text-primary">{{ $prescription->name }}</h6>
                                                </div>
                                                @if($prescription->instruction)
                                                    <div class="small text-muted mb-2">{!! $prescription->instruction !!}</div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-block">{{ __('frontend.frequency') }}</small>
                                                        <span class="small fw-medium">{{ $prescription->frequency }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-block">{{ __('frontend.duration') }}</small>
                                                        <span class="small fw-medium">{{ $prescription->duration }} {{ __('frontend.days') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- 8. Orthodontic Treatment Daily Records -->
                            @if($orthodonticRecords->isNotEmpty())
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.orthodontic_treatment_daily_record') }}</h6>
                                        <a href="{{ route('frontend.download-orthodontic-records', ['appointment_id' => $encounter->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ph ph-download me-1"></i>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $orthodonticRecords = optional($encounter->patientEncounter)->orthodonticDailyRecords ?? collect();
                                        @endphp
                                        @if($orthodonticRecords->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover encounter-orthodontic-table" style="display: table !important; width: 100% !important; border-collapse: collapse !important;">
                                                    <thead class="table-light">
                                                        <tr style="display: table-row !important;">
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.date') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.procedure_performed') }}</th>
                                                            <th class="text-muted small encounter-table-header" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ __('frontend.oral_hygiene_status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($orthodonticRecords as $record)
                                                            <tr class="encounter-table-row" style="display: table-row !important;">
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ formatDate($record->treatment_date) }}</td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ $record->procedure_performed ?? '-' }}</td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
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
                                            <p class="text-muted text-center mb-0">{{ __('frontend.no_orthodontic_records_found') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- 9. STL Records -->
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.stl_records') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $stlRecords = $stl_records ?? collect();
                                        @endphp
                                        @if($stlRecords->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="text-muted small">{{ __('frontend.date') }}</th>
                                                            <th class="text-muted small">{{ __('frontend.stl_files') }}</th>
                                
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($stlRecords as $record)
                                                            <tr class="encounter-table-row" style="display: table-row !important;">
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">{{ formatDate($record['date'] ?? $record->created_at) }}</td>
                                                                <td class="small encounter-table-cell" style="display: table-cell !important; padding: 0.5rem !important; border: 1px solid #dee2e6 !important; text-align: left !important;">
                                                                    @if(isset($record['files']) && is_array($record['files']) && count($record['files']) > 0)
                                                                        @foreach(array_chunk($record['files'], 3) as $fileChunk)
                                                                            <div class="mb-1">
                                                                                @foreach($fileChunk as $file)
                                                                                    <span class="badge bg-primary me-1">{{ $file['name'] ?? 'STL File' }}</span>
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
                                            <p class="text-muted text-center mb-0">{{ __('frontend.no_stl_records_found') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- 10. Post-Operative Instructions -->
                            @php
                                $postInstructions = Modules\Appointment\Models\PostInstructions::all();
                            @endphp
                            @if($postInstructions->count() > 0)
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center" 
                                         data-bs-toggle="collapse" 
                                         data-bs-target="#post-operative-collapse" 
                                         aria-expanded="false" 
                                         aria-controls="post-operative-collapse"
                                         style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <h6 class="mb-0 me-2">{{ __('frontend.post_operative_instructions_for_dental_procedures') }}</h6>
                                            <i class="ph ph-caret-down collapse-icon" style="transition: transform 0.3s ease;"></i>
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
                            @if(optional($encounter->patientEncounter)->other_details)
                            <div class="encounter-section mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('frontend.other_information') }}</h6>
                                        <a href="{{ route('frontend.download-other-details', ['appointment_id' => $encounter->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ph ph-download me-1"></i>
                                            
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-0 small">{!! $encounter->patientEncounter->other_details !!}</div>
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
{{-- Encounter modal --}}

