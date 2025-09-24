@php
    $doctor = $note->doctor;
    $patient = $note->patient;
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ __('appointment.follow_up_note_email_subject') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            color: #222;
        }

        .container {
            background: #fff;
            border-radius: 8px;
            max-width: 600px;
            margin: 30px auto;
            box-shadow: 0 2px 8px #eee;
            padding: 32px;
        }

        .header {
            background: #00C2CB;
            color: #fff;
            padding: 16px 32px;
            border-radius: 8px 8px 0 0;
            font-size: 22px;
            font-weight: bold;
        }

        .content {
            padding: 24px 0;
            line-height: 1.6;
        }

        .label {
            color: #00C2CB;
            font-weight: bold;
        }

        .footer {
            margin-top: 32px;
            color: #888;
            font-size: 13px;
            border-top: 1px solid #eee;
            padding-top: 16px;
        }

        .note-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .note-description {
            margin: 16px 0;
            white-space: pre-line;
        }

        .note-meta {
            margin-top: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            {{ __('appointment.follow_up_note_email_subject') }}
        </div>
        <div class="content">
            <p>
                {{ __('appointment.follow_up_note_email_greeting', ['name' => $recipientType === 'doctor' ? $doctor->first_name ?? '' : $patient->first_name ?? '']) }}
            </p>

            <p>{{ __('appointment.follow_up_note_email_intro') }}</p>

            <div class="note-title">{{ $note->title }}</div>
            <div class="note-description">{!! nl2br(e($note->description)) !!}</div>

            <div class="note-meta">
                <p><span class="label">{{ __('appointment.follow_up_note_date') }}:</span> {{ $note->date }}</p>
                <p><span class="label">{{ __('appointment.follow_up_note_doctor') }}:</span>
                    {{ $doctor->first_name ?? '' }}</p>
                <p><span class="label">{{ __('appointment.follow_up_note_patient') }}:</span>
                    {{ $patient->first_name ?? '' }}</p>
            </div>

            <p>{{ __('appointment.follow_up_note_email_body') }}</p>
        </div>
        <div class="footer">
            {{ __('appointment.follow_up_note_email_footer') }}
        </div>
    </div>
</body>

</html>
