<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Prescription List</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', Arial, sans-serif;
        }
    </style>
</head>

<body style="font-family: 'Poppins', Arial, sans-serif;">
    <div style="max-width: 700px; margin: auto;">

        <h2 style="text-align: center; color: #00C2CB;">Prescription Summary</h2>

        <p>Dear {{ $user->name ?? 'Patient' }},</p>

        <p>Please find below your prescribed medication list. Follow the instructions carefully and consult your doctor
            if you have any questions.</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #00C2CB;">
                    <th
                        style="border: 1px solid #ccc;  background: #00C2CB; color:white; padding: 8px; text-align: left;">
                        #</th>
                    <th
                        style="border: 1px solid #ccc;  background: #00C2CB; color:white; padding: 8px; text-align: left;">
                        Medicine Name</th>
                    <th
                        style="border: 1px solid #ccc;  background: #00C2CB; color:white; padding: 8px; text-align: left;">
                        Frequency</th>
                    <th
                        style="border: 1px solid #ccc;  background: #00C2CB; color:white; padding: 8px; text-align: left;">
                        Duration (Days)</th>
                    <th
                        style="border: 1px solid #ccc;  background: #00C2CB; color:white; padding: 8px; text-align: left;">
                        Instructions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptionList as $index => $prescription)
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $prescription->name }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $prescription->frequency }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $prescription->duration }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $prescription->instruction }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="border: 1px solid #ccc; padding: 8px; text-align: center;">No
                            prescriptions available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p style="margin-top: 30px;">
            Please follow your medication schedule as prescribed. If you feel unwell or experience any side effects,
            contact your healthcare provider immediately.
        </p>

        <p>Wishing you a speedy recovery,<br><strong>{{ config('app.name') }}</strong></p>
    </div>
</body>

</html>
