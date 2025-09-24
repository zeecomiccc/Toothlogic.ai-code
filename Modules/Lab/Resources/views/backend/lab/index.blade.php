@extends('backend.layouts.app')

@section('title')
{{ __('sidebar.lab') }}
@endsection

@section('content')
@if (isset($export_import) && $export_import)
<div data-render="import-export">
    <export-modal export-doctor-id="{{ $export_doctor_id ?? '' }}" export-url="{{ $export_url }}"
        :module-column-prop="{{ json_encode($export_columns) }}" module-name="{{ $module_name }}"></export-modal>
    <import-modal import-url="{{ $import_url ?? '' }}" module-name="{{ $module_name ?? '' }}"
        :module-column-prop="{{ json_encode($import_columns ?? []) }}">
    </import-modal>
</div>
@endif
<div class="table-content mb-5">
    <x-backend.section-header>
        <div class="d-flex flex-wrap gap-3">
            {{-- Bulk Action --}}
            <x-backend.quick-action url='{{ route("backend.lab.bulk_action") }}'>
                <div class="">
                    <select name="action_type" class="form-control select2 col-12" id="quick-action-type"
                        style="width:100%">
                        <option value="">{{ __('messages.no_action') }}</option>
                        <option value="change-status">{{ __('messages.status') }}</option>
                        <option value="delete">{{ __('messages.delete') }}</option>
                    </select>
                </div>
                <div class="select-status d-none quick-action-field" id="change-status-action">
                    <select name="status" class="form-control select2" id="status" style="width:100%">
                        <option value="" selected>{{ __('messages.select_status') }}</option>
                        <option value="1">{{ __('messages.active') }}</option>
                        <option value="0">{{ __('messages.inactive') }}</option>
                    </select>
                </div>
            </x-backend.quick-action>
            <div>
                <button type="button" class="btn btn-primary" data-modal="export">
                    <i class="ph ph-download-simple me-1"></i> {{ __('messages.export') }}
                </button>
                {{-- <button type="button" class="btn btn-primary" data-modal="import">
                    <i class="ph ph-download-simple me-1"></i>{{ __('messages.import') }}
                </button> --}}
            </div>
        </div>
        <x-slot name="toolbar">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..."
                    aria-label="Search" aria-describedby="addon-wrapping">
            </div>
            <button class="btn btn-secondary d-flex align-items-center gap-1 btn-group" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i class="ph ph-funnel"></i>{{
                __('messages.advance_filter') }}</button>
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#addLabModel"
                class='btn btn-primary d-flex align-items-center gap-1' data-crud-id="0">
                <i class="ph ph-plus-circle"></i>
                {{ __('lab.lab_report') }}
            </button>
        </x-slot>
    </x-backend.section-header>
    <table id="datatable" class="table position-relative">
    </table>
    <div data-render="app">
        @include('lab::backend.lab.lab_form')
        @include('lab::backend.lab.show')
    </div>
</div>
<!-- Hidden iframe for printing -->
<iframe id="print-iframe" style="display:none;"></iframe>

<x-backend.advance-filter>
    <x-slot name="title">
        <h4>{{ __('lab.lab_report') }} {{ __('messages.advance_filter') }}</h4>
    </x-slot>
    <div class="form-group datatable-filter">
        <label class="form-label" for="doctor_filter">{{ __('lab.doctor') }}</label>
        <select name="doctor_filter" id="doctor_filter" class="form-control select2" data-filter="select">
            <option value="">{{ __('messages.all') }} {{ __('lab.doctor') }}</option>
            @foreach ($doctors as $doctor)
            <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group datatable-filter">
        <label class="form-label" for="patient_filter">{{ __('lab.patient') }}</label>
        <select name="patient_filter" id="patient_filter" class="form-control select2" data-filter="select">
            <option value="">{{ __('messages.all') }} {{ __('lab.patient') }}</option>
            @foreach ($patients as $patient)
            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group datatable-filter">
        <label class="form-label" for="case_type_filter">{{ __('lab.case_type') }}</label>
        <select name="case_type_filter" id="case_type_filter" class="form-control select2" data-filter="select">
            <option value="">{{ __('messages.all') }} {{ __('lab.case_type') }}</option>
            <option value="clear_aligner">{{ __('lab.clear_aligner') }}</option>
            <option value="crown">{{ __('lab.crown') }}</option>
            <option value="bridge">{{ __('lab.bridge') }}</option>
            <option value="veneers">{{ __('lab.veneers') }}</option>
            <option value="partial_denture">{{ __('lab.partial_denture') }}</option>
            <option value="complete_denture">{{ __('lab.complete_denture') }}</option>
        </select>
    </div>

    <div class="form-group datatable-filter">
        <label class="form-label" for="case_status_filter">{{ __('lab.case_status') }}</label>
        <select name="case_status_filter" id="case_status_filter" class="form-control select2" data-filter="select">
            <option value="">{{ __('messages.all') }} {{ __('lab.case_status') }}</option>
            <option value="created">{{ __('lab.created') }}</option>
            <option value="in_progress">{{ __('lab.in_progress') }}</option>
            <option value="sent_to_lab">{{ __('lab.sent_to_lab') }}</option>
            <option value="delivered">{{ __('lab.delivered') }}</option>
            <option value="seated">{{ __('lab.seated') }}</option>
        </select>
    </div>

    <button type="reset" class="btn btn-danger" id="reset-filter">{{ __('messages.reset') }}</button>
</x-backend.advance-filter>
@endsection

@push('after-styles')
<script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>
<script src="{{ asset('js/form-modal/index.js') }}" defer></script>
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

<style>
    .quadrant-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    
    .quadrant-badge {
        background: linear-gradient(135deg, #00C2CB 0%, #0099a3 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .quadrant-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .section-title {
        color: #495057;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .teeth-numbers {
        background: #e3f2fd;
        color: #1976d2;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .treatment-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .treatment-badge {
        background: linear-gradient(135deg, #00C2CB 0%, #0099a3 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 0.8rem;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
    }
    
    .teeth-treatment-simple {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .teeth-treatment-simple .section-title {
        color: #495057;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f8f9fa;
    }
    
    .shade-selection-grid .shade-selection-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .shade-selection-grid .shade-selection-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .shade-position-header {
        display: flex;
        align-items: center;
        color: #495057;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f8f9fa;
    }
    
    .position-title {
        font-size: 1.1rem;
        color: #2c3e50;
    }
    
    .shade-options {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    
    .shade-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .shade-option:hover {
        transform: translateY(-4px);
    }
    
    .shade-swatch {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .shade-option.selected .shade-swatch {
        border-color: #00C2CB;
        box-shadow: 0 4px 12px rgba(0, 194, 203, 0.3);
        transform: scale(1.1);
    }
    
    .shade-checkmark {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #00C2CB;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 194, 203, 0.3);
    }
    
    .shade-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #6c757d;
        text-align: center;
    }
    
    .selected-shade-text {
        text-align: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #00C2CB;
    }
    
    .selected-label {
        font-weight: 600;
        color: #495057;
        margin-right: 8px;
    }
    
    .selected-value {
        background: linear-gradient(135deg, #00C2CB 0%, #0099a3 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .shade-grid .shade-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px;
        margin-right: 12px;
        margin-bottom: 12px;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .shade-grid .shade-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .shade-position {
        display: flex;
        align-items: center;
        color: #495057;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .shade-badge {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
    }
   
    .media-type {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .media-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
    }
    
    .media-title {
        color: #495057;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .media-count {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .media-file-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .media-file-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .media-file-item.image-file img {
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .file-name {
        color: #495057;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 8px;
        word-break: break-word;
    }
    
    .file-icon {
        color: #6c757d;
    }

    .file-actions {
        margin-top: auto;
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .file-actions .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        min-width: 30px;
    }

    .media-file-item.image-file .file-actions {
        margin-top: 10px;
    }

    .media-file-item.file-file .file-actions {
        margin-top: 15px;
    }
    
    .show-media-section {
        min-height: 200px;
    }
    
    .show-media-section:empty::before {
        content: 'Loading media files...';
        display: block;
        text-align: center;
        color: #6c757d;
        padding: 40px;
        font-style: italic;
    }
    
    .no-media-files {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }
    
    .no-media-files i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@push('after-scripts')
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
<script>
    $('#addLabModel').off('show.bs.modal').on('show.bs.modal', function() {
       });

       flatpickr('input[type="date"]', {
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });
</script>
<script>
    // Re-initialize Select2 when the offcanvas is shown to ensure search works
    $('#addLabModel').on('shown.bs.offcanvas', function () {
        $(this).find('.select2').select2({
            dropdownParent: $('#addLabModel')
        });
    });
</script>
<script type="text/javascript">
    const columns = [
    {
      name: 'check',
      data: 'check',
      title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
      width: '0%',
      exportable: false,
      orderable: false,
      searchable: false,
    },
    {
      data: 'id',
      name: 'id',
      title: 'ID',
      orderable: true,
      searchable: true,
    },
    {
      data: 'doctor',
      name: 'doctor',
      title: 'Doctor',
      orderable: true,
      searchable: true,
    },
    {
      data: 'patient',
      name: 'patient',
      title: 'Patient',
      orderable: true,
      searchable: true,
    },
    {
      data: 'case_type',
      name: 'case_type',
      title: 'Case Type',
      orderable: true,
      searchable: true,
    },
    {
      data: 'case_status',
      name: 'case_status',
      title: 'Status',
      orderable: true,
      searchable: true,
    },
    {
      data: 'created_at',
      name: 'created_at',
      title: 'Created At',
      orderable: true,
      searchable: false,
    }
  ];

  const actionColumn = [{
    data: 'action',
    name: 'action',
    orderable: false,
    searchable: false,
    title: 'Action'
  }];

  let finalColumns = [
    ...columns,
    ...actionColumn
  ];

  document.addEventListener('DOMContentLoaded', (event) => {
    initDatatable({
      url: '{{ route("backend.lab.index_data") }}',
      finalColumns,
      orderColumn: [[ 6, "desc" ]],
      advanceFilter: () => {
        return {
          doctor_filter: $('#doctor_filter').val(),
          patient_filter: $('#patient_filter').val(),
          case_type_filter: $('#case_type_filter').val(),
          case_status_filter: $('#case_status_filter').val(),
        }
      }
    });
    
    // Reset filter functionality
    $('#reset-filter').on('click', function(e) {
      $('#doctor_filter').val('').trigger('change');
      $('#patient_filter').val('').trigger('change');
      $('#case_type_filter').val('').trigger('change');
      $('#case_status_filter').val('').trigger('change');
      window.renderedDataTable.ajax.reload(null, false);
    });
  });
</script>
<script>
    function previewFiles(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'me-2 mb-2';
                        img.style.maxWidth = '80px';
                        img.style.maxHeight = '80px';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else if (file.name.toLowerCase().endsWith('.stl') || 
                          file.type === 'application/sla' || 
                          file.type === 'model/stl' || 
                          file.type === 'application/octet-stream') {
                    // Show STL file icon
                    const div = document.createElement('div');
                    div.className = 'me-2 mb-2 d-inline-block text-center';
                    div.innerHTML = `
                        <div class="text-primary mb-1">
                            <i class="ph ph-cube" style="font-size: 1.5rem;"></i>
                        </div>
                        <div style="font-size: 0.8rem;">
                            ${file.name}
                        </div>
                    `;
                    preview.appendChild(div);
                } else if (file.type === 'application/pdf') {
                    // Show PDF file icon
                    const div = document.createElement('div');
                    div.className = 'me-2 mb-2 d-inline-block text-center';
                    div.innerHTML = `
                        <div class="text-danger mb-1">
                            <i class="ph ph-file-pdf" style="font-size: 1.5rem;"></i>
                        </div>
                        <div style="font-size: 0.8rem;">
                            ${file.name}
                        </div>
                    `;
                    preview.appendChild(div);
                } else {
                    // Show generic file icon
                    const div = document.createElement('div');
                    div.className = 'me-2 mb-2 d-inline-block text-center';
                    div.innerHTML = `
                        <div class="text-secondary mb-1">
                            <i class="ph ph-file" style="font-size: 1.5rem;"></i>
                        </div>
                        <div style="font-size: 0.8rem;">
                            ${file.name}
                        </div>
                    `;
                    preview.appendChild(div);
                }
            });
        }
    }
    // Edit Lab Function
    function editLab(labId) {
        
        $.ajax({
            url: '{{ route("backend.lab.edit", ":id") }}'.replace(':id', labId),
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Edit response received:', response);
                
                if (!response.status || !response.data) {
                    alert('Error: Invalid response format from server');
                    return;
                }
                
                // Update form mode and title
                $('#form_mode').val('edit');
                $('#addLabModelLabel').text('Edit Lab Report');
                $('#lab_id').val(labId);
                
                // Update form action
                $('#lab-form-submit').attr('action', '{{ route("backend.lab.update", ":id") }}'.replace(':id', labId));
                
                // Add method override for PUT request
                if (!$('#_method').length) {
                    $('#lab-form-submit').append('<input type="hidden" name="_method" id="_method" value="PUT">');
                } else {
                    $('#_method').val('PUT');
                }
                
                // Populate form fields
                $('#lab-form-submit select[name="patient_id"]').val(response.data.patient_id).trigger('change');
                $('#lab-form-submit select[name="doctor_id"]').val(response.data.doctor_id).trigger('change');
                $('#lab-form-submit select[name="case_type"]').val(response.data.case_type).trigger('change');
                $('#lab-form-submit select[name="case_status"]').val(response.data.case_status).trigger('change');
                $('#lab-form-submit input[name="delivery_date_estimate"]').val(response.data.delivery_date_estimate ? new Date(response.data.delivery_date_estimate).toISOString().split('T')[0] : '');
                $('#lab-form-submit input[name="treatment_plan_link"]').val(response.data.treatment_plan_link);
                $('#lab-form-submit textarea[name="notes"]').val(response.data.notes);
                $('#lab-form-submit select[name="clear_aligner_impression_type"]').val(response.data.clear_aligner_impression_type).trigger('change');
                $('#lab-form-submit select[name="prosthodontic_impression_type"]').val(response.data.prosthodontic_impression_type).trigger('change');
                $('#lab-form-submit input[name="margin_location"]').val(response.data.margin_location);
                $('#lab-form-submit input[name="contact_tightness"]').val(response.data.contact_tightness);
                $('#lab-form-submit input[name="occlusal_scheme"]').val(response.data.occlusal_scheme);
                // Populate shade selection
                if (response.data.shade_selection) {
                    $('#shade_selection_input').val(response.data.shade_selection);
                    
                    try {
                        // Parse the existing shade selection data
                        const existingShadeData = JSON.parse(response.data.shade_selection);
                        
                        // Update the individual shade input fields
                        if (existingShadeData.Cervical) $('#cervical_shade_input').val(existingShadeData.Cervical);
                        if (existingShadeData.Middle) $('#general_shade_input').val(existingShadeData.Middle);
                        if (existingShadeData.Incisal) $('#incisal_shade_input').val(existingShadeData.Incisal);
                        
                    } catch (e) {
                        // If parsing fails, just clear the selection
                        $('#shade_selection_input').val('');
                    }
                } else {
                    $('#shade_selection_input').val('');
                    // Clear individual shade inputs
                    $('#cervical_shade_input').val('');
                    $('#general_shade_input').val('');
                    $('#incisal_shade_input').val('');
                    $('.file-preview').empty();
                    $('.existing-file').remove();
                }
                
                // Handle teeth_treatment_type data
            if (response.data.teeth_treatment_type && response.data.teeth_treatment_type.length > 0) {
                // Accept stored JSON string or array of quadrant objects
                let storedTeethData = response.data.teeth_treatment_type;
                try {
                    if (typeof storedTeethData === 'string') {
                        storedTeethData = JSON.parse(storedTeethData);
                    }
                } catch (e) {
                    storedTeethData = [];
                }
                
                // Clear all quadrant treatment type selects first
                $('#lab-form-submit select[name="ur_treatment_type"]').val(null).trigger('change');
                $('#lab-form-submit select[name="ul_treatment_type"]').val(null).trigger('change');
                $('#lab-form-submit select[name="ll_treatment_type"]').val(null).trigger('change');
                $('#lab-form-submit select[name="lr_treatment_type"]').val(null).trigger('change');
                
                // Populate selects and mark teeth based on stored structure
                if (Array.isArray(storedTeethData)) {
                    storedTeethData.forEach(function(item) {
                        if (!item || !item.quadrant) return;
                        var selectName = item.quadrant + '_treatment_type';
                        var selectElement = $('#lab-form-submit select[name="' + selectName + '"]');
                        if (selectElement.length && Array.isArray(item.treatments)) {
                            selectElement.val(item.treatments).trigger('change');
                        }
                        if (Array.isArray(item.teeth)) {
                            item.teeth.forEach(function(teethNumber) {
                                var btn = document.querySelector('[data-teeth="' + teethNumber + '"][data-quadrant="' + item.quadrant + '"]');
                                if (btn) btn.classList.add('selected');
                            });
                        }
                    });
                }
                
                // Preserve the stored structure
                $('#teeth_treatment_type_input').val(JSON.stringify(storedTeethData));
                
                console.log('Populated teeth treatment type selects with:', storedTeethData);
                
                // Refresh on-screen summary only
                if (typeof updateCombinedDataDisplay === 'function') {
                    try { updateCombinedDataDisplay(storedTeethData); } catch (e) {}
                }
            } else {
                $('#teeth_treatment_type_input').val('');
                // Clear all quadrant treatment type selects
                $('#lab-form-submit select[name="ur_treatment_type"]').val(null).trigger('change');
                $('#lab-form-submit select[name="ul_treatment_type"]').val(null).trigger('change');
                $('#lab-form-submit select[name="ll_treatment_type"]').val(null).trigger('change');
                $('#lab-form-submit select[name="lr_treatment_type"]').val(null).trigger('change');
                console.log('No teeth treatment type data to populate');
            }
                
                // Handle temporary_placed radio button (convert boolean to string)
                if (response.data.temporary_placed !== null && response.data.temporary_placed !== undefined) {
                    const tempValue = response.data.temporary_placed ? '1' : '0';
                    $('#lab-form-submit input[name="temporary_placed"][value="' + tempValue + '"]').prop('checked', true);
                }
                
                // Show existing media files if any
                displayExistingMedia(response.data.media);
                
                // Open the modal
                $('#addLabModel').offcanvas('show');
            },
            error: function(xhr, status, error) {
                 let errorMessage = 'Error loading lab data';
                 if (xhr.status === 404) {
                     errorMessage = 'Lab record not found';
                 } else if (xhr.status === 500) {
                     errorMessage = 'Server error occurred';
                 } else if (xhr.responseText) {
                     try {
                         const response = JSON.parse(xhr.responseText);
                         errorMessage = response.message || errorMessage;
                     } catch (e) {
                         errorMessage = xhr.responseText;
                     }
                 }
                 
                 alert(errorMessage);
             }
        });
    }
    
    // Display existing media files
    function displayExistingMedia(media) {
        // Clear existing previews
        $('.file-preview').empty();
        $('.existing-file').remove();
        
        if (media) {
            Object.keys(media).forEach(collection => {
                // Map collection names to preview IDs
                const previewIdMap = {
                    'clear_aligner_intraoral': 'preview-intraoral',
                    'clear_aligner_pics': 'preview-pics',
                    'clear_aligner_others': 'preview-aligner-others',
                    'prostho_prep_scans': 'preview-prep-scans',
                    'prostho_bite_scans': 'preview-bite-scans',
                    'prostho_preop_pictures': 'preview-preop-pictures',
                    'prostho_others': 'preview-prostho-others',
                    'rx_prep_scan': 'preview-rx-prep-scan',
                    'rx_bite_scan': 'preview-rx-bite-scan',
                    'rx_preop_images': 'preview-rx-preop-images'
                };
                
                const previewId = previewIdMap[collection];
                const preview = $('#' + previewId);
                
                if (!preview.length) {
                    return;
                }
                
                if (media[collection] && media[collection].length > 0) {
                    media[collection].forEach(file => {
                        // Get file icon based on file type
                        // const fileIcon = getFileIcon(file.mime_type || file.name);
                        
                        const fileDiv = $(`
                            <div class="file-item d-flex align-items-center justify-content-between p-2 border rounded mb-2" data-media-id="${file.id}">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="file-type-icon">
                                        {{-- ${fileIcon} --}}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="file-name-display" title="${file.name}">${file.name}</div>
                                        <small class="text-muted">${file.mime_type} (${formatFileSize(file.size)})</small>
                                    </div>
                                </div>
                                <div class="ms-2">
                                    <button class="btn btn-sm btn-outline-primary download-btn" 
                                            title="Download ${file.name}"
                                            data-url="${file.url}" 
                                            data-filename="${file.file_name}">
                                        <i class="ph ph-download"></i>
                                    </button>
                                </div>
                            </div>
                        `);
                        
                        // Add click event handler for download button
                        fileDiv.find('.download-btn').off('click').on('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            const url = $(this).data('url');
                            const filename = $(this).data('filename');
                            const labId = $('#lab_id').val();
                            const mediaId = $(this).closest('.file-item').data('media-id') || file.id;
                            downloadFile(url, filename, labId, mediaId);
                            
                            return false;
                        });
                        
                        preview.append(fileDiv);
                    });
                }
            });
        }
    }
    
    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function downloadFile(url, fileName, labId, mediaId) {
        // Check if we have labId and mediaId for proper download
        if (labId && mediaId) {
            // Use the Lab download route for proper MIME type handling
            const downloadUrl = '{{ route("backend.lab.download-file", [":id", ":mediaId"]) }}'
                .replace(':id', labId)
                .replace(':mediaId', mediaId);
            
            // Create a temporary link element for download
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = fileName || 'download';
            link.target = '_blank';
            
            // Append to body, click, and remove
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            // Fallback to direct URL if labId/mediaId not available
            var link = document.createElement('a');
            link.href = url;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
    
    // Remove media file
    function removeMedia(mediaId) {
        if (confirm('Are you sure you want to remove this file?')) {
            $.ajax({
                url: '{{ route("backend.lab.remove-media", ["id" => ":id", "media_id" => ":media_id"]) }}'.replace(':id', $('#lab_id').val()).replace(':media_id', mediaId),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Refresh the edit form to show updated media
                    editLab($('#lab_id').val());
                },
                error: function(xhr) {
                    alert('Error removing file: ' + xhr.responseText);
                }
            });
        }
    }

    // Show Lab Function
    function showLab(labId) {
        $.ajax({
            url: '{{ route("backend.lab.show", ":id") }}'.replace(':id', labId),
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    const lab = response.data;
                    populateShowModal(lab);
                    $('#showLabModal').offcanvas('show');
                } else {
                    alert('Error loading lab data');
                }
            },
            error: function(xhr) {
                alert('Error loading lab data: ' + xhr.responseText);
            }
        });
    }

    // Populate show modal with lab data
    function populateShowModal(lab) {
        
        // Set the lab ID on the modal for reference
        const modal = document.querySelector('#showLabModal');
        modal.setAttribute('data-lab-id', lab.id);
        
        // Patient Information
        $('#show-patient-name').text(lab.patient ? lab.patient.full_name : 'N/A');
        $('#show-patient-email').text(lab.patient ? lab.patient.email : 'N/A');
        $('#show-patient-phone').text(lab.patient ? lab.patient.mobile : 'N/A');
        
        // Set patient avatar if available
        if (lab.patient && lab.patient.profile_image) {
            $('#show-patient-avatar').attr('src', lab.patient.profile_image);
        }
        
        // Set patient statistics from database
        if (lab.patient_stats) {
            $('#show-total-appointments').text(lab.patient_stats.total_appointments || 0);
            $('#show-total-labs').text(lab.patient_stats.total_labs || 0);
        } else {
            $('#show-total-appointments').text('0');
            $('#show-total-labs').text('0');
        }
        
        // Lab Information
        $('#show-lab-id').text(lab.id);
        $('#show-doctor-name').text(lab.doctor ? lab.doctor.full_name : 'N/A');
        $('#show-case-type').text(lab.case_type ? lab.case_type.replace('_', ' ').toUpperCase() : 'N/A');
        $('#show-case-status').text(lab.case_status ? lab.case_status.replace('_', ' ').toUpperCase() : 'N/A');
        $('#show-created-at').text(lab.created_at ? new Date(lab.created_at).toLocaleDateString() : 'N/A');
        $('#show-delivery-date').text(lab.delivery_date_estimate ? new Date(lab.delivery_date_estimate).toLocaleDateString() : 'N/A');
        
        // Clinical details
        $('#show-clinical-instructions').text(lab.clinical_instructions || 'N/A');
        $('#show-notes').text(lab.notes || 'N/A');
        
        // Impression types
        $('#show-clear-aligner-impression').text(lab.clear_aligner_impression_type ? lab.clear_aligner_impression_type.charAt(0).toUpperCase() + lab.clear_aligner_impression_type.slice(1) : 'N/A');
        $('#show-prosthodontic-impression').text(lab.prosthodontic_impression_type ? lab.prosthodontic_impression_type.charAt(0).toUpperCase() + lab.prosthodontic_impression_type.slice(1) : 'N/A');
        
        // Treatment plan
        if (lab.treatment_plan_link && (lab.treatment_plan_link.startsWith('http://') || lab.treatment_plan_link.startsWith('https://'))) {
            $('#show-treatment-plan').html(`<a href="${lab.treatment_plan_link}" target="_blank" class="text-primary">${lab.treatment_plan_link}</a>`);
        } else {
            $('#show-treatment-plan').text(lab.treatment_plan_link || 'N/A');
        }
        
        // Rx instructions
        $('#show-rx-margin-location').text(lab.margin_location || 'N/A');
        $('#show-rx-contact-tightness').text(lab.contact_tightness || 'N/A');
        $('#show-rx-occlusal-scheme').text(lab.occlusal_scheme || 'N/A');
        $('#show-rx-temporary-placed').text(lab.temporary_placed ? 'Yes' : 'No');
        
        // Teeth treatment type
         if (lab.teeth_treatment_type) {
             displayTeethTreatmentType(lab.teeth_treatment_type);
         } else {
             // Find all teeth treatment display containers and update them
             const teethContainers = document.querySelectorAll('.teeth-treatment-display, .teeth-treatment-display-full');
             teethContainers.forEach(container => {
                 if (container) {
                     container.innerHTML = '<span class="text-muted">No data</span>';
                 }
             });
         }
         
         // Shade selection
         if (lab.shade_selection) {
              try {
                  let shadeData;
                  if (typeof lab.shade_selection === 'string') {
                      // Try to parse as JSON first
                      try {
                          shadeData = JSON.parse(lab.shade_selection);
                      } catch (parseError) {
                          // If JSON parsing fails, check if it's a simple shade string
                          if (lab.shade_selection.includes('shade')) {
                              // Convert simple shade string to proper format
                              shadeData = {
                                  'General': lab.shade_selection
                              };
                          } else {
                              // Try to parse as a different format or set as empty
                              shadeData = {};
                          }
                      }
                  } else {
                      shadeData = lab.shade_selection;
                  }
                  
                  // Populate individual shade fields
                  if (shadeData.Cervical) {
                      const cervicalElement = document.getElementById('show-cervical-shade');
                      cervicalElement.textContent = shadeData.Cervical;
                      cervicalElement.className = 'shade-value';
                  } else {
                      const cervicalElement = document.getElementById('show-cervical-shade');
                      cervicalElement.textContent = 'Not specified';
                      cervicalElement.className = 'shade-value no-data';
                  }
                  
                  if (shadeData.Middle) {
                      const middleElement = document.getElementById('show-middle-shade');
                      middleElement.textContent = shadeData.Middle;
                      middleElement.className = 'shade-value';
                  } else {
                      const middleElement = document.getElementById('show-middle-shade');
                      middleElement.textContent = 'Not specified';
                      middleElement.className = 'shade-value no-data';
                  }
                  
                  if (shadeData.Incisal) {
                      const incisalElement = document.getElementById('show-incisal-shade');
                      incisalElement.textContent = shadeData.Incisal;
                      incisalElement.className = 'shade-value';
                  } else {
                      const incisalElement = document.getElementById('show-incisal-shade');
                      incisalElement.textContent = 'Not specified';
                      incisalElement.className = 'shade-value no-data';
                  }
                              } catch (e) {
                    console.log('Error processing shade data:', e);
                    // Set default values if there's an error
                    const cervicalElement = document.getElementById('show-cervical-shade');
                    cervicalElement.textContent = 'Error loading data';
                    cervicalElement.className = 'shade-value error';
                    
                    const middleElement = document.getElementById('show-middle-shade');
                    middleElement.textContent = 'Error loading data';
                    middleElement.className = 'shade-value error';
                    
                    const incisalElement = document.getElementById('show-incisal-shade');
                    incisalElement.textContent = 'Error loading data';
                    incisalElement.className = 'shade-value error';
                }
         } else {
             // No shade data available
             const cervicalElement = document.getElementById('show-cervical-shade');
             cervicalElement.textContent = 'No data available';
             cervicalElement.className = 'shade-value no-data';
             
             const middleElement = document.getElementById('show-middle-shade');
             middleElement.textContent = 'No data available';
             middleElement.className = 'shade-value no-data';
             
             const incisalElement = document.getElementById('show-incisal-shade');
             incisalElement.textContent = 'No data available';
             incisalElement.className = 'shade-value no-data';
         }
         
         // Notes
         document.getElementById('show-notes').textContent = lab.notes || 'No notes available';
        
        // Media files
        if (lab.media) {
            displayMediaFiles(lab.media);
        }
    }

    // Print Lab function
    function printLab(id) {
        const url = '{{ route("backend.lab.print", ":id") }}'.replace(':id', id);
        fetch(url)
            .then(response => response.text())
            .then(html => {
                let printFrame = document.getElementById('print-iframe');
                if (!printFrame) {
                    printFrame = document.createElement('iframe');
                    printFrame.id = 'print-iframe';
                    printFrame.style.display = 'none';
                    document.body.appendChild(printFrame);
                }
                printFrame.onload = function() {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                };
                printFrame.srcdoc = html;
            });
    }

    // Download Lab PDF function
    function downloadLabPDF(id) {
        window.open('{{ route("backend.lab.download-pdf", ":id") }}'.replace(':id', id), '_blank');
    }

    // Bulk action functions
    function resetQuickAction() {
        const actionValue = $('#quick-action-type').val();
        if (actionValue != '') {
            $('#quick-action-apply').removeAttr('disabled');

            if (actionValue == 'change-status') {
                $('.quick-action-field').addClass('d-none');
                $('#change-status-action').removeClass('d-none');
            } else {
                $('.quick-action-field').addClass('d-none');
            }
        } else {
            $('#quick-action-apply').attr('disabled', true);
            $('.quick-action-field').addClass('d-none');
        }
    }

    $('#quick-action-type').change(function() {
        resetQuickAction();
    });

    $(document).on('update_quick_action', function() {
        // resetActionButtons()
    });

    $(document).ready(function() {
        $('#lab-form-submit').on('submit', async function(event) {
            event.preventDefault();
            let form = $(this)[0];
            if (form.checkValidity() === false) {
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }
            
            // Ensure teeth data is updated before submission
            if (typeof updateTeethSelection === 'function') {
                updateTeethSelection();
                // Add a small delay to ensure the hidden input is updated
                await new Promise(resolve => setTimeout(resolve, 100));
            }
            
            let formData = new FormData(this);
            
            // Ensure teeth_treatment_type values are properly included
            const teethTreatmentTypeInput = $('#teeth_treatment_type_input').val();
            
            if (teethTreatmentTypeInput && teethTreatmentTypeInput.trim() !== '') {
                try {
                    const teethData = JSON.parse(teethTreatmentTypeInput);
                    
                    // Remove any existing entries
                    formData.delete('teeth_treatment_type');
                    const allTreatments = [];
                    teethData.forEach(quadrantData => {
                        if (quadrantData.treatments && Array.isArray(quadrantData.treatments)) {
                            allTreatments.push(...quadrantData.treatments);
                        }
                    });
                    
                    // Store the complete quadrant structure in a hidden field for display
                    formData.append('teeth_treatment_structure', JSON.stringify(teethData));
                    
                    // Add each treatment type to the form data for backend processing
                    allTreatments.forEach(treatment => {
                        formData.append('teeth_treatment_type[]', treatment);
                    });
                } catch (e) {
                    // If parsing fails, ensure we don't send invalid data
                    formData.delete('teeth_treatment_type');
                }
            } else {
                // Ensure the field is not sent if empty
                formData.delete('teeth_treatment_type');
            }
            
            // Ensure shade_selection is included
            const shadeSelection = $('#shade_selection_input').val();
            if (shadeSelection) {
                formData.set('shade_selection', shadeSelection);
            }
            
            let baseUrl = '{{ url('/') }}';
            let route = $(this).attr('action');
            $.ajax({
                url: route,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Reset form for create mode
                    $('#form_mode').val('create');
                    $('#addLabModelLabel').text('Create Lab Report');
                    $('#lab_id').val('');
                    $('#lab-form-submit').attr('action', '{{ route("backend.lab.store") }}');
                    if ($('#_method').length) {
                        $('#_method').val('POST');
                    }
                    $('#lab-form-submit')[0].reset();
                    // Reset the teeth treatment type input
                    $('#teeth_treatment_type_input').val('');
                    // Reset shade selection
                    $('#shade_selection_input').val('');
                    $('.file-preview').empty();
                    $('.existing-file').remove();

                    $('.offcanvas').offcanvas('hide');
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });
        $('.offcanvas').on('hidden.bs.offcanvas', function() {
            // Reset form for create mode
            $('#form_mode').val('create');
            $('#addLabModelLabel').text('Create Lab Report');
            $('#lab_id').val('');
            $('#lab-form-submit').attr('action', '{{ route("backend.lab.store") }}');
            if ($('#_method').length) {
                $('#_method').val('POST');
            }
            $('#lab-form-submit')[0].reset();
            // Reset the teeth treatment type input
            $('#teeth_treatment_type_input').val('');
            // Reset shade selection
            $('#shade_selection_input').val('');
            // Clear individual shade inputs
            $('#cervical_shade_input').val('');
            $('#general_shade_input').val('');
            $('#incisal_shade_input').val('');
            $('.file-preview').empty();
            $('.existing-file').remove();
        });
    });

         // Function to display teeth treatment type data
     function displayTeethTreatmentType(data) {
         // Display in both containers
         const containers = [
             document.querySelector('.teeth-treatment-display'),
             document.querySelector('.teeth-treatment-display-full')
         ].filter(Boolean);
         
         if (containers.length === 0) return;
         
         // Handle different data formats
         if (!data) {
             containers.forEach(container => {
                 container.innerHTML = '<span class="text-muted">No teeth treatment data</span>';
             });
             return;
         }
         
         // Ensure data is an array
         let teethData = data;
         if (typeof data === 'string') {
             try {
                 teethData = JSON.parse(data);
             } catch (e) {
                 // If JSON parsing fails, treat as simple string
                 if (data.trim() !== '') {
                     teethData = [data];
                 } else {
                     containers.forEach(container => {
                         if (container) {
                             container.innerHTML = '<span class="text-muted">No teeth treatment data</span>';
                         }
                     });
                     return;
                 }
             }
         }
         
         // Check if data is valid array
         if (!Array.isArray(teethData) || teethData.length === 0) {
             containers.forEach(container => {
                 container.innerHTML = '<span class="text-muted">No teeth treatment data</span>';
             });
             return;
         }
        
        // Handle simple array format (current storage format)
        if (teethData.length > 0 && typeof teethData[0] === 'string') {
            // Simple array of treatment types
            let html = '<div class="teeth-treatment-simple">';
            html += '<div class="section-title mb-2"><i class="ph ph-stethoscope me-1"></i>Treatment Types:</div>';
            html += '<div class="treatment-badges">';
            teethData.forEach(treatment => {
                const formattedTreatment = treatment.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                html += `<span class="treatment-badge">${formattedTreatment}</span>`;
            });
            html += '</div>';
            html += '</div>';
            
            // Update all containers
            containers.forEach(container => {
                container.innerHTML = html;
            });
            return;
        }
        
        // Handle complex format (quadrant structure)
        let html = '<div class="teeth-treatment-grid">';
        
        // Create a structured quadrant display
        const quadrants = ['ur', 'ul', 'lr', 'll'];
        const quadrantLabels = {
            'ur': 'Upper Right',
            'ul': 'Upper Left', 
            'lr': 'Lower Right',
            'll': 'Lower Left'
        };
        
        quadrants.forEach(quadrant => {
            const quadrantData = teethData.find(item => item.quadrant === quadrant);
            
            html += '<div class="quadrant-item mb-3">';
            html += `<div class="quadrant-header mb-2">`;
            html += `<span class="quadrant-badge">${quadrant.toUpperCase()}</span>`;
            html += `<span class="quadrant-label">${quadrantLabels[quadrant]}</span>`;
            html += `</div>`;
            
            if (quadrantData && quadrantData.teeth && Array.isArray(quadrantData.teeth) && quadrantData.teeth.length > 0) {
                html += `<div class="teeth-section mb-2">`;
                html += `<div class="section-title"><i class="ph ph-tooth me-1"></i>Selected Teeth:</div>`;
                html += `<div class="teeth-numbers">${quadrantData.teeth.join(', ')}</div>`;
                html += `</div>`;
            } else {
                // Show default teeth for each quadrant if no specific teeth selected
                const defaultTeeth = {
                    'ur': '18 17 16 15 14 13 12 11',
                    'ul': '21 22 23 24 25 26 27 28',
                    'lr': '48 47 46 44 43 42 41',
                    'll': '31 32 33 34 35 36 37 38'
                };
                html += `<div class="teeth-section mb-2">`;
                html += `<div class="section-title"><i class="ph ph-tooth me-1"></i>Selected Teeth:</div>`;
                html += `<div class="teeth-numbers">${defaultTeeth[quadrant]}</div>`;
                html += `</div>`;
            }
            
            if (quadrantData && quadrantData.treatments && Array.isArray(quadrantData.treatments) && quadrantData.treatments.length > 0) {
                html += `<div class="treatments-section">`;
                html += `<div class="section-title"><i class="ph ph-stethoscope me-1"></i>Treatment Types:</div>`;
                html += `<div class="treatment-badges">`;
                quadrantData.treatments.forEach(treatment => {
                    const formattedTreatment = treatment.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    html += `<span class="treatment-badge">${formattedTreatment}</span>`;
                });
                html += `</div>`;
                html += `</div>`;
            } else {
                html += `<div class="treatments-section">`;
                html += `<div class="section-title"><i class="ph ph-stethoscope me-1"></i>Treatment Types:</div>`;
                html += `<div class="treatment-badges">`;
                html += `<span class="text-muted">No treatments selected</span>`;
                html += `</div>`;
                html += `</div>`;
            }
            
            html += '</div>';
        });
        
        html += '</div>';
        
        // Update all containers
        containers.forEach(container => {
            container.innerHTML = html;
        });
    }

    // Function to display media files
    function displayMediaFiles(media) {
        const container = document.querySelector('.show-media-section');
        if (!container) return;
        
        // Get the current lab ID from the modal
        const labId = document.querySelector('#showLabModal').getAttribute('data-lab-id');
        
        let html = '';
        Object.entries(media).forEach(([type, files]) => {
            if (files && files.length > 0) {
                
                // Format collection name (remove underscores, capitalize)
                const formattedType = type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                html += `<div class="media-type mb-4">`;
                html += `<div class="media-header mb-3">`;
                html += `<h6 class="media-title">`;
                html += `<i class="ph ph-folder me-2"></i>`;
                html += `${formattedType}`;
                html += `</h6>`;
                html += `<div class="media-count">${files.length} file${files.length !== 1 ? 's' : ''}</div>`;
                html += `</div>`;
                html += `<div class="row">`;
                
                files.forEach(file => {
                    
                    // Ensure file has required properties
                    const fileUrl = file.url || file.original_url || file.download_url || '#';
                    const fileName = file.name || file.file_name || 'Unknown File';
                    const mimeType = file.mime_type || 'application/octet-stream';
                    const isImage = mimeType.startsWith('image/');
                    const isPdf = mimeType === 'application/pdf';
                    const mediaId = file.id || file.media_id;
                    
                    html += `<div class="col-md-3 mb-3">`;
                    if (isImage) {
                        html += `<div class="media-file-item image-file">`;
                        html += `<img src="${fileUrl}" class="img-fluid rounded" alt="${fileName}" style="max-height: 100px; width: 100%; object-fit: cover;" onerror="this.src='{{ asset('public/img/avatar/avatar.webp') }}'">`;
                        html += `<div class="file-name mt-2">${fileName}</div>`;
                        html += `<div class="file-actions mt-2">`;
                        html += `<button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="downloadFile('${fileUrl}', '${fileName}', '${labId}', '${mediaId}')">`;
                        html += `<i class="ph ph-download me-1"></i>`;
                        html += `</button>`;
                        html += `<a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-secondary">`;
                        html += `<i class="ph ph-eye me-1"></i>`;
                        html += `</a>`;
                        html += `</div>`;
                        html += `</div>`;
                    } else {
                        html += `<div class="media-file-item file-file">`;
                        html += `<div class="file-icon mb-2">`;
                        if (isPdf) {
                            html += `<i class="ph ph-file-pdf fs-1 text-danger"></i>`;
                        } else {
                            html += `<i class="ph ph-file-text fs-1 text-muted"></i>`;
                        }
                        html += `</div>`;
                        html += `<div class="file-name">    ${fileName}</div>`;
                        html += `<div class="file-actions mt-2">`;
                        html += `<button type="button" class="btn btn-sm btn-outline-primary" onclick="downloadFile('${fileUrl}', '${fileName}', '${labId}', '${mediaId}')">`;
                        html += `<i class="ph ph-download me-1"></i>`;
                        html += `</button>`;
                        html += `</div>`;
                        html += `</div>`;
                    }
                    html += `</div>`;
                });
                
                html += `</div></div>`;
            }
        });
        
        if (html === '') {
            html = '<div class="no-media-files">';
            html += '<i class="ph ph-folder-open"></i>';
            html += '<div>No media files uploaded</div>';
            html += '<small class="text-muted">Files will appear here once uploaded</small>';
            html += '</div>';
        }
        
        container.innerHTML = html;
        
    }

    // Function to update teeth selection (required for form submission)
    function updateTeethSelection() {
        // This function is called from the form to ensure teeth data is updated
        // The actual implementation is in lab_form.blade.php
        console.log('updateTeethSelection called from index');
    }

    // Function to update combined data display (required for teeth selection)
    function updateCombinedDataDisplay(combinedData) {
        // This function is called from the form to update teeth display
        // The actual implementation is in lab_form.blade.php
        console.log('updateCombinedDataDisplay called from index:', combinedData);
    }

    // Function to update treatment display (required for teeth selection)
    function updateTreatmentDisplay() {
        // This function is called from the form to update treatment display
        // The actual implementation is in lab_form.blade.php
        console.log('updateTreatmentDisplay called from index');
    }
</script>
@endpush