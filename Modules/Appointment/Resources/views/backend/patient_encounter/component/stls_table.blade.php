<div class="table-responsive rounded mb-0">
    <table class="table table-lg m-0" id="stls_table">
        <thead>
            <tr class="text-white">
                <th scope="col">{{ __('appointment.date') }}</th>
                <th scope="col">{{ __('appointment.stl_files') }}</th>
                <th scope="col">{{ __('appointment.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($data['stls']) && count($data['stls']) > 0)
            @foreach ($data['stls'] as $stl)
            <tr>
                <td>{{ $stl['date'] ? \Carbon\Carbon::parse($stl['date'])->format('Y-m-d') : '' }}</td>
                <td>
                    @foreach (array_chunk($stl['files'], 3) as $fileChunk)
                    <div class="mb-1">
                        @foreach ($fileChunk as $file)
                        <span class="badge bg-primary me-1">{{ $file['name'] }}</span>
                        @endforeach
                    </div>
                    @endforeach
                </td>
                <td class="action">
                    <button type="button" class="btn text-primary p-0 fs-5 me-2" data-bs-toggle="modal"
                        data-bs-target="#addStlModal" onclick="editStl({{ $stl['id'] }})"
                        aria-controls="form-offcanvas">
                        <i class="ph ph-pencil-simple-line"></i>
                    </button>
                    <button type="button" class="btn text-info p-0 fs-5" onclick="showStlFiles({{ $stl['id'] }})"
                        data-bs-toggle="tooltip" title="View Files">
                        <i class="ph ph-eye"></i>
                    </button>
                    <button type="button" class="btn text-danger p-0 fs-5"
                        onclick="deleteStl({{ $stl['id'] }}, 'Are you sure you want to delete this STL record?')"
                        data-bs-toggle="tooltip" title="Delete">
                        <i class="ph ph-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="3" class="text-center text-danger">{{ __('appointment.no_stl_files_found') }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Modal for STL file previews -->
<div class="modal fade" id="stlFilesModal" tabindex="-1" aria-labelledby="stlFilesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stlFilesModalLabel">{{ __('appointment.stl_files') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="stl-files-gallery" class="row"></div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {
        flatpickr('#stl_date', { dateFormat: "Y-m-d" });
        var baseUrl = '{{ url('/') }}';
        $('#stl-form-submit').on('submit', function(event) {
            event.preventDefault();
            let form = $(this)[0];
            if (form.checkValidity() === false) {
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }
            let formData = new FormData(this);
            let hasId = formData.has('id') && formData.get('id') !== '';
            let id = formData.get('id') || null;
            let route = hasId ? `${baseUrl}/app/encounter/update-stl/${id}` : `${baseUrl}/app/encounter/save-stl`;
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
                    if (response.html) {
                        $('#addStlModal').modal('hide');
                        window.location.reload();
                    } else {
                        window.errorSnackbar('Something went wrong! Please check.');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });
        $('#addStlModal').on('hidden.bs.modal', function() {
            $('#stl_id').val('');
            $('#stl-form-submit')[0].reset();
        });
    });
    function editStl(id) {
        var baseUrl = '{{ url('/') }}';
        $.ajax({
            url: baseUrl + '/app/encounter/edit-stl/' + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    $('#stl_date').val(response.data.date);
                    $('#stl_id').val(response.data.id);
                    $('#stl_user_id').val(response.data.user_id);
                    $('#stl_encounter_id').val(response.data.encounter_id);
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
    }
    function deleteStl(id, message) {
        var baseUrl = '{{ url('/') }}';
        confirmDeleteSwal({ message }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: baseUrl + '/app/encounter/delete-stl/' + id,
                type: 'GET',
                success: (response) => {
                    if (response.reload) {
                        window.location.reload();
                        return;
                    }
                    if (response.html) {
                        $('#stls_table').html(response.html);
                        Swal.fire({
                            title: 'Deleted',
                            text: response.message,
                            icon: 'success',
                            showClass: { popup: 'animate__animated animate__zoomIn' },
                            hideClass: { popup: 'animate__animated animate__zoomOut' }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message || 'Failed to delete the STL record.',
                            icon: 'error',
                            showClass: { popup: 'animate__animated animate__shakeX' },
                            hideClass: { popup: 'animate__animated animate__fadeOut' }
                        });
                    }
                }
            });
        });
    }
    function downloadStlFiles(encounterId) {
        var baseUrl = '{{ url('/') }}';
        window.open(baseUrl + '/app/encounter/download-stl-files?id=' + encounterId);
    }
    
    function showStlFiles(stlId) {
    var baseUrl = '{{ url('/') }}';
    $.ajax({
        url: baseUrl + '/app/encounter/get-stl-files/' + stlId,
        type: 'GET',
        success: function(response) {
            var gallery = $('#stl-files-gallery');
            gallery.empty();
            if (response.files && response.files.length > 0) {
                response.files.forEach(function(file) {
                    var col = $('<div class="col-md-4 mb-3"></div>');
                    if (file.is_image) {
                        var imageContainer = $('<div />', {
                            class: 'position-relative d-flex align-items-center justify-content-center',
                            style: 'width: 180px; height: 180px; background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); margin: 5px; overflow: hidden;'
                        });
                        var imgElem = $('<img />', {
                            src: file.url,
                            style: 'width: 100%; height: 100%; object-fit: contain; background: #fff; border-radius: 8px; display: block;'
                        });
                        var downloadButton = $('<a />', {
                            class: 'btn btn-sm btn-outline-primary position-absolute',
                            style: 'top: 8px; right: 8px; padding: 2px 6px; z-index: 2;',
                            html: '<i class="ph ph-download"></i>',
                            href: baseUrl + '/app/encounter/view-stl-file/' + stlId + '/' + file.id,
                            download: file.file_name,
                            target: '_blank'
                        });
                        imageContainer.append(imgElem, downloadButton);
                        col.append(imageContainer);
                    } else {
                        var fileCard = $('<div />', {
                            class: 'card',
                            style: 'width: 180px; min-height: 160px;'
                        });
                        var cardBody = $('<div />', {
                            class: 'card-body text-center p-2'
                        });
                        var icon = $('<i />', {
                            class: file.is_pdf ? 'ph ph-file-pdf fs-1 text-danger' : 'ph ph-file-text fs-1 text-primary'
                        });
                        var fileName = $('<div />', {
                            class: 'mt-2 text-truncate',
                            text: file.file_name,
                            title: file.file_name
                        });
                        var fileType = $('<small />', {
                            class: 'text-info',
                            text: file.mime_type || ''
                        });
                        var fileSize = $('<small />', {
                            class: 'text-muted',
                            text: formatFileSize(file.size)
                        });
                        var viewButton = $('<a />', {
                            class: 'btn btn-sm btn-primary mt-2',
                            text: 'View',
                            href: baseUrl + '/app/encounter/view-stl-file/' + stlId + '/' + file.id,
                            target: '_blank'
                        });
                        var downloadButton = $('<a />', {
                            class: 'btn btn-sm btn-outline-primary mt-1 ms-1',
                            html: '<i class="ph ph-download"></i>',
                            href: baseUrl + '/app/encounter/view-stl-file/' + stlId + '/' + file.id,
                            download: file.file_name,
                            target: '_blank'
                        });
                        cardBody.append(icon, fileName, fileType, fileSize, viewButton, downloadButton);
                        fileCard.append(cardBody);
                        col.append(fileCard);
                    }
                    gallery.append(col);
                });
            } else {
                gallery.append('<div class="text-danger text-center">No files found.</div>');
            }
            $('#stlFilesModal').modal('show');
        },
        error: function() {
            alert('Failed to load files.');
        }
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadFile(url, fileName) {
    var link = document.createElement('a');
    link.href = url;
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endpush