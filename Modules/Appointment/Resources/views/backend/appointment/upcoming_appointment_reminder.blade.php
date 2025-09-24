<!DOCTYPE html>
<html>

<head>
    <title>{{ __('appointment.lbl_upcoming_appointment_reminder') }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; font-size: 14px; color: #333;">
    <p>
        @if ($role === 'doctor')
            {{ setNamePrefix(\App\Models\User::find($appointment->doctor_id)) }},
        @else
            {{ setNamePrefix(\App\Models\User::find($appointment->user_id)) }},
        @endif
    </p>

    <p>This is a gentle reminder of your upcoming appointment scheduled for tomorrow.</p>

    <p><strong>Appointment Details:</strong></p>
    <ul>
        <li><strong>ID:</strong> #{{ $appointment->id }}</li>
        <li><strong>Service:</strong> {{ $appointment->clinicservice->name ?? '-' }}</li>
        <li><strong>Description:</strong> {{ $appointment->clinicservice->description ?? '-' }}</li>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d-m-Y') }}</li>
        <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</li>

        @if ($role === 'doctor')
            <li><strong>Patient Name:</strong> {{ setNamePrefix(\App\Models\User::find($appointment->user_id)) }}</li>
        @else
            <li><strong>Doctor Name:</strong> {{ setNamePrefix(\App\Models\User::find($appointment->doctor_id)) }}
            </li>
        @endif

        @if ($appointment->cliniccenter)
            <li><strong>Clinic:</strong> {{ $appointment->cliniccenter->name ?? '-' }}</li>
            <li><strong>Address:</strong> {{ $appointment->cliniccenter->address ?? '-' }}</li>
        @endif
    </ul>

    <p>
        Please ensure to arrive on time.
        @if ($role === 'doctor')
            Kindly make the necessary preparations in advance.
        @else
            If you need to reschedule or have any concerns, please contact our support team or your clinic.
        @endif
    </p>

    <p>Thank you,<br><strong>{{ config('app.name') }}</strong></p>
</body>

</html>
