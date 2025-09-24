{{-- Debug information --}}
@php
    \Log::info('verify_action view - Data object:', [
        'billing_id' => $data->id ?? 'null',
        'is_estimate' => $data->is_estimate ?? 'null',
        'is_estimate_type' => gettype($data->is_estimate ?? null),
        'is_estimate_boolean' => (bool)($data->is_estimate ?? false),
        'payment_status' => $data->payment_status ?? 'null',
        'encounter_id' => $data->encounter_id ?? 'null'
    ]);
@endphp

@if ($data->is_estimate)
    <span class="badge booking-status bg-info-subtle p-2">{{__('appointment.estimate')}}</span>
@elseif ($data->payment_status) 
    <span class="badge booking-status bg-success-subtle p-2">{{__('appointment.paid')}} </span>
@elseif(optional(optional(optional($data->patientencounter)->appointmentdetail)->appointmenttransaction)->advance_payment_status)
    <span class="badge booking-status bg-success-subtle py-2 px-3">{{__('appointment.advance_paid')}} </span>        
@elseif(optional(optional(optional($data->patientencounter)->appointmentdetail)->appointmenttransaction) == null)
    <span class="badge booking-status bg-danger-subtle py-2 px-3">{{__('appointment.failed')}} </span>
@else
    <span class="badge booking-status bg-warning-subtle py-2 px-3">{{__('messages.unpaid')}} </span>
@endif