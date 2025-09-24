<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ __('appointment.lbl_prescription') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #222222;
            padding: 30px;
            background-color: #ffffff;
        }

        .logo {
            display: block;
            margin: 0 auto 30px;
            max-width: 180px;
            height: auto;
        }

        .card {
            background: #F9FBFD;
            padding: 20px 25px;
            margin-bottom: 30px;
            border-left: 5px solid #00C2CB;
            border-radius: 6px;
        }

        .card h3 {
            font-size: 16px;
            margin-bottom: 20px;
            color: #00C2CB;
            font-weight: 700;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .details-grid {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .details-column {
            width: 48%;
        }

        .details-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .details-column li {
            margin-bottom: 8px;
        }

        .highlight {
            font-weight: 500;
            color: #00C2CB;
        }

        .table-border {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        .table-border th {
            background-color: #B9D0F2;
            padding: 10px;
            text-align: left;
            background: #00C2CB;
            color:white;
            font-weight: bold;
            border: 1px solid #e0e0e0;
        }

        .table-border td {
            padding: 10px;
            border: 1px solid #eee;
            color: #222222;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            align-items: center;
        }

        .signature {
            width: 120px;
            height: auto;
            margin-left: 15px;
        }

        .no-data {
            text-align: center;
            color: #888;
            font-style: italic;
            padding: 10px 0;
        }
    </style>
</head>

<body>

    @php
        $imagePath = public_path('img/logo/invoice_logo.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $site_logo = 'data:image/png;base64,' . $imageData;
    @endphp
    {{-- <img class="logo" src="{{ $site_logo }}" alt="logo"> --}}

    <div class="card">
        @php
            $dateformate = App\Models\Setting::where('name', 'date_formate')->value('val') ?? 'Y-m-d';
            $timeformate = App\Models\Setting::where('name', 'time_formate')->value('val') ?? 'h:i A';
            $encounter_date = isset($data['encounter_date'])
                ? date($dateformate, strtotime($data['encounter_date']))
                : '--';
        @endphp
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <!-- Logo (Left) -->
                    <td width="50%" valign="top">
                        @php
                            $brandMarkBase64 = '';
                            $brandMarkUrl = $data['clinic']['brand_mark_url'] ?? null;
                            $clinicName = $data['clinic']['name'] ?? 'Unknown Clinic';
                            
                            // Get clinic logo and convert to base64 for PDF
                            if (!empty($brandMarkUrl)) {
                                try {
                                    // Check if it's already a base64 string
                                    if (strpos($brandMarkUrl, 'data:image') === 0) {
                                        $brandMarkBase64 = $brandMarkUrl;
                                    } else {
                                        // Convert URL to base64 for PDF generation
                                        if (filter_var($brandMarkUrl, FILTER_VALIDATE_URL)) {
                                            // Check if it's a local storage URL (like http://127.0.0.1:8001/storage/...)
                                            if (strpos($brandMarkUrl, '/storage/') !== false) {
                                                // Extract the storage path from the full URL
                                                $storagePath = substr($brandMarkUrl, strpos($brandMarkUrl, '/storage/') + 9); // Remove '/storage/' part
                                                $imagePath = storage_path('app/public/' . $storagePath);
                                                
                                                if (file_exists($imagePath)) {
                                                    $imageData = base64_encode(file_get_contents($imagePath));
                                                    $mimeType = 'image/png'; // Default to PNG
                                                    $brandMarkBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
                                                }
                                            } else {
                                                // Remote URL - try to fetch and convert with timeout
                                                $context = stream_context_create([
                                                    'http' => [
                                                        'timeout' => 5, // 5 second timeout
                                                        'user_agent' => 'Mozilla/5.0 (compatible; PDF Generator)'
                                                    ]
                                                ]);
                                                $imageContent = @file_get_contents($brandMarkUrl, false, $context);
                                                if ($imageContent !== false && strlen($imageContent) > 0) {
                                                    $imageData = base64_encode($imageContent);
                                                    $mimeType = 'image/png'; // Default to PNG
                                                    $brandMarkBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
                                                }
                                            }
                                        } elseif (strpos($brandMarkUrl, '/') === 0) {
                                            // Absolute path from root
                                            $imagePath = public_path(ltrim($brandMarkUrl, '/'));
                                            if (file_exists($imagePath)) {
                                                $imageData = base64_encode(file_get_contents($imagePath));
                                                $mimeType = 'image/png'; // Default to PNG
                                                $brandMarkBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
                                            }
                                        } elseif (strpos($brandMarkUrl, 'storage/') === 0) {
                                            // Storage path
                                            $imagePath = storage_path('app/public/' . str_replace('storage/', '', $brandMarkUrl));
                                            if (file_exists($imagePath)) {
                                                $imageData = base64_encode(file_get_contents($imagePath));
                                                $mimeType = 'image/png'; // Default to PNG
                                                $brandMarkBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
                                            }
                                        } else {
                                            // Relative path
                                            $imagePath = public_path($brandMarkUrl);
                                            if (file_exists($imagePath)) {
                                                $imageData = base64_encode(file_get_contents($imagePath));
                                                $mimeType = 'image/png'; // Default to PNG
                                                $brandMarkBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                    // If any error occurs, fall back to site logo
                                    $brandMarkBase64 = '';
                                }
                            }
                            
                            // If brand mark failed to load, ensure we have a fallback
                            if (empty($brandMarkBase64)) {
                                $brandMarkBase64 = $site_logo;
                            }
                        @endphp
                        
                        <img src="{{ $brandMarkBase64 }}" alt="Clinic Brand Mark" style="height: 100px; width: auto;">
                    </td>

                    <!-- Clinic Info (Right) -->
                    <td width="50%" valign="top"
                        style="text-align: right; font-size: 12px; line-height: 1.6; color: #333;">
                        <strong>{{ $data['clinic']['name'] ?? '--' }}</strong><br>
                        Phone: {{ $data['clinic']['contact_number'] ?? '--' }}<br>
                        Email: {{ $data['clinic']['email'] ?? '--' }}<br>
                        {{ $data['clinic']['address'] ?? '' }}<br>
                        {{ optional($data['clinic']['cities'])->name ?? '' }},
                        {{ optional($data['clinic']['states'])->name ?? '' }},
                        {{ $data['clinic']['pincode'] ?? '' }},
                        {{ optional($data['clinic']['countries'])->name ?? '' }}
                    </td>
                </tr>
            </table>

        <h3>{{ __('appointment.patient_&_appointment_details') }}</h3>

        <div class="details-grid">
            <!-- LEFT: Clinic + Doctor -->
            <div class="details-column">
                <ul>
                    <li><strong><span>{{ __('appointment.lbl_clinic_name') }}:</span></strong>
                        {{ $data['clinic']['name'] ?? '--' }}</li>
                    <li><strong><span>{{ __('appointment.lbl_doctor_name') }}:</span></strong> Dr.
                        {{ $data['doctor']['first_name'] . ' ' . $data['doctor']['last_name'] ?? '--' }}</li>
                    <li><strong><span>{{ __('appointment.lbl_encounter_date') }}:</span></strong> {{ $encounter_date }}
                    </li>
                    <li><strong><span>{{ __('appointment.lbl_description') }}:</span></strong>
                        {{ $data['description'] ?? '--' }}</li>
                </ul>
            </div>

            <!-- RIGHT: Patient -->
            <div class="details-column">
                <ul>
                    <li><strong><span>{{ __('appointment.lbl_name') }}:</span></strong>
                        {{ $data['user']['first_name'] . ' ' . $data['user']['last_name'] ?? '--' }}</li>
                    <li><strong><span>{{ __('appointment.lbl_email') }}:</span></strong>
                        {{ $data['user']['email'] ?? '--' }}</li>
                    <li><strong><span>{{ __('appointment.address') }}:</span></strong>
                        {{ $data['user']['address'] ?? '--' }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>{{ __('appointment.prescribed_medicines') }}</h3>
        <table class="table-border">
            <thead>
                <tr>
                    <th>{{ __('appointment.lbl_name') }}</th>
                    <th>{{ __('appointment.lbl_frequency') }}</th>
                    <th>{{ __('appointment.lbl_duration') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $prescriptionsList = $data['prescriptions'] ?? []; @endphp
                @forelse($prescriptionsList as $prescription)
                    <tr>
                        <td>
                            <strong>{{ $prescription->name ?? '--' }}</strong><br>
                            <small>{{ $prescription->instruction ?? '--' }}</small>
                        </td>
                        <td>{{ $prescription->frequency ?? '--' }}</td>
                        <td>{{ $prescription->duration ?? '--' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="no-data">No prescriptions available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="signature-section">
        <strong>{{ __('appointment.lbl_doctor_sign') }}:</strong>
        @if (!empty($data->signature))
            <img src="{{ $data->signature }}" alt="Doctor Signature" class="signature">
        @else
            <span style="margin-left: 10px;">--</span>
        @endif
    </div>

     <div class="footer" style="font-size: 12px; text-align: center; margin-top: 20px;">
            <p>
                @if (config('app.name'))
                    <div style="margin-top: 5px; font-size: 14px; font-weight: bold; color: #00C2CB;">
                        {{ config('app.name') }}
                    </div>
                @endif
            </p>
        </div>

</body>

</html>
