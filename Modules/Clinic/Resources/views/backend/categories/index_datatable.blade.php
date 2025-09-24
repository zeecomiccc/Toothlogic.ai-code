@extends('backend.layouts.app')

@section('title')
   {{ __($module_title) }}
@endsection

@push('after-styles')
<link rel="stylesheet" href="{{ mix('modules/clinic/style.css') }}">
@endpush

@section('content')
<div class="table-content mb-5">
    <x-backend.section-header>
        <div class="d-flex flex-wrap gap-3">
            @if(auth()->user()->can('edit_clinics_category') || auth()->user()->can('delete_clinics_category'))
            <x-backend.quick-action url='{{route("backend.category.bulk_action")}}'>
                <div class="">
                    <select name="action_type" class="form-control select2 col-12" id="quick-action-type" style="width:100%">
                        <option value="">{{ __('messages.no_action') }}</option>
                        @can('edit_clinics_category')
                        <option value="change-status">{{ __('messages.status') }}</option>
                        <option value="change-featured">{{ __('messages.featured') }}</option>
                        @endcan
                        @can('delete_clinics_category')
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

                <div class="select-featured d-none quick-action-field" id="change-featured-action">
                    <select name="featured" class="form-control select2" id="featured" style="width:100%">
                        <option value="1">{{ __('messages.yes') }}</option>
                        <option value="0">{{ __('messages.no') }}</option>
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
            <div>
                <div class="datatable-filter" style="width: 100%; display: inline-block;">
                    {{$filter['status']}}
                    <select name="column_status" id="column_status" class="select2 form-control" data-filter="select" style="width:100%">
                        <option value="" disabled selected>{{ __('messages.change_status') }}</option>
                        <option value="">{{ __('messages.all') }}</option>
                        <option value="1" {{$filter['status'] == '1' ? "selected" : ''}}>{{ __('messages.active') }}</option>
                        <option value="0" {{$filter['status'] == '0' ? "selected" : ''}}>{{ __('messages.inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..." aria-label="Search" aria-describedby="addon-wrapping">
            </div>
            <button class="btn btn-secondary d-flex align-items-center gap-1 btn-group" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i class="ph ph-funnel"></i>{{__('messages.advance_filter')}}</button>
            @hasPermission('add_clinics_category')
            <button type="button" class="btn btn-primary d-flex align-items-center gap-1" data-crud-id="{{0}}"><i class="ph ph-plus-circle"></i> {{ __('messages.new') }} </button>
            @endhasPermission

        </x-slot>
    </x-backend.section-header>
    <table id="datatable" class="table table-responsive">
    </table>
</div>

<div data-render="app">
    <clinic-category-offcanvas custom-data="{{ isset($data) ? json_encode($data) : '{}' }}" default-image="{{default_file_url()}}" create-title="{{ __('messages.create') }} {{ __('category.singular_title') }}" edit-title="{{ __('messages.edit') }} {{ __('category.singular_title') }}" :customefield="{{ json_encode($customefield) }}">
    </clinic-category-offcanvas>
</div>
<x-backend.advance-filter>
    <x-slot name="title">
        <h4>{{ __('service.lbl_advanced_filter') }}</h4>
    </x-slot>


    <div class="form-group datatable-filter">
        <label class="form-label" for="form-label"> {{ __('category.lbl_category') }}</label>
        <select id="clinic_category" name="clinic_category" data-filter="select" class="select2 form-control" data-ajax--url="{{ route('backend.get_search_data', ['type' => 'clinic_category']) }}" data-ajax--cache="true">
        </select>
    </div>

   {{-- @if($parentcategory->isNotEmpty())
     <div class="form-group datatable-filter" id="subcategory-group">
        <label class="form-label" for="column_subcategory">{{ __('service.lbl_subcategory') }}</label>
        <select name="column_subcategory" id="column_subcategory" class="form-control select2" data-filter="select">
            <option value="">{{ __('service.all') }} {{ __('service.lbl_subcategory') }}</option>
            @foreach ($parentcategory as $parentcategory)
            <option value="{{ $parentcategory->id }}">{{ $parentcategory->name }}</option>
            @endforeach
        </select>
    </div>
    @endif --}}
    <button type="reset" class="btn btn-danger" id="reset-filter">{{ __('appointment.reset') }}</button>
</x-backend.advance-filter>
@endsection

@push('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
<script src="{{ mix('modules/clinic/script.js') }}"></script>
<script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>
<script src="{{ asset('js/form-modal/index.js') }}" defer></script>


<!-- DataTables Core and Extensions -->
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

<script type="text/javascript" defer>
    const columns = [{
            name: 'check',
            data: 'check',
            title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
            width: '2%',
            exportable: false,
            orderable: false,
            searchable: false,
        },
        {
            data: 'name',
            name: 'name',
            title: "{{ __('category.lbl_name') }}",
            width: '15%'
        },

        // {
        //     data: 'parent_id',
        //     name: 'parent_id',
        //     title: "{{ __('category.parent_category') }}",
        //     width: '15%'
        // },

        {
            data: 'featured',
            name: 'featured',
            orderable: true,
            searchable: false,
            title: "{{ __('messages.featured') }}",
            width: '5%'
        },

        {
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false,
            title: "{{ __('category.lbl_status') }}",
            width: '5%'
        },
        {
            data: 'updated_at',
            name: 'updated_at',
            title: "{{ __('service.lbl_update_at') }}",
            orderable: true,
            visible: false,
        },


    ]

    const actionColumn = [{
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false,
        title: "{{ __('category.lbl_action') }}",
        width: '5%'
    }]

    const customFieldColumns = JSON.parse(@json($columns))

    let finalColumns = [
        ...columns,
        ...customFieldColumns,
        ...actionColumn
    ]

    document.addEventListener('DOMContentLoaded', (event) => {
        initDatatable({
            url: '{{ route("backend.$module_name.index_data") }}',
            finalColumns,
            orderColumn: [
                [5, "desc"]
            ],
            advanceFilter: () => {
                return {
                    parent_category: $('#column_subcategory').val(),
                    vendor_id: $('#vendor').val(),

                    clinic_category: $('#clinic_category').val(),

                };
            }
        });
        $('#reset-filter').on('click', function(e) {
            $('#column_subcategory').val('');
            $('#clinic_category').val('');
            window.renderedDataTable.ajax.reload(null, false);
        });
    })


    const formOffcanvas = document.getElementById('clinic-category-offcanvas')
    const instance = bootstrap.Offcanvas.getOrCreateInstance(formOffcanvas)

    $(document).on('click', '[data-crud-id]', function() {
        setEditID($(this).attr('data-crud-id'), $(this).attr('data-parent-id'))
    })

    function setEditID(id, parent_id) {
        if (id !== '' || parent_id !== '') {
            const idEvent = new CustomEvent('crud_change_id', {
                detail: {
                    form_id: id,
                    parent_id: parent_id
                }
            })
            document.dispatchEvent(idEvent)
        } else {
            removeEditID()
        }
        instance.show()
    }

    function removeEditID() {
        const idEvent = new CustomEvent('crud_change_id', {
            detail: {
                form_id: 0,
                parent_id: null
            }
        })
        document.dispatchEvent(idEvent)
    }

    formOffcanvas?.addEventListener('hidden.bs.offcanvas', event => {
        removeEditID()
    })


    function resetQuickAction() {
        const actionValue = $('#quick-action-type').val();
        if (actionValue != '') {
            $('#quick-action-apply').removeAttr('disabled');

            if (actionValue == 'change-status') {
                $('.quick-action-field').addClass('d-none');
                $('#change-status-action').removeClass('d-none');
            } else if (actionValue == 'change-featured') {

                $('.quick-action-field').addClass('d-none');
                $('#change-featured-action').removeClass('d-none');

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
    document.addEventListener('DOMContentLoaded', function() {
        var type = '<?php echo isset($type) ? $type : ''; ?>';
        var data = '<?php echo isset($data) ? json_encode($data) : ''; ?>';
        console.log(type === 'category')
        if (type === 'category') {
            var myOffcanvas = document.getElementById('clinic-category-offcanvas')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
            bsOffcanvas.show()
        } else {

        }
    });
</script>
@endpush
