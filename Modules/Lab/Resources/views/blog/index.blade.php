@extends('backend.layouts.app')

{{-- @section('title')
    Blog
@endsection --}}
@section('title') {{ __($module_title) }} @endsection


@section('content')
    <div class="card-main mb-5">
          <x-backend.section-header>
            <div class="d-flex flex-wrap gap-3">
                {{-- @if(auth()->user()->can('edit_' . $module_name) || auth()->user()->can('delete_' . $module_name)) --}}
                <x-backend.quick-action url="{{ route('backend.' . $module_name . '.bulk_action') }}">
                    <div class="">
                        <select name="action_type" class="form-control select2 col-12" id="quick-action-type" style="width:100%">
                            <option value="">{{ __('messages.no_action') }}</option>
                            {{-- @can('edit_' . $module_name) --}}
                            <option value="change-status">{{ __('messages.lbl_status') }}</option>
                            {{-- @endcan
                            @can('delete_' . $module_name) --}}
                            <option value="delete">{{ __('messages.delete') }}</option>
                            {{-- @endcan
                            @can('restore_' . $module_name) --}}
                            <option value="restore">{{ __('messages.restore') }}</option>
                            {{-- @endcan
                            @can('force_delete_' . $module_name) --}}
                            <option value="permanently-delete">{{ __('messages.permanent_delete') }}</option>
                            {{-- @endcan --}}
                        </select>
                    </div>
                    <div class="select-status d-none quick-action-field" id="change-status-action">
                        <select name="status" class="form-control select2" id="status" style="width:100%">
                            <option value="1" selected>{{ __('messages.active') }}</option>
                            <option value="0">{{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                </x-backend.quick-action>
                {{-- @endif --}}

               
            </div>

            <x-slot name="toolbar">
                <div>
                    <div class="datatable-filter">
                        <select name="column_status" id="column_status" class="select2 form-control" data-filter="select" style="width: 100%">
                            <option value="">{{__('messages.all')}}</option>
                            <option value="0" {{ $filter['status'] == '0' ? 'selected' : '' }}>
                                {{ __('messages.inactive') }}</option>
                            <option value="1" {{ $filter['status'] == '1' ? 'selected' : '' }}>
                                {{ __('messages.active') }}</option>
                        </select>
                    </div>
                </div>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text pe-0" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control dt-search" placeholder="{{__('messages.search')}}" aria-label="Search" aria-describedby="addon-wrapping">
                </div>

                 {{-- @can('add_' . $module_name) --}}
                 <a href="{{ route('backend.' . $module_name . '.create') }}" class="btn btn-primary d-flex align-items-center gap-1" id="add-post-button">
                    <i class="ph ph-plus-circle"></i>{{ __('messages.new') }}
                </a>
                {{-- @endcan --}}
            </x-slot>
        </x-backend.section-header>
          
          
        <table id="datatable" class="table table-responsive">
            <!-- Table header with the required fields -->
        </table>
    </div>
    
    @if(session('success'))
        <div class="snackbar" id="snackbar">
            <div class="d-flex justify-content-around align-items-center">
                <p class="mb-0">{{ session('success') }}</p>
                <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
            </div>
        </div>
    @endif
@endsection

@push('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
<!-- DataTables Core and Extensions -->
<script src="{{ asset('js/form-modal/index.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
<script type="text/javascript" defer>
    const columns = [
        {
            name: 'check',
            data: 'check',
            title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="blog" onclick="selectAllTable(this)">',
            exportable: false,
            orderable: false,
            searchable: false,
        },
        {
            data: 'updated_at',
            name: 'updated_at',
            title: "{{ __('product.lbl_update_at') }}",
            orderable: true,
            visible: false,
        },
        {
            data: 'title',
            name: 'title',
            title: "{{ __('messages.title') }}"
        },
        {
            data: 'author_id',
            name: 'author_id',
            title: "{{ __('messages.author') }}"
        },
        {
            data: 'total_views',
            name: 'total_views',
            title: "{{ __('messages.total_views') }}"
        },
        {
            data: 'status',
            name: 'status',
            title: "{{ __('messages.status') }}"
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            title: "{{ __('messages.action') }}",
            className: 'text-end'
        }
    ]

    document.addEventListener('DOMContentLoaded', (event) => {
        initDatatable({
            url: '{{ route("backend.blog.index_data") }}',
            finalColumns: columns,
            orderColumn: [[4, "desc"]],
            advanceFilter: () => {
                return {
                    name: $('#user_name').val()
                }
            }
        });
    })

    $('#reset-filter').on('click', function(e) {
        $('#user_name').val('')
        window.renderedDataTable.ajax.reload(null, false)
    })

    function resetQuickAction () {
        const actionValue = $('#quick-action-type').val();
        if (actionValue != '') {
            $('#quick-action-apply').removeAttr('disabled');

            if (actionValue == 'change-status') {
                $('.quick-action-field').addClass('d-none');
                $('#change-status-action').removeClass('d-none');
            } else {
                $('.quick-action-field').addClass('d-none');
            }
        } else {
            $('#quick-action-apply').attr('disabled', true);
            $('.quick-action-field').addClass('d-none');
        }
      }

      $('#quick-action-type').change(function () {
        resetQuickAction()
      });

      $(document).on('update_quick_action', function() {
        // resetActionButtons()
    })
</script>
@endpush
