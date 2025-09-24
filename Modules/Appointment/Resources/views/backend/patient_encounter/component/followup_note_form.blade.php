<div class="modal fade" id="addFollowUpNote" tabindex="-1" role="dialog" aria-labelledby="addFollowUpNoteLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFollowUpNoteLabel">{{ __('appointment.add_follow_up_note') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="followup-note-form" class="requires-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="followup_note_id">
                    <input type="hidden" name="encounter_id" id="followup_encounter_id"
                        value="{{ $data['id'] ?? '' }}">
                    <input type="hidden" name="clinic_id" id="followup_clinic_id"
                        value="{{ $data['clinic_id'] ?? '' }}">
                    <input type="hidden" name="doctor_id" id="followup_doctor_id"
                        value="{{ $data['doctor_id'] ?? '' }}">
                    <input type="hidden" name="patient_id" id="followup_patient_id"
                        value="{{ $data['user_id'] ?? '' }}">

                    <div class="form-group">
                        <label for="followup_title" class="form-label">{{ __('appointment.title') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" id="followup_title" class="form-control" required
                            placeholder="{{ __('appointment.title') }}">
                        <div class="invalid-feedback">
                            {{ __('validation.required', ['attribute' => __('appointment.title')]) }}</div>
                    </div>

                    <div class="form-group">
                        <label for="followup_description" class="form-label">{{ __('appointment.description') }} <span
                                class="text-danger">*</span></label>
                        <textarea name="description" id="followup_description" class="form-control" rows="4" required
                            placeholder="{{ __('appointment.description') }}"></textarea>
                        <div class="invalid-feedback">
                            {{ __('validation.required', ['attribute' => __('appointment.description')]) }}</div>
                    </div>

                    <div class="form-group">
                        <label for="followup_date" class="form-label">{{ __('appointment.date') }} <span
                                class="text-danger">*</span></label>
                        <input type="date" name="date" id="followup_date" class="form-control" required
                            placeholder="{{ __('appointment.date') }}">
                        <div class="invalid-feedback" id="date-error">
                            {{ __('validation.required', ['attribute' => __('appointment.date')]) }}</div>
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

        function editFollowUpNote(id) {
            $.ajax({
                url: `${baseUrl}/app/encounter/edit-followup-note/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response && response.followup_notes) {
                        $('#followup_note_id').val(response.followup_notes.id);
                        $('#followup_title').val(response.followup_notes.title);
                        $('#followup_description').val(response.followup_notes.description);
                        $('#followup_date').val(response.followup_notes.date);
                        $('#addFollowUpNote').modal('show');
                    }
                }
            });
        }

        function deleteFollowUpNote(id) {
            confirmDeleteSwal({
                message: 'Are you sure you want to delete it?'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: `${baseUrl}/app/encounter/delete-followup-note/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            document.getElementById('followup_note_table').innerHTML = response
                                .html;
                            window.successSnackbar(response.message);
                        } else {
                            window.errorSnackbar('Delete failed');
                        }
                    },
                    error: function() {
                        window.errorSnackbar('Delete failed');
                    }
                });
            });
        }

        $(document).ready(function() {
            // Handle add/edit submit
            $(document).on('submit', '#followup-note-form', function(event) {
                event.preventDefault();
                let form = $(this)[0];

                const dateField = $('#followup_date');
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

                var id = $('#followup_note_id').val();
                var url = id ? `${baseUrl}/app/encounter/update-followup-note/${id}` :
                    `${baseUrl}/app/encounter/save-followup-note`;
                var method = 'POST';
                var data = $(this).serializeArray();
                // Use plain textarea value
                data.push({
                    name: 'description',
                    value: $('#followup_description').val()
                });
                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            document.getElementById('followup_note_table').innerHTML = response
                                .html;
                            $('#addFollowUpNote').modal('hide');
                            $('#followup-note-form')[0].reset();
                            $('#followup-note-form')[0].classList.remove('was-validated');
                            window.successSnackbar(response.message);
                        } else {
                            window.errorSnackbar('Save failed');
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Save failed';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                            .message;
                        window.errorSnackbar(msg);
                    }
                });
            });

            $('#addFollowUpNote').on('hidden.bs.modal', function() {
                $('#followup_note_id').val('');
                $('#followup_title').val('');
                $('#followup_description').val('');
                $('#followup_date').val('');
                $('#followup-note-form')[0].reset();
                $('#followup-note-form')[0].classList.remove('was-validated');
            });
        });
    </script>
@endpush
