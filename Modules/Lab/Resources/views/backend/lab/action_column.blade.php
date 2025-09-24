<div class="d-flex gap-3 align-items-center">
    @hasPermission('view_lab')
    <button type="button" class="btn p-0 btn-icon text-primary border-0 bg-transparent text-nowrap"
        onclick="showLab({{ $data->id }})" data-bs-toggle="tooltip" title="{{ __('messages.view') }}">
        <i class="ph ph-eye"></i>
    </button>
    @endhasPermission

    @hasPermission('edit_lab')
    <button type="button" class="btn text-success p-0 fs-5" onclick="editLab({{ $data->id }})"
        title="{{ __('messages.edit') }}" data-bs-toggle="tooltip">
        <i class="ph ph-pencil-simple-line"></i>
    </button>
    @endhasPermission

    @hasPermission('delete_lab')
    <a href="{{ route('backend.lab.destroy', $data->id) }}" id="delete-lab-{{ $data->id }}"
        class="btn text-danger p-0 fs-5" data-type="ajax" data-method="DELETE" data-token="{{ csrf_token() }}"
        data-bs-toggle="tooltip" title="{{ __('messages.delete') }}"
        data-confirm="{{ __('messages.are_you_sure?', ['form' => $data->id ?? __('Unknown'), 'module' => __('sidebar.lab')]) }}">
        <i class="ph ph-trash"></i>
    </a>
    @endhasPermission

    {{-- Print Button --}}
    @hasPermission('view_lab')
    <button type="button" class="btn text-info p-0 fs-5" onclick="printLab({{ $data->id }})" title="Print"
        data-bs-toggle="tooltip">
        <i class="ph ph-printer"></i>
    </button>
    @endhasPermission

    {{-- Download PDF Button --}}
    @hasPermission('view_lab')
    <button type="button" class="btn text-success p-0 fs-5" onclick="downloadLabPDF({{ $data->id }})"
        title="Download PDF" data-bs-toggle="tooltip">
        <i class="ph ph-file-pdf"></i>
    </button>
    @endhasPermission
</div>