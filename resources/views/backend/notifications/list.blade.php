<div class="card-header border-bottom p-3">
    <h6 class="mb-0">{{ __('messages.all_notifications') }} ({{ $all_unread_count }})</h6>
</div>

<div class="card-body overflow-auto card-header-border p-0 card-body-list max-17 scroll-thin">
    <div class="dropdown-menu-1 overflow-y-auto list-style-1 mb-0 notification-height">
        @if (isset($notifications) && count($notifications) > 0)

            @foreach ($notifications->sortByDesc('created_at')->take(5) as $notification)
                @php
                    $timezone = App\Models\Setting::where('name', 'default_time_zone')->value('val') ?? 'UTC';
                    $notification->created_at = $notification->created_at->setTimezone($timezone);
                    $notification->updated_at = $notification->updated_at->setTimezone($timezone);
                    
                    // Safely get notification data
                    $notificationData = $notification->data;
                    $notification_type = '';
                    $notification_group = '';
                    
                    if (isset($notificationData['data'])) {
                        // New structure (nested data)
                        $notification_type = ucwords(str_replace('_', ' ', $notificationData['data']['notification_type'] ?? 'general')) . '!';
                        $notification_group = $notificationData['data']['notification_group'] ?? 'general';
                    } else {
                        // Old structure (direct data)
                        $notification_type = ucwords(str_replace('_', ' ', $notificationData['notification_type'] ?? 'general')) . '!';
                        $notification_group = $notificationData['notification_group'] ?? 'general';
                    }
                @endphp
                @if ($notification_group == 'appointment')
                    <div
                        class="dropdown-item-1 float-none p-3 list-unstyled iq-sub-card  {{ $notification->read_at ? '' : 'notify-list-bg' }} ">
                      


                            @if(auth()->user()->hasRole('user'))
                            <a href="{{ route('appointment-details', ['id' => $notificationData['data']['id'] ?? $notificationData['id'] ?? 'N/A', 'notification_id' => $notification->id]) }}"
                                class="">
                        @else
                            <a href="{{ route('backend.appointments.clinicAppointmentDetail', ['id' => $notificationData['data']['id'] ?? $notificationData['id'] ?? 'N/A', 'notification_id' => $notification->id]) }}"
                                class="">
                        @endif 
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0 font-size-14">{{ $notification_type }}</h6>
                                <h6 class="mb-0 font-size-14">#{{ $notificationData['data']['id'] ?? $notificationData['id'] ?? 'N/A' }}</h6>
                            </div>
                            <div class="list-item d-flex column-gap-3 row-gap-1">
                                <div>
                                    <button type="button" class="btn bg-primary-subtle btn-icon rounded-pill">
                                        {{ strtoupper(substr($notification->data['data']['user_name'], 0, 1)) }}
                                    </button>
                                </div>
                                <div class="list-style-detail">
                                    @if ($notification->data['data']['notification_type'] == 'new_appointment')
                                        <p class="text-body font-size-14 mb-1">Appointment received for <span
                                                class="text-primary">{{ $notification->data['data']['appointment_services_names'] }}</span>
                                            service by <span
                                                class="text-black">{{ $notification->data['data']['user_name'] }}</span>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body mb-0 font-size-14">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body mb-0 font-size-14">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'accept_appointment')
                                        <p class="text-body font-size-14 mb-1">Appointment <span
                                                class="text-primary">#{{ $notification->data['data']['id'] }}</span>
                                            has been Accepted.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'reject_appointment')
                                        <p class="text-body font-size-14 mb-1">Appointment <span
                                                class="text-primary">#{{ $notification->data['data']['id'] }}</span>
                                            has been Rejected.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'complete_appointment')
                                        <p class="text-body font-size-14 mb-1">Appointment <span
                                                class="text-primary">#{{ $notification->data['data']['id'] }}</span>
                                            has been Completed.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'cancel_appointment')
                                        <p class="text-body font-size-14 mb-1">Appointment <span
                                                class="text-primary">#{{ $notification->data['data']['id'] }}</span>
                                            has been Cancelled.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'accept_appointment_request')
                                        <p class="text-body font-size-14 mb-1">Appointment Request <span
                                                class="text-primary">#{{ $notification->data['data']['id'] }}</span>
                                            has been Accepted.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'reschedule_appointment')
                                        <p class="text-body font-size-14 mb-1">Appointment <span
                                                class="text-primary">#{{ $notification->data['data']['id'] }}</span>
                                            has been Rescheduled.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'checkout_appointment')
                                        <p class="text-body font-size-14 mb-1">Appointment <span
                                                class="text-primary">#{{ $notification->data['data']['id'] }}</span>
                                            has been Completed.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                  
                                        @elseif($notification->data['data']['notification_type'] == 'wallet_refund')
                                        <p class="text-body font-size-14 mb-1">wallet refund added.
                                            </p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>


                                        
                                    @endif

                                </div>
                            </div>
                        </a>
                    </div>
                @elseif($notification->data['data']['notification_group'] == 'requestservice')
                    @php

                        $user_id = $notification->data['data']['vendor_id'];
                        $username = App\Models\User::where('id', $user_id)->pluck('first_name');
                    @endphp
                    <div
                        class="dropdown-item-1 float-none p-3 list-unstyled iq-sub-card  {{ $notification->read_at ? '' : 'notify-list-bg' }} ">
                        <a href="" class="">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0">{{ $notification_type }}</h6>
                                <h6 class="mb-0">#{{ $notification->data['data']['id'] }}</h6>

                            </div>
                            <div class="list-item d-flex column-gap-3 row-gap-1">
                                <div>
                                    <button type="button" class="btn bg-primary-subtle btn-icon rounded-pill">
                                        {{ strtoupper(substr($username, 0, 1)) }}
                                    </button>
                                </div>
                                <div class="list-style-detail">
                                    @if ($notification->data['data']['notification_type'] == 'new_request_service')
                                        <p class="text-body font-size-14 mb-1">New Service Request <span
                                                class="text-primary">{{ $notification->data['data']['name'] }}</span>
                                            added by <span
                                                class="text-black">{{ $notification->data['data']['logged_in_user_fullname'] }}</span>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'accept_request_service')
                                        <p class="text-body font-size-14 mb-1">Accept Service Request <span
                                                class="text-primary">{{ $notification->data['data']['name'] }}</span>
                                            by <span
                                                class="text-black">{{ $notification->data['data']['logged_in_user_fullname'] }}</span>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'reject_request_service')
                                        <p class="text-body font-size-14 mb-1">Rejected Service Request <span
                                                class="text-primary">{{ $notification->data['data']['name'] }}</span>
                                            by <span
                                                class="text-black">{{ $notification->data['data']['logged_in_user_fullname'] }}</span>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @elseif($notification->data['data']['notification_group'] == 'order')
                    @php
                        $user = auth()->user();
                        $item_id = $notification->data['data']['item_id'] ?? null;

                        if ($user->hasRole('pet_store')) {
                            $order_id = $notification->data['data']['id'];
                            if ($order_id) {
                                $orderItem = Modules\Product\Models\OrderItem::where('order_id', $order_id)
                                    ->where('vendor_id', $user->id)
                                    ->first();
                                $item_id = $orderItem->id;
                            }
                        }
                    @endphp
                    <div
                        class="dropdown-item-1 float-none p-3 list-unstyled iq-sub-card  {{ $notification->read_at ? '' : 'notify-list-bg' }} ">
                        <a href="{{ route('backend.orders.show', ['id' => $item_id, 'notification_id' => $notification->id]) }}">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0">{{ $notification_type }}</h6>
                                <h6 class="mb-0">{{ $notification->data['data']['order_code'] ?? null }} </h6>
                            </div>
                            <div class="list-item d-flex column-gap-3 row-gap-1">
                                <div>
                                    @if ($notification->data['data']['notification_group'] == 'order')
                                        <button type="button" class="btn bg-primary-subtle btn-icon rounded-pill">
                                            {{ strtoupper(substr($notification->data['data']['user_name'], 0, 1)) }}
                                        </button>
                                    @endif
                                </div>
                                <div class="list-style-detail">
                                    @if ($notification->data['data']['notification_type'] == 'order_placed')
                                        <p class="text-body font-size-14 mb-1">New Order received from <span
                                                class="text-black">{{ $notification->data['data']['user_name'] }}.</span>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'order_accepted')
                                        <p class="text-body font-size-14 mb-1">Order <span
                                                class="text-black">{{ $notification->data['data']['order_code'] }}</span>
                                            has been Accepted.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'order_proccessing')
                                        <p class="text-body font-size-14 mb-1">Order <span
                                                class="text-black">{{ $notification->data['data']['order_code'] }}</span>
                                            has been Processing.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'order_delivered')
                                        <p class="text-body font-size-14 mb-1">Order <span
                                                class="text-black">{{ $notification->data['data']['order_code'] }}
                                            </span> has been Delivered.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @elseif($notification->data['data']['notification_type'] == 'order_cancelled')
                                        <p class="text-body font-size-14 mb-1">Order <span
                                                class="text-black">{{ $notification->data['data']['order_code'] }}
                                            </span> has been Cancelled.</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </a>
                    </div>

                    @elseif ($notification->data['data']['notification_group'] == 'wallet')
                    <div
                        class="dropdown-item-1 float-none p-3 list-unstyled iq-sub-card  {{ $notification->read_at ? '' : 'notify-list-bg' }} ">
                        <a href="{{ route('wallet-history') }}"
                            class="">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0 font-size-14">{{ $notification_type }}</h6>
                                <h6 class="mb-0 font-size-14">#{{ $notification->data['data']['id'] }}</h6>
                            </div>
                            <div class="list-item d-flex column-gap-3 row-gap-1">
                                
                                <div class="list-style-detail">
                                  
                                        @if($notification->data['data']['notification_type'] == 'wallet_refund')

                                   


                                        <p class="text-body font-size-14 mb-1">{{($notification->data['data']['notification_msg']) ?? 'Wallet refund added ' }}
                                            </p>
                                        <div class="d-flex justify-content-between gap-2">
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('d/m/Y') }}</p>
                                            <p class="text-body font-size-14 mb-0">{{ $notification->created_at->format('h:i A') }}</p>
                                        </div>


                                        
                                    @endif

                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
            
            {{-- Handle follow-up note notifications --}}
            @foreach ($notifications->sortByDesc('created_at')->take(5) as $notification)
                @if (isset($notification->data['data']['notification_type']) && $notification->data['data']['notification_type'] == 'follow_up_note')
                    <div class="dropdown-item-1 float-none p-3 list-unstyled iq-sub-card {{ $notification->read_at ? '' : 'notify-list-bg' }}">
                        <a href="#" class="">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0 font-size-14">Follow-up Note!</h6>
                                <h6 class="mb-0 font-size-14">#{{ $notification->data['data']['follow_up_note_id'] ?? 'N/A' }}</h6>
                            </div>
                            <div class="list-item d-flex column-gap-3 row-gap-1">
                                <div>
                                    <button type="button" class="btn bg-primary-subtle btn-icon rounded-pill">
                                        {{ $notification->data['data']['user_avatar'] ?? '?' }}
                                    </button>
                                </div>
                                <div class="list-style-detail">
                                    <p class="text-body font-size-14 mb-1">Follow-up note received for <span class="text-primary">{{ $notification->data['data']['follow_up_note_title'] ?? 'N/A' }}</span> for patient <span class="text-black">{{ $notification->data['data']['user_name'] ?? 'N/A' }}</span></p>
                                    <div class="d-flex justify-content-between">
                                        <p class="text-body mb-0 font-size-14">{{ $notification->data['data']['follow_up_date'] ?? $notification->created_at->format('d/m/Y') }}</p>
                                        <p class="text-body mb-0 font-size-14">{{ $notification->created_at->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        @else
            <li class="list-unstyled dropdown-item-1 float-none p-3">
                <div class="list-item d-flex justify-content-center align-items-center">
                    <div class="list-style-detail text-center">
                        <h6 class="font-weight-bold mb-0">{{ __('messages.no_notification') }}</h6>
                    </div>
                </div>
            </li>
        @endif
    </div>
</div>
<div class="card-footer py-3 border-top">
    <div class="d-flex align-items-center justify-content-between">
        @if ($all_unread_count > 0)
            <a href="{{ route('backend.notifications.markAllAsRead') }}" data-type="markas_read"
                class="text-primary mb-0 notifyList pull-right"><span>{{ __('messages.mark_all_as_read') }}</span></a>
        @endif
        @if (isset($notifications) && count($notifications) > 0)
            @if(auth()->user()->hasRole('user'))
                <a href="{{ route('user-notifications') }}"
                    class="btn btn-sm btn-primary p-2">{{ __('messages.view_all') }}</a>
            @elseif(auth()->user()->hasRole('receptionist'))
                <a href="{{ route('receptionist.notifications') }}"
                    class="btn btn-sm btn-primary p-2">{{ __('messages.view_all') }}</a>
            @else
                <a href="{{ route('backend.notifications.index') }}"
                    class="btn btn-sm btn-primary p-2">{{ __('messages.view_all') }}</a>
            @endif
        @endif
    </div>
</div>
{{-- @if (isset($notifications) && count($notifications) > 0)
    <div class="card-footer text-muted p-3 text-center ">
        <a href="{{ route('backend.notifications.index') }}" class="mb-0 btn-link btn-link-hover font-weight-bold view-all-btn">{{ __('messages.view_all') }}</a>
    </div>
@endif --}}
