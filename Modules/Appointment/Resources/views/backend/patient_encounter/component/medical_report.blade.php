<div class="modal fade" id="addMedicalreport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('clinic.add_medical_report') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" id="medical-report-submit" class="requires-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row" id="medical-report-model">


                        <input type="hidden" name="id" id="medical_id" >
                        <input type="hidden" name="user_id" id="medical_user_id" value="{{ $data['user_id'] }}">
                        <input type="hidden" name="encounter_id" id="medical_encounter_id" value="{{ $data['id'] }}">


                        {{-- <div class="form-group">
                            <label class="form-label col-md-12">
                                {{ __('clinic.lbl_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="medical_name"  class="form-control col-md-12"
                                placeholder="{{ __('clinic.lbl_name') }}" required>
                            <div class="invalid-feedback">
                                {{ __('Name field is required.') }}
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label class="form-label col-md-12">
                                {{ __('clinic.lbl_date') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="date" id="date" class="form-control col-md-12"
                                placeholder="{{ __('clinic.lbl_date') }}" required>

                            <div class="invalid-feedback" id="date-error"> {{ __('appointment.date') }} {{ __('appointment.field_is_required') }}</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label col-md-12">
                                {{ __('clinic.lbl_intraoral_scans') }}
                            </label>
                            <div id="intraoral-scans-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            <input type="file" name="intraoral_scans[]" id="intraoral_scans" class="form-control col-md-12"
                                placeholder="Upload Intraoral Scans" value="" multiple 
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom,.stl,.obj">
                            <small class="form-text text-muted">
                                {{ __('appointment.supported_file_types') }}
                            </small>
                            
                            <div class="invalid-feedback">
                                {{ __('Intraoral scans field is required.') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label col-md-12">
                                {{ __('clinic.lbl_oral_pics') }}
                            </label>
                            <div id="oral-pics-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            <input type="file" name="oral_pics[]" id="oral_pics" class="form-control col-md-12"
                                placeholder="Upload Oral Pictures" value="" multiple 
                                accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                            <small class="form-text text-muted">
                                {{ __('appointment.supported_file_types') }}
                            </small>
                            
                            <div class="invalid-feedback">
                                {{ __('Oral pictures field is required.') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label col-md-12">
                                {{ __('clinic.lbl_radiographs') }}
                            </label>
                            <div id="radiograph-files-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="radiographs[]" value="Bitewing" id="radiograph_bitewing">
                                        <label class="form-check-label" for="radiograph_bitewing">
                                            Bitewing
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="radiographs[]" value="Periapical" id="radiograph_periapical">
                                        <label class="form-check-label" for="radiograph_periapical">
                                            Periapical
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="radiographs[]" value="OPG" id="radiograph_opg">
                                        <label class="form-check-label" for="radiograph_opg">
                                            {{ __('clinic.lbl_opg') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="radiographs[]" value="Lateral Ceph" id="radiograph_lateral_ceph">
                                        <label class="form-check-label" for="radiograph_lateral_ceph">
                                            Lateral Ceph
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="radiographs[]" value="CBCT" id="radiograph_cbct">
                                        <label class="form-check-label" for="radiograph_cbct">
                                            CBCT
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="radiographs[]" value="Others" id="radiograph_others">
                                        <label class="form-check-label" for="radiograph_others">
                                            {{ __('clinic.lbl_others') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="radiograph_files[]" id="radiograph_files" class="form-control col-md-12"
                                placeholder="Upload Radiograph Files" value="" multiple 
                                accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom,.pdf">
                            <small class="form-text text-muted">
                                {{ __('appointment.supported_file_types') }}
                            </small>
                            
                            <div class="invalid-feedback">
                                {{ __('Radiograph files field is required.') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label col-md-12">
                                {{ __('clinic.lbl_additional_attachments') }}
                            </label>
                            <div id="additional-attachments-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            <input type="file" name="additional_attachments[]" id="additional_attachments" class="form-control col-md-12"
                                placeholder="Upload Additional Attachments" value="" multiple 
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom,.stl,.obj,.xlsx,.xls,.csv">
                            <small class="form-text text-muted">
                                {{ __('appointment.supported_file_types') }}
                            </small>
                            
                            <div class="invalid-feedback">
                                {{ __('Additional attachments field is required.') }}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('clinic.btn_close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('clinic.btn_save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

    @push('after-scripts')
        <script>
            $(document).ready(function() {

                flatpickr('#date', {
                    dateFormat: "Y-m-d",

                });

                var baseUrl = '{{ url('/') }}';

                $('#medical-report-submit').on('submit', function(event) {
                    event.preventDefault();

                    const dateField = $('#date');
                    const dateError = $('#date-error');

                    dateField.removeClass('is-invalid');
                    dateError.hide();

                    if (!dateField.val()) {
                        dateField.addClass('is-invalid');
                        dateError.show();
                    }
                    let form = $(this)[0];
                    if (form.checkValidity() === false || dateField.hasClass('is-invalid')) {
                        event.stopPropagation();
                        form.classList.add('was-validated');
                        return;
                    }

                    let formData = new FormData(this);

                    let hasId = formData.has('id') && formData.get('id') !== '';
                    let id = formData.get('id') || null;

                    let route = hasId ?
                        `${baseUrl}/app/encounter/update-medical-report/${id}` :
                        `${baseUrl}/app/encounter/save-medical-report`;

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
                                document.getElementById('medical_report_table').innerHTML = response
                                    .html;
                                $('#addMedicalreport').modal('hide');
                                $('#medical-report-submit')[0].reset();
                                $('#medical_id').val('');
                                $('#medical-report-submit')[0].classList.remove('was-validated');
                                window.successSnackbar(
                                    `Medical report ${hasId ? 'updated' : 'added'} successfully`
                                    );
                            } else {
                                window.errorSnackbar('Something went wrong! Please check.');
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred: ' + xhr.responseText);
                        }
                    });
                });

                $('#addMedicalreport').on('hidden.bs.modal', function() {
                    $('#medical_id').val('');
                    $('#medical-report-submit')[0].reset();
                    $('input[name="radiographs[]"]').prop('checked', false);
                    $('#intraoral_scans').val('');
                    $('#oral_pics').val('');
                    $('#radiograph_files').val('');
                    $('#additional_attachments').val('');
                });
            });

            function renderFilePreview(containerId, files) {
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

            function downloadFile(url, fileName) {
                var link = document.createElement('a');
                link.href = url;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
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
                            // Fetch and render file previews for each type
                            $.ajax({
                                url: baseUrl + '/app/encounter/get-medical-report-images/' + id,
                                type: 'GET',
                                success: function(res) {
                                    if (res.files) {
                                        renderFilePreview('#intraoral-scans-preview', res.files.filter(f => f.type === 'intraoral_scan'));
                                        renderFilePreview('#oral-pics-preview', res.files.filter(f => f.type === 'oral_pic'));
                                        renderFilePreview('#radiograph-files-preview', res.files.filter(f => f.type === 'radiograph'));
                                        renderFilePreview('#additional-attachments-preview', res.files.filter(f => f.type === 'additional_attachment'));
                                    }
                                }
                            });
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
        </script>
    @endpush
