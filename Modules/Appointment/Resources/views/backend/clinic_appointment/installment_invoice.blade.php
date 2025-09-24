<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installment Receipt</title>
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

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .services-table th,
        .services-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .services-table th {
            background: #00C2CB;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .services-table .text-right {
            text-align: right;
        }

        .services-table .text-center {
            text-align: center;
        }

        /* Consistent payment summary alignment */

        .summary-row {
            margin-bottom: 4px;
        }

        .summary-row.total {
            border-top: 2px solid #333;
            padding-top: 4px;
            font-weight: bold;
            font-size: 13px;
        }


        .summary-row.paid {
            color: #28a745;
            font-weight: bold;
        }

        .summary-row.unpaid {
            color: #dc3545;
            font-weight: bold;
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
        $info = $data[0];
    @endphp
    <div class="container">
        <!-- Header Section -->
        <div style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #333;">
            @php
                $brandMarkBase64 = '';
                $site_logo = '';
                

            
                // Get clinic logo from clinic center data and convert to base64 for PDF
                if (isset($info['cliniccenter']['brand_mark_url']) && !empty($info['cliniccenter']['brand_mark_url'])) {
                    try {
                        // Check if it's already a base64 string
                        if (strpos($info['cliniccenter']['brand_mark_url'], 'data:image') === 0) {
                            $brandMarkBase64 = $info['cliniccenter']['brand_mark_url'];
                        } else {
                            // Convert URL to base64 for PDF generation
                            $brandMarkUrl = $info['cliniccenter']['brand_mark_url'];
                            
                            // Handle different types of URLs
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
                
                // Set default site logo
                $imagePath = public_path('img/logo/invoice_logo.png');
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $site_logo = 'data:image/png;base64,' . $imageData;
                }
                
                // If brand mark failed to load, ensure we have a fallback
                if (empty($brandMarkBase64)) {
                    $brandMarkBase64 = $site_logo;
                }
                
                // Ensure we always have a logo to display
                if (empty($brandMarkBase64)) {
                    $brandMarkBase64 = $site_logo;
                }
            @endphp

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <!-- Logo (Left) -->
                    <td width="50%" valign="top">
                        <img src="{{ $brandMarkBase64 }}" alt="Clinic Brand Mark" style="height: 100px; width: auto;">
                    </td>

                    <!-- Clinic Info (Right) -->
                    <td width="50%" valign="top"
                        style="text-align: right; font-size: 12px; line-height: 1.6; color: #333;">
                        <strong>{{ $info['cliniccenter']['name'] ?? '--' }}</strong><br>
                        Phone: {{ $info['cliniccenter']['contact_number'] ?? '--' }}<br>
                        Email: {{ $info['cliniccenter']['email'] ?? '--' }}<br>
                        {{ $info['cliniccenter']['address'] ?? '' }}<br>
                        {{ $info['cliniccenter']['city'] ?? '' }},
                        {{ $info['cliniccenter']['state'] ?? '' }},
                        {{ $info['cliniccenter']['pincode'] ?? '' }},
                        {{ $info['cliniccenter']['country'] ?? '' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div style="margin-bottom: 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <!-- Patient Info (Left) -->
                        <td width="49%" valign="top" style="padding-right: 10px;">
                            <h3
                                style="color: #00C2CB; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                                {{ __('invoice.patient_information') }}
                            </h3>
                            <div style="font-size: 12px; line-height: 1.6;">
                                <div><strong>{{ __('invoice.name') }}:</strong>
                                    {{ setNamePrefix(\App\Models\User::find($info['user_id'])) }}</div>
                                <div><strong>{{ __('invoice.mr_no') }}:</strong> {{ $patient_mr ?? 'MR001234' }}</div>
                                <div><strong>{{ __('invoice.phone') }}:</strong> {{ $info['user']['mobile'] }}</div>
                                <div><strong>{{ __('invoice.gender') }}:</strong>
                                    {{ $info['user']['gender'] ?? '--' }}</div>
                                <div><strong>{{ __('invoice.age') }}:</strong>
                                    @if (!empty($info['user']['date_of_birth']))
                                        {{ \Carbon\Carbon::parse($info['user']['date_of_birth'])->age }}
                                        {{ __('Years') }}
                                    @else
                                        --
                                    @endif
                                </div>
                                @if ($info['user']['date_of_birth'] !== null)
                                    <div><strong>{{ __('invoice.date_of_birth') }}:</strong>
                                        {{ date($dateformate, strtotime($info['user']['date_of_birth'])) ?? '--' }}
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Receipt Info (Right) -->
                        <td width="49%" valign="top" style="padding-left: 10px;">
                            <h3
                                style="color: #00C2CB; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                                {{ __('invoice.receipt_details') }}
                            </h3>
                            <div style="font-size: 12px; line-height: 1.6;">
                                <div><strong>{{ __('invoice.receipt_id') }}:</strong>
                                    BR-{{ $info['patient_encounter']['billingrecord']['id'] }}-INST-{{ $installment->id }}
                                </div>
                                <div><strong>{{ __('invoice.created_on') }}:</strong>
                                    {{ date('Y-m-d H:i', strtotime($installment->created_at)) }}
                                </div>
                                <div><strong>{{ __('invoice.payment_status') }}:</strong>
                                    {{ __('Paid') }}
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
                    @foreach ($info['patient_encounter']['billingrecord']['billing_item'] as $billingItem)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $billingItem['clinicservice']['product_code'] ?? '--' }}</td>
                            @if ($billingItem['discount_value'] != 0)
                                @if ($billingItem['discount_type'] === 'percentage')
                                    <td>{{ $billingItem['clinicservice']['name'] ?? '--' }}
                                        (<span>{{ $billingItem['discount_value'] ?? '--' }}%</span>)
                                    </td>
                                @else
                                    <td>{{ $billingItem['clinicservice']['name'] ?? '--' }}
                                        (<span>{{ Currency::format($billingItem['discount_value']) ?? '--' }}</span>)
                                    </td>
                                @endif
                            @else
                                <td>{{ $billingItem['clinicservice']['name'] ?? '--' }}</td>
                            @endif
                            <td>{{ $billingItem['clinicservice']['description'] }}</td>
                            <td class="text-center">{{ $billingItem['quantity'] ?? '--' }}</td>
                            <td class="text-right"> {{ Currency::format($billingItem['service_amount']) ?? '--' }}
                            </td>
                            <td class="text-right">
                                {{ Currency::format($billingItem['clinicservice']['discount']) ?? '--' }}</td>
                            <td class="text-right">{{ Currency::format($billingItem['total_amount']) ?? '--' }}
                            </td>
                            <td>{{ $info['doctor']['full_name'] ?? '--' }}</td>
                        </tr>
                        @php $index++ @endphp
                    @endforeach
                </tbody>
                @if ($info['clinicservice'] == null)
                    <tbody>
                        <tr>
                            <td colspan="6">
                                <h4 class="text-primary mb-0">
                                    {{ __('invoice.no_record_found') }}</h4>
                            </td>
                        </tr>
                    </tbody>
                @endif
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px; margin-bottom: 20px;">
                <tr>
                    <!-- Installment Details (Left) -->
                    <td width="60%" valign="top" style="padding-right: 15px;">
                        <div style="font-size: 12px;">
                            <h3
                                style="color: #00C2CB; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
                                {{ __('appointment.installment_details') }}
                            </h3>
                            <table width="100%"
                                style="border-collapse: collapse; border: 1px solid #ddd; font-size: 12px;">
                                <thead>
                                    <tr style="background-color: #f0f0f0;">
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">
                                            {{ __('invoice.amount') }}</th>
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">
                                            {{ __('invoice.payment_mode') }}</th>
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">
                                            {{ __('invoice.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">
                                            {{ Currency::format($installment->amount) }}
                                        </td>
                                        <td style="padding: 8px; border: 1px solid #ddd;">
                                            {{ $installment->payment_mode }}
                                        </td>
                                        <td style="padding: 8px; border: 1px solid #ddd;">
                                            {{ date('d M Y', strtotime($installment->date)) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>

                    <!-- Payment Summary (Right) -->
                    <td width="40%" valign="top" style="padding-left: 15px;">
                        @php
                            // Calculate total from billing items (same as billing details)
                            $service_total_amount = 0;
                            if (isset($info['patient_encounter']['billingrecord']['billing_item'])) {
                                foreach ($info['patient_encounter']['billingrecord']['billing_item'] as $item) {
                                    $service_total_amount += $item['total_amount'];
                                }
                            }

                            // Calculate discount (same as billing details)
                            $discount_amount = 0;
                            $transaction = $info['patient_encounter']['billingrecord'] ?? null;
                            if (
                                $transaction &&
                                $transaction['final_discount'] == 1 &&
                                $transaction['final_discount_value'] > 0
                            ) {
                                if ($transaction['final_discount_type'] == 'fixed') {
                                    $discount_amount = $transaction['final_discount_value'];
                                } else {
                                    $discount_amount =
                                        ($transaction['final_discount_value'] * $service_total_amount) / 100;
                                }
                            }

                            // Calculate tax using the same function as billing details
                            // $taxDetails = getBookingTaxamount($service_total_amount - $discount_amount, null); // Tax calculation disabled
                            // $total_tax = $taxDetails['total_tax_amount'] ?? 0; // Tax calculation disabled
                            $total_tax = 0; // Tax calculation disabled

                            // Calculate grand total (same formula as billing details)
                            $grand_total = $service_total_amount - $discount_amount; // Tax calculation disabled
                            // $grand_total = $total_tax + $service_total_amount - $discount_amount;

                            // For installment PDF, this installment amount is what was paid
                            $paid_amount = $installment->amount;

                            // Calculate total paid so far (including this installment)
                            $total_paid_so_far = $billingRecord->installments->sum('amount');

                            // Calculate remaining amount after all installments
                            $remaining_amount = max(0, $grand_total - $total_paid_so_far);

                            // Check for refund scenario - if total paid exceeds grand total
                            $refundAmount = 0;
                            $showRefundNote = false;
                            if ($total_paid_so_far > $grand_total) {
                                $refundAmount = $total_paid_so_far - $grand_total;
                                $showRefundNote = true;
                            }
                        @endphp
                        <div style="padding: 15px; font-size: 12px;">
                            <h3
                                style="margin: 0 0 15px 0; font-size: 14px; color: #00C2CB; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
                                {{ __('invoice.payment_summary') }}
                            </h3>
                            <table width="100%" class="summary-row">
                                <tr>
                                    <td width="50%"><strong>{{ __('invoice.service_amount') }}</strong></td>
                                    <td width="50%" style="text-align: right;">
                                        {{ Currency::format($service_total_amount) }}</td>
                                </tr>
                            </table>
                            @if ($transaction && $transaction['final_discount'] == 1 && $transaction['final_discount_value'] > 0)
                                <table width="100%" class="summary-row">
                                    <tr>
                                        <td width="50%"><strong>{{ __('invoice.discount') }}
                                                @if ($transaction['final_discount_type'] === 'percentage')
                                                    ({{ $transaction['final_discount_value'] ?? '--' }}%)
                                                @else
                                                    ({{ Currency::format($transaction['final_discount_value']) ?? '--' }})
                                                @endif
                                            </strong></td>
                                        <td width="50%" style="text-align: right;">
                                            -{{ Currency::format($discount_amount) ?? '--' }}</td>
                                    </tr>
                                </table>
                                <table width="100%" class="summary-row">
                                    <tr>
                                        <td width="50%"><strong>{{ __('invoice.sub_total') }}</strong></td>
                                        <td width="50%" style="text-align: right;">
                                            {{ Currency::format($service_total_amount - $discount_amount) }}</td>
                                    </tr>
                                </table>
                            @endif
                            {{-- @if ($total_tax > 0)
                                <table width="100%" class="summary-row">
                                    <tr>
                                        <td width="50%"><strong>{{ __('invoice.tax') }}</strong></td>
                                        <td width="50%" style="text-align: right;">
                                            {{ Currency::format($total_tax) }}</td>
                                    </tr>
                                </table>
                            @endif --}}
                            <table width="100%" class="summary-row total" style="margin-top: 10px;">
                                <tr>
                                    <td width="50%"><strong>{{ __('invoice.net_total') }}</strong></td>
                                    <td width="50%" style="text-align: right;">
                                        {{ Currency::format($grand_total) }}</td>
                                </tr>
                            </table>
                            <table width="100%" class="summary-row paid" style="margin-top: 10px;">
                                <tr>
                                    <td width="50%"><strong>{{ __('invoice.paid') }}</strong></td>
                                    <td width="50%" style="text-align: right;">
                                        {{ Currency::format($total_paid_so_far) }}</td>
                                </tr>
                            </table>
                            <table width="100%" class="summary-row unpaid">
                                <tr>
                                    <td width="50%"><strong>{{ __('invoice.unpaid') }}</strong></td>
                                    <td width="50%" style="text-align: right;">
                                        {{ Currency::format($remaining_amount) }}</td>
                                </tr>
                            </table>

                            @if ($showRefundNote)
                                <table width="100%" class="summary-row"
                                    style="margin-top: 10px; padding: 8px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
                                    <tr>
                                        <td width="50%" style="color: #856404; font-weight: bold;">
                                            {{ __('appointment.refundable_amount') }}</td>
                                        <td width="50%"
                                            style="text-align: right; color: #856404; font-weight: bold;">
                                            {{ Currency::format($refundAmount) }}</td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Notes Section -->
            <div class="notes-section">
                <h3>{{ __('invoice.notes') }}</h3>
                <div class="notes-box">
                    @if (!empty($info['patient_encounter']['billingrecord']['notes']))
                        {{ $info['patient_encounter']['billingrecord']['notes'] }}
                    @else
                        {{ __('invoice.no_notes_provided') }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer" style="font-size: 12px; text-align: center; margin-top: 20px;">
            <p>
                @if (config('app.name'))
                    <div style="margin-top: 5px; font-size: 14px; font-weight: bold; color: #00C2CB;">
                        {{ config('app.name') }}
                    </div>
                @endif
            </p>
        </div>

    </div>
</body>

</html>
