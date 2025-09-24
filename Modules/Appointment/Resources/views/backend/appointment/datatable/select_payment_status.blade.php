@if(isset($data->appointmenttransaction))
    @if($data->appointmenttransaction->payment_status == 1)
        @foreach ($payment_status as $key => $value )
        
        @if(isset($data->appointmenttransaction))
            @if($data->appointmenttransaction->payment_status == $value['value'])
                <span class="text-capitalize badge bg-info-subtle p-2">{{$value['name']}}</span>
            @endif
        @endif
        @endforeach
    @elseif(optional($data->appointmenttransaction)->payment_status == 0 && optional($data->appointmenttransaction)->advance_payment_status == 1)
        <span class="text-capitalize badge bg-info-subtle p-2">{{__('appointment.advance_paid')}}</span>
    @else
        {{-- Check if this is an estimate --}}
        @if(optional($data->patientEncounter)->billingrecord && optional($data->patientEncounter->billingrecord)->is_estimate)
            <span class="text-capitalize badge bg-warning-subtle p-2">{{__('appointment.estimate')}}</span>
        @else
            <select name="branch_for" class="select2 change-select" data-token="{{csrf_token()}}"
                data-url="{{route('backend.appointments.updatePaymentStatus', ['id' => $data->id, 'action_type' => 'update-payment-status'])}}"
                style="width: 100%;" {{ $data->status !== 'checkout' ? 'disabled' : '' }}>
                @foreach ($payment_status as $key => $value )
                <option value="{{$value->value}}" {{optional($data->appointmenttransaction)->payment_status  == $value->value ? 'selected' : ''}}>
                    {{$value['name']}}</option>
                @endforeach
            </select>
        @endif
    @endif 
@else
    {{-- Check if this is an estimate when no appointment transaction exists --}}
    @if(optional($data->patientEncounter)->billingrecord && optional($data->patientEncounter->billingrecord)->is_estimate)
        <span class="text-capitalize badge bg-warning-subtle p-2">{{__('appointment.estimate')}}</span>
    @else
        <span class="text-capitalize badge bg-danger-subtle  p-3">Failed</span>
    @endif
@endif 