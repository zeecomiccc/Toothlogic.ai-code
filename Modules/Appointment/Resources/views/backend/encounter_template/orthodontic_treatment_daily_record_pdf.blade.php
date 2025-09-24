<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ __('appointment.orthodontic_treatment_daily_record') }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 40px;
            background: white;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
        }

        h2 {
            font-size: 20px;
            color: #00C2CB;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 13px;
            margin-bottom: 25px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #00C2CB;
            padding: 8px;
            font-size: 12px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #00C2CB;
            border: 1px solid #fff;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        th:first-child {
            border-left: 1px solid #00C2CB;
        }

        th:last-child {
            border-right: 1px solid #00C2CB;
        }

        td {
            min-height: 40px;
        }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: center;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        @media print {
            body {
                font-size: 11px;
                padding: 20px;
            }

            .container {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>{{ __('appointment.orthodontic_treatment_daily_record') }}</h2>
        <div class="subtitle">
            {{ __('appointment.orthodontic_treatment_daily_record_description') }}
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 10%">{{ __('appointment.date') }}</th>
                    <th style="width: 20%">{{ __('appointment.procedure_performed') }}</th>
                    <th style="width: 15%">{{ __('appointment.oral_hygiene_status') }}</th>
                    <th style="width: 20%">{{ __('appointment.patient_compliance') }}</th>
                    <th style="width: 20%">{{ __('appointment.next_appointment_date_procedure') }}</th>
                    <th style="width: 15%">{{ __('appointment.remarks_comments') }}</th>
                    <th style="width: 10%">{{ __('appointment.initials') }}</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($dailyRecords) && count($dailyRecords))
                    @foreach ($dailyRecords as $record)
                        <tr>
                            <td>{{ $record->date ?? '' }}</td>
                            <td>{{ $record->procedure_performed ?? '' }}</td>
                            <td>{{ $record->oral_hygiene_status ?? '' }}</td>
                            <td>{{ $record->patient_compliance ?? '' }}</td>
                            <td>{{ $record->next_appointment_date_procedure ?? '' }}</td>
                            <td>{{ $record->remarks_comments ?? '' }}</td>
                            <td>{{ $record->initials ?? '' }}</td>
                        </tr>
                    @endforeach
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                @else
                    @for ($i = 0; $i < 12; $i++)
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
