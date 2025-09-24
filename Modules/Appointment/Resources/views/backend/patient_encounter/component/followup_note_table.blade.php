<div class="table-responsive rounded mb-0">
    <table class="table table-lg m-0" id="followup_note_table">
        <thead>
            <tr class="text-white">
                <th>#</th>
                <th>{{ __('appointment.title') }}</th>
                <th>{{ __('appointment.date') }}</th>
                <th>{{ __('appointment.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($followup_notes as $index => $note)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $note->title }}</td>
                    <td>{{ $note->date }}</td>
                    <td class="action">
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="btn text-primary p-0 fs-5 me-2" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" onclick="editFollowUpNote({{ $note->id }})"
                                aria-controls="form-offcanvas">
                                <i class="ph ph-pencil-simple-line"></i>
                            </button>
                            <button type="button" class="btn text-danger p-0 fs-5"
                                onclick="deleteFollowUpNote({{ $note->id }}, 'Are you sure you want to delete it?')"
                                data-bs-toggle="tooltip">
                                <i class="ph ph-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="my-1 text-danger text-center">{{ __('appointment.no_follow_up_notes') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
