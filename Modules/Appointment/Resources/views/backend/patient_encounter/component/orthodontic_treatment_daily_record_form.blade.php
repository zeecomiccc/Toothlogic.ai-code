<div class="modal modal-lg fade" id="addOrthoDailyRecordModal" tabindex="-1" role="dialog"
    aria-labelledby="addOrthoDailyRecordModalLabel" aria-hidden="true">
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="#addOrthoDailyRecordModalLabel">
                    {{ __('appointment.add_orthodontic_daily_record') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="ortho-daily-record-form" class="requires-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="ortho_daily_record_id">
                    <input type="hidden" name="encounter_id" id="ortho_daily_record_encounter_id"
                        value="{{ $data['id'] ?? '' }}">
                    <input type="hidden" name="clinic_id" id="ortho_daily_record_clinic_id"
                        value="{{ $data['clinic_id'] ?? '' }}">
                    <input type="hidden" name="doctor_id" id="ortho_daily_record_doctor_id"
                        value="{{ $data['doctor_id'] ?? '' }}">
                    <input type="hidden" name="patient_id" id="ortho_daily_record_patient_id"
                        value="{{ $data['user_id'] ?? '' }}">

                    <div class="form-group">
                        <label class="form-label" for="date">
                            {{ __('appointment.date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="date" id="date" class="form-control date"
                            placeholder="{{ __('appointment.date') }}" autocomplete="off" required>
                        <div class="invalid-feedback" id="date-error">
                            {{ __('validation.required', ['attribute' => __('appointment.date')]) }}</div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="form-label"
                                for="procedure_performed">{{ __('appointment.procedure_performed') }}
                                <span class="text-danger">*</span></label>
                            <textarea name="procedure_performed" id="procedure_performed" class="form-control" required placeholder="{{ __('appointment.procedure_performed') }}"></textarea>
                            <div class="invalid-feedback">
                                {{ __('validation.required', ['attribute' => __('appointment.procedure_performed')]) }}
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label"
                                for="oral_hygiene_status">{{ __('appointment.oral_hygiene_status') }}
                                <span class="text-danger">*</span></label>
                            <textarea name="oral_hygiene_status" id="oral_hygiene_status" class="form-control" required placeholder="{{ __('appointment.oral_hygiene_status') }}"></textarea>
                            <div class="invalid-feedback">
                                {{ __('validation.required', ['attribute' => __('appointment.oral_hygiene_status')]) }}
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label"
                                for="patient_compliance">{{ __('appointment.patient_compliance') }}
                                <span class="text-danger">*</span></label>
                            <textarea name="patient_compliance" id="patient_compliance" class="form-control" required placeholder="{{ __('appointment.patient_compliance') }}"></textarea>
                            <div class="invalid-feedback">
                                {{ __('validation.required', ['attribute' => __('appointment.patient_compliance')]) }}
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label"
                                for="next_appointment_date_procedure">{{ __('appointment.next_appointment_date_procedure') }}
                                <span class="text-danger">*</span></label>
                            <textarea name="next_appointment_date_procedure" id="next_appointment_date_procedure" class="form-control" required placeholder="{{ __('appointment.next_appointment_date_procedure') }}"></textarea>
                            <div class="invalid-feedback">
                                {{ __('validation.required', ['attribute' => __('appointment.next_appointment_date_procedure')]) }}
                            </div>
                        </div>

                        <div class="form-group col-lg-6         ">
                            <label class="form-label"
                                for="remarks_comments">{{ __('appointment.remarks_comments') }}</label>
                            <textarea name="remarks_comments" id="remarks_comments" class="form-control" placeholder="{{ __('appointment.remarks_comments') }}">
                        </textarea>
                            <div class="invalid-feedback">
                                {{ __('validation.required', ['attribute' => __('appointment.remarks_comments')]) }}
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label" for="initials">{{ __('appointment.initials') }}</label>
                            <textarea name="initials" id="initials" class="form-control" placeholder="{{ __('appointment.initials') }}">
                        </textarea>
                            <div class="invalid-feedback">
                                {{ __('validation.required', ['attribute' => __('appointment.initials')]) }}</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('clinic.btn_close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('clinic.btn_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        flatpickr('#date', {
            dateFormat: "Y-m-d",
        });

        var baseUrl = '{{ url('/') }}';
        // Open modal for add
        $(document).on('click', '[data-bs-target="#addOrthoDailyRecordModal"]', function() {
            $('#addOrthoDailyRecordModal .modal-title').text(
                '{{ __('appointment.add_orthodontic_daily_record') }}');
            $('#ortho-daily-record-form')[0].reset();
            $('#ortho_daily_record_id').val('');
        });

        // Save (add/update)
        $('#ortho-daily-record-form').on('submit', function(e) {
            e.preventDefault();
            let form = $('#ortho-daily-record-form')[0]; // Get raw DOM element
            const dateField = $('.date');
            const dateError = $('#date-error');

            dateField.removeClass('is-invalid');
            dateError.hide();

            if (!dateField.val()) {
                dateField.addClass('is-invalid');
                dateError.show();
            }
            if (form.checkValidity() === false || dateField.hasClass('is-invalid')) {
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            let id = $('#ortho_daily_record_id').val();
            let url = id ? `${baseUrl}/app/encounter/update-orthodontic-treatment-daily-record/${id}` :
                `${baseUrl}/app/encounter/save-orthodontic-treatment-daily-record`;
            let method = 'POST';
            $.ajax({
                url: url,
                method: method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#addOrthoDailyRecordModal').modal('hide');
                        document.getElementById('ortho_daily_record_table').innerHTML = response
                            .html;
                        window.successSnackbar(response.message || 'Saved successfully');
                    } else {
                        window.errorSnackbar(response.message || 'Error occurred');
                    }
                },
                error: function(xhr) {
                    window.errorSnackbar(xhr.responseJSON?.message || 'Error occurred');
                }
            });
        });

        // Edit ortho record
        function editOrthoRecord(id) {
            $.get(`${baseUrl}/app/encounter/edit-orthodontic-treatment-daily-record/${id}`, function(
                response) {
                let record = response.data;
                $('#addOrthoDailyRecordModal .modal-title').text(
                    '{{ __('appointment.edit_orthodontic_daily_record') }}');
                $('#ortho_daily_record_id').val(record.id);
                $('.date').val(record.date);
                $('#procedure_performed').val(record.procedure_performed);
                $('#oral_hygiene_status').val(record.oral_hygiene_status);
                $('#patient_compliance').val(record.patient_compliance);
                $('#next_appointment_date_procedure').val(record
                    .next_appointment_date_procedure);
                $('#remarks_comments').val(record.remarks_comments);
                $('#initials').val(record.initials);
                $('#addOrthoDailyRecordModal').modal('show');
            });
        }

        // Delete ortho record
        function deleteOrthoRecord(id) {
            confirmDeleteSwal({
                message: 'Are you sure you want to delete it?'
            }).then((result) => {
                if (!result.isConfirmed) return;
            });

            $.ajax({
                url: `${baseUrl}/app/encounter/delete-orthodontic-treatment-daily-record/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        document.getElementById('ortho_daily_record_table').innerHTML = response
                            .html;
                        window.successSnackbar(response.message || 'Deleted successfully');
                    } else {
                        window.errorSnackbar(response.message || 'Error occurred');
                    }
                },
                error: function(xhr) {
                    window.errorSnackbar(xhr.responseJSON?.message || 'Error occurred');
                }
            });
        }

        $('#addOrthoDailyRecordModal').on('hidden.bs.modal', function() {
            $('#ortho_daily_record_id').val('');
            $('#date').val('');
            $('#procedure_performed').val('');
            $('#oral_hygiene_status').val('');
            $('#patient_compliance').val('');
            $('#next_appointment_date_procedure').val('');
            $('#remarks_comments').val('');
            $('#initials').val('');
            $('#ortho-daily-record-form')[0].reset();
            $('#ortho-daily-record-form')[0].classList.remove('was-validated');
        });
    </script>
@endpush
