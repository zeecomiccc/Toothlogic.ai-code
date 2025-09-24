@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection


@push('after-styles')
    <link rel="stylesheet" href="{{ mix('modules/constant/style.css') }}">
@endpush
@section('content')
    <div class="table-content mb-5">
        <x-backend.section-header>
            <div class="d-flex flex-wrap gap-3">
                @if(auth()->user()->can('edit_{{module}}') || auth()->user()->can('delete_{{module}}'))
                <x-backend.quick-action url='{{ route("backend.subscription.$module_name.bulk_action") }}'>
                    <div>
                        <select name="action_type" class="form-control select2 col-12" id="quick-action-type"
                            style="width:100%">
                            <option value="">{{ __('messages.no_action') }}</option>
                            @can('edit_subscription')
                            <option value="change-status">{{ __('messages.status') }}</option>
                            @endcan
                            @can('delete_subscription')
                            <option value="delete">{{ __('messages.delete') }}</option>
                            @endcan
                        </select>
                    </div>
                    <div class="select-status d-none quick-action-field" id="change-status-action">
                        <select name="status" class="form-control select2" id="status" style="width:100%">
                            <option value="" selected>{{ __('messages.select_status') }}</option>
                            <option value="1">{{ __('messages.active') }}</option>
                            <option value="0">{{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                </x-backend.quick-action>
                @endif
                <div>
                    <button type="button" class="btn btn-primary" data-modal="export">
                    <i class="ph ph-download-simple me-1"></i> {{ __('messages.export') }}
                    </button>
                    {{-- <button type="button" class="btn btn-secondary" data-modal="import">--}}
                    {{-- <i class="fa-solid fa-upload"></i> Import--}}
                    {{-- </button>--}}
                </div>
            </div>
            <x-slot name="toolbar">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i
                            class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..." aria-label="Search"
                        aria-describedby="addon-wrapping">
                </div>
                @hasPermission('add_plan_list')
                    <x-buttons.offcanvas target='#form-offcanvas'
                        title="{{ __('messages.create') }} {{ __($module_title) }}">{{ __('messages.create') }}
                        {{ __($module_title) }}</x-buttons.offcanvas>
                @endhasPermission
                {{-- <button class="btn btn-outline-primary btn-icon" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i
                        class="fa-solid fa-filter"></i></button> --}}
            </x-slot>
        </x-backend.section-header>
        <table id="datatable" class="table table-responsive">
        </table>
    </div>

    <div data-render="app">

        <plan-offcanvas create-title="{{ __('messages.create') }} {{ __('messages.new') }} {{ __($module_title) }}"
            edit-title="{{ __('messages.edit') }} {{ __($module_title) }}"
            :customefield="{{ json_encode($customefield) }}" >
        </plan-offcanvas>

        <x-backend.advance-filter>
            <x-slot name="title">
                <h4>Advanced Filter</h4>
            </x-slot>
            <select name="" id="" class="select2">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </x-backend.advance-filter>
    </div>
@endsection

@push('after-styles')
    <!-- DataTables Core and Extensions -->
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
    <script src="{{ mix('modules/subscriptions/script.js') }}"></script>
    <script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>
    <script src="{{ asset('js/form-modal/index.js') }}" defer></script>

    <!-- DataTables Core and Extensions -->
    <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" defer>
        const columns = [
            {
                name: 'check',
                data: 'check',
                title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
                width: '0%',
                exportable: false,
                orderable: false,
                searchable: false,
            },
            { data: 'name', name: 'name', title: "{{ __('plan.lbl_name') }}"  },
            { data: 'type', name: 'type', title: "{{ __('plan.lbl_type') }}" },
            { data: 'duration', name: 'duration', title: "{{ __('plan.lbl_duration') }}" },
            { data: 'amount', name: 'amount', title: "{{ __('plan.lbl_amount') }}" },
            { data: 'planlimitation', name: 'planlimitation',  title: "{{ __('plan.lbl_plan_limitation') }}"},
            { data: 'status', name: 'status', orderable: false, searchable: true, title: "{{ __('plan.lbl_status') }}" },
        ]

        const actionColumn = [
            { data: 'action', name: 'action', orderable: false, searchable: false, title: 'Action' }
        ]

        const customFieldColumns = JSON.parse(@json($columns))

        let finalColumns = [
            ...columns,
            ...customFieldColumns,
            ...actionColumn
        ]

        document.addEventListener('DOMContentLoaded', (event) => {
            initDatatable({
                url: '{{ route("backend.subscription.$module_name.index_data") }}',
                finalColumns,
            })
        })

        function resetQuickAction() {
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

        $('#quick-action-type').change(function() {
            resetQuickAction()
        });

        $(document).on('update_quick_action', function() {
            // resetActionButtons()
        })
    </script>
@endpush
