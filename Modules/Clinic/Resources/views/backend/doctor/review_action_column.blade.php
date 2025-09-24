<div class="d-flex gap-3 align-items-center">
    <a href="javascript:void(0)" onclick="showReview({{ $data->id }})" class="fs-4 text-primary" data-bs-toggle="tooltip" title="{{ __('View') }}">
        <i class="ph ph-eye align-middle"></i>
    </a>
{{-- @hasPermission('delete_reviews') --}}
        <a href="{{route("backend.doctor.destroy_review", $data->id)}}" id="delete-{{$module_name}}-{{$data->id}}" class="fs-4 text-danger" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}">  <i class="ph ph-trash align-middle"></i></a>
    {{-- @endhasPermission --}}
</div>
