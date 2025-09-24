@php
    $doctor = $note->doctor;
    $patient = $note->patient;
    $clinic = $note->clinic;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Follow-up Note Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 650px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background: #00C2CB;
            color: #ffffff;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            padding: 15px;
        }
        .content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .note-title {
            font-size: 18px;
            font-weight: bold;
            color: #00C2CB;
            margin: 15px 0 10px 0;
        }
        .note-description {
            background: #f1f5f9;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .footer {
            background: #f6f8fa;
            text-align: center;
            font-size: 12px;
            color: #777777;
            padding: 12px;
            border-top: 1px solid #eaeaea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Follow-up Appointment Reminder
        </div>
        <div class="content">
            <p>Dear {{ $receptionist->first_name ?? 'Front Desk Officer' }},</p>

            <p>
                This is a kind reminder from <strong>{{ setting('app_name', 'ToothLogic') }}</strong> 
                regarding a scheduled follow-up appointment.
            </p>

            <p>
                <strong>Patient {{ $patient->first_name ?? 'N/A' }} {{ $patient->last_name ?? '' }}</strong> 
                has a follow-up appointment with 
                <strong>Dr. {{ $doctor->first_name ?? 'N/A' }} {{ $doctor->last_name ?? '' }}</strong> 
                at <strong>{{ $clinic->name ?? 'N/A' }}</strong> tomorrow. Please make sure all necessary arrangements are made.
            </p>

            <div class="note-title">{{ $note->title ?? 'N/A' }}</div>
            <div class="note-description">{!! nl2br(e($note->description ?? 'No description available')) !!}</div>

            <p>
                We kindly request you to ensure everything is prepared for this consultation at 
                <strong>{{ $clinic->name ?? 'ToothLogic' }}</strong>.
            </p>
        </div>
        <div class="footer">
            <p><strong>{{ setting('app_name', 'ToothLogic') }}</strong></p>
        </div>
    </div>
</body>
</html>
