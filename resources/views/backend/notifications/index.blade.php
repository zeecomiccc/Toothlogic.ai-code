@extends('backend.layouts.app')

@section('title', __($module_title))

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('modules/constant/style.css') }}">
@endpush

@section('content')
    <div class="card mb-4">
        <div class="card-body p-0">

            <div class="row">
                <div class="col">

                    <table id="datatable" class="table table-responsive notification-table">
                        <thead>
                            <tr>
                                <th>
                                    {{ __('notification.lbl_id') }}
                                </th>
                                <th>
                                    {{ __('notification.lbl_type') }}
                                </th>
                                <th>
                                    {{ __('notification.lbl_text') }}
                                </th>
                                <th>
                                    {{ __('notification.lbl_patient') }}
                                </th>
                                <th>
                                    {{ __('notification.lbl_update') }}
                                </th>
                                <th>
                                    {{ __('notification.lbl_action') }}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($$module_name as $module_name_singular)
                                <?php
                                $row_class = '';
                                $span_class = '';
                                if ($module_name_singular->read_at == '') {
                                    $row_class = 'table-info';
                                    $span_class = 'font-weight-bold';
                                }
                                ?>

                                <input type="hidden" id="idData" value="{{ $module_name_singular->id }}">

                                <tr class="{{ $row_class }}">
                                    <td>

                                        <!-- <a href="#">#{{ $module_name_singular->data['data']['id'] }}</a> -->
                                                                                 @if (isset($module_name_singular->data['data']['notification_group'])) 
                                             @if ($module_name_singular->data['data']['notification_group'] == 'appointment')
                                                 <a
                                                     href="{{ route('backend.appointments.clinicAppointmentDetail', ['id' => $module_name_singular->data['data']['id'] ?? '', 'notification_id' => $module_name_singular->id]) }}">#{{ $module_name_singular->data['data']['id'] ?? 'N/A' }}</a>
                                             @elseif($module_name_singular->data['data']['notification_group'] == 'requestservice')
                                                 <a
                                                     href="{{ route('backend.requestservices.index') }}">#{{ $module_name_singular->data['data']['id'] ?? 'N/A' }}</a>
                                             @else
                                                 <a
                                                     href="{{ route('backend.orders.show', ['id' => $module_name_singular->data['data']['id'] ?? '', 'notification_id' => $module_name_singular->id]) }}">#{{ $module_name_singular->data['data']['id'] ?? 'N/A' }}</a>
                                             @endif
                                         @else
                                             <span>#{{ $module_name_singular->id }}</span>
                                         @endif
                                    </td>
                                    <td>
                                        <span
                                            class="{{ $span_class }}">
                                            @if(isset($module_name_singular->data['data']['notification_group']))
                                                {{ ucfirst($module_name_singular->data['data']['notification_group']) }}
                                            @else
                                                General
                                            @endif
                                        </span>
                                    </td>
                                    @php
                                        $notification = \Modules\NotificationTemplate\Models\NotificationTemplateContentMapping::where(
                                            'subject',
                                            $module_name_singular->data['subject'],
                                        )->first();
                                    @endphp
                                    <td class="description-column">
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="text-start">
                                                <a href="#">
                                                    <h6>{{ $module_name_singular->data['subject'] ?? 'Notification' }}</h6>
                                                </a>
                                                <span
                                                    class="{{ $span_class }}">
                                                    @if($notification)
                                                         {{ $notification->notification_message }}
                                                     @else
                                                         @if(isset($module_name_singular->data['data']['message']))
                                                             {{ $module_name_singular->data['data']['message'] }}
                                                         @else
                                                             Notification message not available
                                                         @endif
                                                     @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td>
                                                <a href="#">
                                                    <span class="{{ $span_class }}">
                                                        {{ $module_name_singular->data['subject'] }}
                                                    </span>
                                                </a>
                                            </td> -->
                                    @php
                                        $user = null;
                                        if (isset($module_name_singular->data['data']['notification_group'])) {
                                            if ($module_name_singular->data['data']['notification_group'] == 'requestservice') {
                                                if (isset($module_name_singular->data['data']['vendor_id'])) {
                                                    $user = \App\Models\User::find($module_name_singular->data['data']['vendor_id']);
                                                }
                                            } else {
                                                if (isset($module_name_singular->data['data']['user_id'])) {
                                                    $user = \App\Models\User::find($module_name_singular->data['data']['user_id']);
                                                }
                                            }
                                        }
                                        
                                        // If no user found, try to get user from other possible fields
                                        if (!$user) {
                                            if (isset($module_name_singular->data['data']['person_id'])) {
                                                $user = \App\Models\User::find($module_name_singular->data['data']['person_id']);
                                            } elseif (isset($module_name_singular->data['data']['doctor_id'])) {
                                                $user = \App\Models\User::find($module_name_singular->data['data']['doctor_id']);
                                            } elseif (isset($module_name_singular->data['data']['receptionist_id'])) {
                                                $user = \App\Models\User::find($module_name_singular->data['data']['receptionist_id']);
                                            }
                                        }
                                    @endphp


                                    <td>
                                        <div class="d-flex gap-3 align-items-center">
                                            @if($user)
                                                <img src="{{ $user->profile_image ?? default_user_avatar() }}" alt="avatar"
                                                    class="avatar avatar-40 rounded-pill">
                                                <div class="text-start">
                                                    <h6 class="m-0">{{ $user->full_name ?? default_user_name() }}</h6>
                                                    <span>{{ $user->email ?? '--' }}</span>
                                                </div>
                                            @else
                                                <img src="{{ default_user_avatar() }}" alt="avatar"
                                                    class="avatar avatar-40 rounded-pill">
                                                <div class="text-start">
                                                    <h6 class="m-0">{{ default_user_name() }}</h6>
                                                    <span>User not found</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $module_name_singular->created_at->diffForHumans() }}
                                    </td>

                                    <td>
                                        <a onclick="remove_notification()"
                                            id="delete-{{ $module_name }}-{{ $module_name_singular->id }}"
                                            class="fs-4 text-danger" data-type="ajax" data-method="DELETE"
                                            data-token="{{ csrf_token() }}" data-bs-toggle="tooltip"
                                            title="{{ __('Delete') }}" data-confirm="{{ __('messages.are_you_sure?') }}">
                                            <i class="ph ph-trash"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; vertical-align: middle;">
                                        {{ __('messages.No_data_found') }}
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-7">

                    <!-- <div class="float-left">

                                    {{ __('Total') }} {{ $$module_name->total() }} {{ __($module_name) }}
                                </div> -->
                    <form id="paginationForm" method="GET" action="{{ url()->current() }}" class="d-inline">
                        <label for="perPageSelect" class="me-2">Show</label>
                        <select name="per_page" id="perPageSelect" class="form-select form-select-sm d-inline w-auto"
                            onchange="document.getElementById('paginationForm').submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="ms-2">entries</span>
                    </form>
                    Showing {{ $$module_name->firstItem() }} to {{ $$module_name->lastItem() }} of
                    {{ $$module_name->total() }} entries
                </div>
                <div class="col-5">
                    <div class="float-end">
                        {!! $$module_name->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('after-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
        <script src="{{ mix('js/vue.min.js') }}"></script>
        <script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>

        <script>
            function remove_notification() {

                var id = document.getElementById('idData').value;
                var url = "{{ route('notification.remove', ['id' => ':id']) }}";
                url = url.replace(':id', id);

                var message = 'Are you certain you want to delete it?';
                confirmSwal(message).then((result) => {
                    if (!result.isConfirmed) return
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted',
                                text: response.message,
                                icon: 'success'
                            })
                            window.location.reload();
                            successSnackbar(response.message);
                        },
                        error: function(response) {
                            alert('error');
                        }
                    });
                })


            }
        </script>
    @endpush
@endsection
