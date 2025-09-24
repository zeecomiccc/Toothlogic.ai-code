<div class="table-responsive rounded mb-0">
    <table class="table table-lg m-0" id="medical_report_table">
        <thead>

            <tr class="text-white">
                {{-- <th scope="col">{{ __('appointment.name') }}</th> --}}
                <th scope="col">{{ __('appointment.date') }}</th>
                <th scope="col">{{ __('appointment.radiographs') }}</th>
                <th scope="col">{{ __('appointment.action') }}</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($data['medicalReport'] as $index => $medicalreport)
                <tr>
                    {{-- <td>
                        <span>
                            {{ $medicalreport['name'] }}
                        </span>

                    </td> --}}
                    <td>
                        {{ $medicalreport['date'] }}
                    </td>

                    <td>
                        @if (!empty($medicalreport['radiographs']))
                            @foreach ($medicalreport['radiographs'] as $radiograph)
                                <span class="badge bg-primary me-1">{{ $radiograph }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                        <td class="action">

                            <div class="d-flex align-items-center gap-3">
                                @if ($data['status'] == 1)
                                <button type="button" class="btn text-primary p-0 fs-5 me-2" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"
                                    onclick="editMedicalreport({{ $medicalreport['id'] }})"
                                    aria-controls="form-offcanvas">
                                    <i class="ph ph-pencil-simple-line"></i>
                                </button>

                                @endif


                                <button type="button" class="btn text-primary p-0 fs-5" onclick="showMedicalReportImages({{ $medicalreport['id'] }})">
                                    <i class="ph ph-eye align-middle"></i>
                                </button>

                                @if ($data['status'] == 1)

                                <button type="button" class="btn text-danger p-0 fs-5"
                                onclick="deletemedicalreport({{ $medicalreport['id'] }}, 'Are you sure you want to delete it?')"
                                data-bs-toggle="tooltip">
                                <i class="ph ph-trash"></i>
                              </button>
                            @endif



                            </div>
                        </td>

                </tr>
            @endforeach


            @if (count($data['medicalReport']) <= 0)
                <tr>
                    <td colspan="5">
                        <div class="my-1 text-danger text-center">{{ __('appointment.no_medical_report_found') }}
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</div>

<!-- Modal for viewing files -->
<div class="modal fade" id="medicalReportImagesModal" tabindex="-1" aria-labelledby="medicalReportImagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicalReportImagesModalLabel">{{ __('appointment.medical_report_files') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="medical-report-files-gallery" class="d-flex flex-wrap gap-3 justify-content-center"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for expanded image -->
<div class="modal fade" id="expandedImageModal" tabindex="-1" aria-labelledby="expandedImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 d-flex justify-content-center align-items-center">
                <img id="expanded-image" src="" class="img-fluid rounded shadow" style="max-width:90vw; max-height:90vh;" />
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    var baseUrl = '{{ url('/') }}';
    function deletemedicalreport(id, message) {

            confirmDeleteSwal({
                message
            }).then((result) => {

                if (!result.isConfirmed) return;

                $.ajax({
                    url: baseUrl + '/app/encounter/delete-medical-report/' + id,
                    type: 'GET',
                    success: (response) => {
                        if (response.html) {

                            $('#medical_report_table').html(response.html);

                            Swal.fire({
                                title: 'Deleted',
                                text: response.message,
                                icon: 'success',
                                showClass: {
                                    popup: 'animate__animated animate__zoomIn'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__zoomOut'
                                }
                            });
                        } else {

                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'Failed to delete the prescription.',
                                icon: 'error',
                                showClass: {
                                    popup: 'animate__animated animate__shakeX'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOut'
                                }
                            });
                        }
                    }
                });
            });
        }


        function editMedicalreport(id) {
            $.ajax({
                url: baseUrl + '/app/encounter/edit-medical-report/' + id,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {

                    if (response.status) {

                        // $('#medical_name').val(response.data.name);
                        $('#date').val(response.data.date);
                        $('#medical_id').val(response.data.id);
                        $('#medical_user_id').val(response.data.user_id);
                        $('#medical_encounter_id').val(response.data.encounter_id);

                        // Reset radiograph checkboxes
                        $('input[name="radiographs[]"]').prop('checked', false);
                        
                        // Check radiograph checkboxes based on saved data
                        if (response.data.radiographs && Array.isArray(response.data.radiographs)) {
                            response.data.radiographs.forEach(function(radiograph) {
                                $('input[name="radiographs[]"][value="' + radiograph + '"]').prop('checked', true);
                            });
                        }

                        $('#addMedicalreport').modal('show');
                    } else {
                        alert(response.message || 'Failed to load prescription details.');
                    }
                },
                error: (xhr, status, error) => {
                    console.error(error);
                    alert('An unexpected error occurred.');
                }
            });
        }



        function sendMedicalReport(button, id) {

            $(button).prop('disabled', true).html(`
                                 <div class="d-inline-flex align-items-center gap-1">
                                 <i class="ph ph-spinner ph-spin"></i>
                                 Sending...
                                 </div>
                                 `);

            $.ajax({
                url: baseUrl + '/app/encounter/send-medical-report?id=' + id,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    if (response.status) {
                        window.successSnackbar(response.message);
                    } else {
                        window.errorSnackbar('Something went wrong! Please check.');
                    }
                    // Re-enable the button and reset its text
                    $(button).prop('disabled', false).html(`
                              <div class="d-inline-flex align-items-center gap-1">
                                  <i class="ph ph-paper-plane-tilt"></i>
                                  {{ __('appointment.email') }}
                              </div>
                          `);
                  },
                error: (xhr, status, error) => {
                    console.error(error);
                    window.errorSnackbar('Something went wrong! Please check.');
                    // Re-enable the button and reset its text
                    $(button).prop('disabled', false).html(`
                         <div class="d-inline-flex align-items-center gap-1">
                             <i class="ph ph-paper-plane-tilt"></i>
                             {{ __('appointment.email') }}
                         </div>
                   `);
                  }
            });
        }

    function showMedicalReportImages(reportId) {
        // Fetch all files for the report via AJAX
        $.ajax({
            url: baseUrl + '/app/encounter/get-medical-report-images/' + reportId,
            type: 'GET',
            success: function(response) {
                var gallery = $('#medical-report-files-gallery');
                gallery.empty();
                if (response.files && response.files.length > 0) {
                    // Group files by type
                    var groups = {
                        'intraoral_scan': [],
                        'oral_pic': [],
                        'radiograph': [],
                        'additional_attachment': [],
                        'medical_report': []
                    };
                    response.files.forEach(function(file) {
                        if (groups[file.type]) {
                            groups[file.type].push(file);
                        } else {
                            groups['medical_report'].push(file); // fallback
                        }
                    });

                    // Helper to create a row for each group
                    function renderGroupRow(label, files) {
                        if (!files.length) return '';
                        var row = $('<div class="mb-4 w-100"></div>');
                        var labelElem = $('<div class="fw-bold mb-2 text-start">' + label + '</div>');
                        var fileRow = $('<div class="d-flex flex-wrap gap-3"></div>');
                        files.forEach(function(file) {
                            if (file.is_image) {
                                var imageContainer = $('<div />', {
                                    class: 'position-relative d-flex align-items-center justify-content-center',
                                    style: 'width: 180px; height: 180px; background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); margin: 5px; overflow: hidden;'
                                });
                                var imgElem = $('<img />', {
                                    src: file.url,
                                    style: 'width: 100%; height: 100%; object-fit: contain; background: #fff; border-radius: 8px; display: block;',
                                    click: function() {
                                        showExpandedImage(file.url);
                                    }
                                });
                                var downloadButton = $('<button />', {
                                    class: 'btn btn-sm btn-outline-primary position-absolute',
                                    style: 'top: 8px; right: 8px; padding: 2px 6px; z-index: 2;',
                                    html: '<i class="ph ph-download"></i>',
                                    click: function(e) {
                                        e.stopPropagation();
                                        downloadFile(file.url, file.file_name);
                                    }
                                });
                                imageContainer.append(imgElem, downloadButton);
                                fileRow.append(imageContainer);
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
                                    text: getFileTypeLabel(file.type)
                                });
                                var fileSize = $('<small />', {
                                    class: 'text-muted',
                                    text: formatFileSize(file.size)
                                });
                                var viewButton = $('<button />', {
                                    class: 'btn btn-sm btn-primary mt-2',
                                    text: 'View',
                                    click: function(e) {
                                        e.stopPropagation();
                                        openFileInNewTab(file.url);
                                    }
                                });
                                var downloadButton = $('<button />', {
                                    class: 'btn btn-sm btn-outline-primary mt-1 ms-1',
                                    html: '<i class="ph ph-download"></i>',
                                    click: function(e) {
                                        e.stopPropagation();
                                        downloadFile(file.url, file.file_name);
                                    }
                                });
                                cardBody.append(icon, fileName, fileType, fileSize, viewButton, downloadButton);
                                fileCard.append(cardBody);
                                fileRow.append(fileCard);
                            }
                        });
                        row.append(labelElem, fileRow);
                        return row;
                    }

                    // Render each group in order
                    var order = [
                        { key: 'intraoral_scan', label: 'Intraoral Scans' },
                        { key: 'oral_pic', label: 'Oral Pictures' },
                        { key: 'radiograph', label: 'Radiographs' },
                        { key: 'additional_attachment', label: 'Additional Attachments' },
                        { key: 'medical_report', label: 'Other Files' }
                    ];
                    order.forEach(function(group) {
                        var rowElem = renderGroupRow(group.label, groups[group.key]);
                        if (rowElem) gallery.append(rowElem);
                    });
                } else {
                    gallery.append('<div class="text-danger">No files found.</div>');
                }
                $('#medicalReportImagesModal').modal('show');
            },
            error: function() {
                alert('Failed to load files.');
            }
        });
    }

    function showExpandedImage(imgUrl) {
        $('#expanded-image').attr('src', imgUrl);
        $('#expandedImageModal').modal('show');
    }

    function openFileInNewTab(url) {
        window.open(url, '_blank');
    }

    function downloadFile(url, fileName) {
        var link = document.createElement('a');
        link.href = url;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function getFileTypeLabel(type) {
        switch (type) {
            case 'radiograph':
                return 'Radiograph';
            case 'medical_report':
                return 'Medical Report';
            case 'intraoral_scan':
                return 'Intraoral Scan';
            case 'oral_pic':
                return 'Oral Picture';
            case 'additional_attachment':
                return 'Additional Attachment';
            default:
                return 'Unknown';
        }
    }
</script>
@endpush
