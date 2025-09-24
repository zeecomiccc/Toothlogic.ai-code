<div class="table-responsive rounded mb-0">
    <table class="table table-lg m-0" id="ortho_daily_record_table">
        <thead>
            <tr class="text-white">
                <th>{{ __('appointment.date') }}</th>
                <th>{{ __('appointment.procedure_performed') }}</th>
                <th>{{ __('appointment.oral_hygiene_status') }}</th>
                <th>{{ __('appointment.initials') }}</th>
                <th>{{ __('appointment.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orthodontic_daily_records as $record)
                <tr data-record-id="{{ $record->id }}">
                    <td>{{ $record->date }}</td>
                    <td>{{ Illuminate\Support\Str::limit($record->procedure_performed, 20, '...') }}</td>
                    <td>{{ Illuminate\Support\Str::limit($record->oral_hygiene_status, 20, '...') }}</td>
                    <td>{{ Illuminate\Support\Str::limit($record->initials, 20, '...') }}</td>
                    <td class="action">
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="btn text-primary p-0 fs-5 me-2"
                                onclick="editOrthoRecord({{ $record->id }})" data-id="{{ $record->id }}"
                                aria-controls="form-offcanvas">
                                <i class="ph ph-pencil-simple-line"></i>
                            </button>
                            <button type="button" class="btn text-danger p-0 fs-5" data-bs-toggle="tooltip"
                                onclick="deleteOrthoRecord({{ $record->id }})" data-id="{{ $record->id }}">
                                <i class="ph ph-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            @if (count($orthodontic_daily_records) <= 0)
                <tr>
                    <td colspan="8">
                        <div class="my-1 text-danger text-center">No orthodontic treatment daily records found.
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <div id="ortho-record-error-message" class="alert alert-danger mt-2 d-none"></div>
</div>
