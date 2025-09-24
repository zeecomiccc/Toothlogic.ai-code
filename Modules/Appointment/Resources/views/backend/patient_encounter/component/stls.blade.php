<div class="modal fade" id="addStlModal" tabindex="-1" role="dialog" aria-labelledby="addStlModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStlModalLabel">{{ __('appointment.add_stl_record') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="stl-form-submit" class="requires-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="stl_id">
                    <input type="hidden" name="user_id" id="stl_user_id" value="{{ $data['user_id'] }}">
                    <input type="hidden" name="encounter_id" id="stl_encounter_id" value="{{ $data['id'] }}">
                    <div class="form-group">
                        <label class="form-label col-md-12">{{ __('appointment.date') }} <span class="text-danger">*</span></label>
                        <input type="text" name="date" id="stl_date" class="form-control col-md-12" placeholder="{{ __('appointment.date') }}" required>
                        <div class="invalid-feedback">{{ __('appointment.date') }} {{ __('appointment.field_is_required') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label col-md-12">{{ __('appointment.stl_files') }} <span class="text-danger">*</span></label>
                        <div id="stl-files-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        <input type="file" name="stl_files[]" id="stl_files" class="form-control col-md-12" placeholder="{{ __('appointment.upload_stl_files') }}" value="" multiple accept=".stl,.zip,.rar,.7z,.obj,.pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">{{ __('appointment.supported_file_types') }}</small>
                        <div class="invalid-feedback">{{ __('appointment.stl_files') }} {{ __('appointment.are_required') }}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('appointment.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('appointment.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script>
function renderStlFilePreview(containerId, files) {
    var container = $(containerId);
    container.empty();
    if (!files || !files.length) return;
    files.forEach(function(file) {
        if (file.is_image) {
            var imgBox = $('<div />', {
                class: 'position-relative d-flex align-items-center justify-content-center',
                style: 'width: 60px; height: 60px; background: #fff; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; margin: 2px;'
            });
            var imgElem = $('<img />', {
                src: file.url,
                style: 'width: 100%; height: 100%; object-fit: contain; background: #fff; border-radius: 6px; display: block; cursor: pointer;',
                click: function() { window.open(file.url, '_blank'); }
            });
            var downloadBtn = $('<button />', {
                class: 'btn btn-xs btn-outline-primary position-absolute',
                style: 'top: 4px; right: 4px; padding: 1px 4px; z-index: 2;',
                html: '<i class="ph ph-download"></i>',
                click: function(e) { e.stopPropagation(); downloadFile(file.url, file.file_name); }
            });
            imgBox.append(imgElem, downloadBtn);
            container.append(imgBox);
        } else {
            var fileBox = $('<div />', {
                class: 'd-flex flex-column align-items-center justify-content-center',
                style: 'width: 60px; height: 60px; background: #f8f9fa; border-radius: 6px; margin: 2px; position: relative;'
            });
            var icon = $('<i />', {
                class: file.is_pdf ? 'ph ph-file-pdf fs-4 text-danger' : 'ph ph-file-text fs-4 text-primary',
                style: 'margin-bottom: 2px;'
            });
            var downloadBtn = $('<button />', {
                class: 'btn btn-xs btn-outline-primary position-absolute',
                style: 'top: 4px; right: 4px; padding: 1px 4px; z-index: 2;',
                html: '<i class="ph ph-download"></i>',
                click: function(e) { e.stopPropagation(); downloadFile(file.url, file.file_name); }
            });
            fileBox.append(icon, downloadBtn);
            fileBox.attr('title', file.file_name);
            fileBox.on('click', function() { window.open(file.url, '_blank'); });
            container.append(fileBox);
        }
    });
}

// Patch editStl to fetch and render file previews and format date
var originalEditStl = window.editStl;
window.editStl = function(id) {
    var baseUrl = '{{ url('/') }}';
    $.ajax({
        url: baseUrl + '/app/encounter/edit-stl/' + id,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status) {
                // Format date as YYYY-MM-DD
                var date = response.data.date ? response.data.date.substring(0, 10) : '';
                $('#stl_date').val(date);
                $('#stl_id').val(response.data.id);
                $('#stl_user_id').val(response.data.user_id);
                $('#stl_encounter_id').val(response.data.encounter_id);
                // Fetch and render file previews
                $.ajax({
                    url: baseUrl + '/app/encounter/get-stl-files/' + id,
                    type: 'GET',
                    success: function(res) {
                        if (res.files) {
                            renderStlFilePreview('#stl-files-preview', res.files);
                        }
                    }
                });
                $('#addStlModal').modal('show');
            } else {
                alert(response.message || 'Failed to load STL details.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('An unexpected error occurred.');
        }
    });
};
</script>
@endpush 