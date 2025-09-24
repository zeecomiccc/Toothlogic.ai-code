<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>{{ __('appointment.estimate') }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .estimate-header {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            border: 2px solid #00C2CB;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .estimate-title {
            color: #00C2CB;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .estimate-subtitle {
            color: #6c757d;
            font-size: 10px;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .services-table th,
        .services-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .services-table th {
            background-color: #00C2CB;
            color: white;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary-row {
            width: 100%;
            margin-bottom: 5px;
        }

        .summary-row td {
            padding: 5px 0;
        }

        .total {
            border-top: 2px solid #00C2CB;
            font-weight: bold;
            font-size: 14px;
        }

        .notes-section {
            margin-top: 30px;
        }

        .notes-box {
            border: 1px solid #ddd;
            padding: 15px;
            background: #f9f9f9;
            min-height: 60px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        @media print {
            .container {
                max-width: none;
                margin: 0;
                padding: 0;
            }

            body {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    @php
        $info = $data;
    @endphp
    <div class="container">

        <!-- Estimate Header -->
        <div class="estimate-header">
            <div class="estimate-title">ESTIMATE</div>
            <div class="estimate-subtitle">This is a cost estimate and does not constitute an invoice</div>
        </div>

        <!-- Header Section -->
        <div class="header">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <!-- Logo (Left) -->
                    <td width="50%" valign="top">
                        @php
                            $brandMarkBase64 = '';
                            
                            // Get clinic logo and convert to base64 for PDF
                            if (isset($info['clinic']['brand_mark_url']) && !empty($info['clinic']['brand_mark_url'])) {
                                try {
                                    $brandMarkUrl = $info['clinic']['brand_mark_url'];
                                    
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
                    <td width="50%" valign="top" style="text-align: right; font-size: 12px; line-height: 1.6; color: #333;">
                        <strong>{{ $info->clinic->name ?? '--' }}</strong><br>
                        Phone: {{ $info->clinic->contact_number ?? '--' }}<br>
                        Email: {{ $info->clinic->email ?? '--' }}<br>
                        {{ $info->clinic->address ?? '' }}<br>
                        {{ $info->clinic->city ?? '' }},
                        {{ $info->clinic->state ?? '' }},
                        {{ $info->clinic->pincode ?? '' }},
                        {{ $info->clinic->country ?? '' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Main Content -->
        <div style="margin-bottom: 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <!-- Patient Info (Left) -->
                    <td width="49%" valign="top" style="padding-right: 10px;">
                        <h3 style="color: #00C2CB; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                            {{ __('invoice.patient_information') }}
                        </h3>
                        <div style="font-size: 12px; line-height: 1.6;">
                            <div><strong>{{ __('invoice.name') }}:</strong>
                                {{ $info->user->first_name . ' ' . $info->user->last_name ?? '--' }}</div>
                            <div><strong>{{ __('invoice.phone') }}:</strong> {{ $info->user->mobile ?? '--' }}</div>
                            <div><strong>{{ __('invoice.gender') }}:</strong>
                                {{ $info->user->gender ?? '--' }}</div>
                            <div><strong>{{ __('invoice.age') }}:</strong>
                                @if (!empty($info->user->date_of_birth))
                                    {{ \Carbon\Carbon::parse($info->user->date_of_birth)->age }}
                                    {{ __('Years') }}
                                @else
                                    --
                                @endif
                            </div>
                            @if ($info->user->date_of_birth !== null)
                                <div><strong>{{ __('invoice.date_of_birth') }}:</strong>
                                    {{ date('Y-m-d', strtotime($info->user->date_of_birth)) ?? '--' }}
                                </div>
                            @endif
                        </div>
                    </td>

                    <!-- Estimate Info (Right) -->
                    <td width="49%" valign="top" style="padding-left: 10px;">
                        <h3 style="color: #00C2CB; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                            {{ __('appointment.estimate_details') }}
                        </h3>
                        <div style="font-size: 12px; line-height: 1.6;">
                            <div><strong>{{ __('appointment.estimate_id') }}:</strong>
                                EST-{{ $info->id ?? '--' }}</div>
                            <div><strong>{{ __('appointment.encounter_date') }}:</strong>
                                {{ date('Y-m-d H:i', strtotime($info->patientencounter->encounter_date ?? '--')) }}
                            </div>
                            <div><strong>{{ __('appointment.status') }}:</strong>
                                {{ __('appointment.estimate') }}
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Services Table -->
        <table class="services-table">
            <thead>
                <tr>
                    <th style="width: 10%;">{{ __('invoice.sr_no') }}</th>
                    <th style="width: 12%;">{{ __('invoice.product_code') }}</th>
                    <th style="width: 15%;">{{ __('invoice.item_name') }}</th>
                    <th style="width: 18%;">{{ __('invoice.description') }}</th>
                    <th style="width: 7%;">{{ __('invoice.qty') }}</th>
                    <th style="width: 10%;">{{ __('invoice.price') }}</th>
                    <th style="width: 8%;">{{ __('invoice.discount') }}</th>
                    <th style="width: 10%;">{{ __('invoice.after_discount') }}</th>
                    <th style="width: 10%;">{{ __('invoice.service_provided_by') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $index = 1 @endphp
                @if (isset($info->billingItem) && $info->billingItem->count() > 0)
                    @foreach ($info->billingItem as $billingItem)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $billingItem->clinicservice->product_code ?? '--' }}</td>
                            @if ($billingItem->discount_value != 0)
                                @if ($billingItem->discount_type === 'percentage')
                                    <td>{{ $billingItem->clinicservice->name ?? '--' }}
                                        (<span>{{ $billingItem->discount_value ?? '--' }}%</span>)
                                    </td>
                                @else
                                    <td>{{ $billingItem->clinicservice->name ?? '--' }}
                                        (<span>{{ Currency::format($billingItem->discount_value) ?? '--' }}</span>)
                                    </td>
                                @endif
                            @else
                                <td>{{ $billingItem->clinicservice->name ?? '--' }}</td>
                            @endif
                            <td>{{ $billingItem->clinicservice->description ?? '--' }}</td>
                            <td class="text-center">{{ $billingItem->quantity ?? '--' }}</td>
                            <td class="text-right"> {{ Currency::format($billingItem->service_amount) ?? '--' }}</td>
                            <td class="text-right">
                                @if($billingItem->discount_value > 0)
                                    @if($billingItem->discount_type === 'percentage')
                                        {{ $billingItem->discount_value }}%
                                    @else
                                        {{ Currency::format($billingItem->discount_value) }}
                                    @endif
                                @else
                                    --
                                @endif
                            </td>
                            <td class="text-right">{{ Currency::format($billingItem->total_amount) ?? '--' }}</td>
                            <td>{{ $info->doctor->first_name . ' ' . $info->doctor->last_name ?? '--' }}</td>
                        </tr>
                        @php $index++ @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">
                            <h4 class="text-primary mb-0">
                                {{ __('invoice.no_record_found') }}</h4>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Cost Summary -->
        <div style="margin-top: 20px; margin-bottom: 20px;">
            <div style="padding: 15px; font-size: 12px; border: 1px solid #ddd; background: #f5f5f5;">
                <h3 style="margin: 0 0 15px 0; font-size: 14px; color: #00C2CB; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
                    {{ __('appointment.cost_summary') }}
                </h3>
                @php
                    // Calculate service total amount from billing items
                    $service_total_amount = 0;
                    if (isset($info->billingItem) && $info->billingItem->count() > 0) {
                        foreach ($info->billingItem as $item) {
                            $service_total_amount += $item->total_amount;
                        }
                    }
                    
                    $transaction = $info;
                    $discount_amount = 0;
                    if ($transaction && $transaction->final_discount_type == 'percentage') {
                        $discount_amount = $service_total_amount * ($transaction->final_discount_value / 100);
                    } else {
                        $discount_amount = $transaction->final_discount_value ?? 0;
                    }
                    $sub_total = $service_total_amount - $discount_amount;
                    $grand_total = $transaction->final_total_amount ?? $sub_total;
                @endphp
                
                <table width="100%" class="summary-row">
                    <tr>
                        <td width="50%"><strong>{{ __('appointment.service_amount') }}</strong></td>
                        <td width="50%" style="text-align: right;">
                            {{ Currency::format($service_total_amount) }}</td>
                    </tr>
                </table>
                
                @if ($transaction && $transaction->final_discount == 1)
                    <table width="100%" class="summary-row">
                        <tr>
                            <td width="50%"><strong>{{ __('appointment.discount') }}
                                    @if ($transaction->final_discount_type === 'percentage')
                                        ({{ $transaction->final_discount_value ?? '--' }}%)
                                    @else
                                        ({{ Currency::format($transaction->final_discount_value) ?? '--' }})
                                    @endif
                                </strong></td>
                            <td width="50%" style="text-align: right;">
                                -{{ Currency::format($discount_amount) ?? '--' }}</td>
                        </tr>
                    </table>
                    <table width="100%" class="summary-row">
                        <tr>
                            <td width="50%"><strong>{{ __('appointment.sub_total') }}</strong></td>
                            <td width="50%" style="text-align: right;">
                                {{ Currency::format($sub_total) }}</td>
                        </tr>
                    </table>
                @endif
                
                <table width="100%" class="summary-row total">
                    <tr>
                        <td width="50%"><strong>{{ __('appointment.total_payable_amount') }}</strong></td>
                        <td width="50%" style="text-align: right;">
                            {{ Currency::format($grand_total) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p>
                @if (config('app.name'))
                    <div style="font-size: 14px; font-weight: bold; color: #00C2CB;">
                        {{ config('app.name') }}
                    </div>
                @endif
            </p>
        </div>

    </div>
</body>

</html>
