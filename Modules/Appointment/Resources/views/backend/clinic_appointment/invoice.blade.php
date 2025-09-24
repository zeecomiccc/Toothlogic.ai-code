<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>{{ __('appointment.invoice_detail') }}</title>
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

        /* 🔄 Updated Header */
        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .logo-clinic-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            flex: 0 0 auto;
        }

        .logo {
            height: 65px;
            width: auto;
            display: block;
        }

        .clinic-info-text {
            text-align: right;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .main-content {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .patient-receipt-wrapper {
            display: flex;
            gap: 20px;
            width: 100%;
        }

        .left-section,
        .right-section {
            flex: 1;
        }

        .patient-info {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background: #f5f5f5;
        }

        .patient-info h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .patient-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .patient-details div {
            display: flex;
            justify-content: space-between;
        }

        .receipt-details {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background: #f5f5f5;
        }

        .receipt-details h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .receipt-details div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .services-table th,
        .services-table td,
        {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        }

        .services-table th,
        .installments-table th,
        {
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

        .notes-section {
            margin-bottom: 20px;
        }

        .notes-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .notes-box {
            border: 1px solid #ddd;
            padding: 10px;
            min-height: 60px;
            background: #f5f5f5;
        }

        .summary-section {
            border: 1px solid #ddd;
            padding: 15px;
            background: #f5f5f5;
        }

        .summary-section h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #00C2CB;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

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
                $imagePath = public_path('img/logo/invoice_logo.png');
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $site_logo = 'data:image/png;base64,' . $imageData;
                } else {
                    $site_logo = '';
                }
            @endphp

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <!-- Logo (Left) -->
                    <td width="50%" valign="top">
                        @php
                            $brandMarkBase64 = '';
                            
                            // Get clinic logo and convert to base64 for PDF
                            if (isset($info['cliniccenter']['brand_mark_url']) && !empty($info['cliniccenter']['brand_mark_url'])) {
                                try {
                                    $brandMarkUrl = $info['cliniccenter']['brand_mark_url'];
                                    
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
                                    BR-{{ $info['patient_encounter']['billingrecord']['id'] }}</div>
                                <div><strong>{{ __('invoice.created_on') }}:</strong>
                                    {{ date('Y-m-d H:i', strtotime($info['patient_encounter']['billingrecord']['created_at'])) }}
                                </div>
                                <div><strong>{{ __('invoice.payment_status') }}:</strong>
                                    {{ $info['patient_encounter']['billingrecord']['payment_status'] == 1 ? __('Paid') : __('Pending') }}
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

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px; margin-bottom: 20px;"
                class="installments-table">
                <tr>
                    <!-- Payment History (Left) -->
                    <td width="60%" valign="top" style="padding-right: 15px;">
                        <div style="font-size: 12px;">
                            <h3
                                style="color: #00C2CB; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
                                {{ __('invoice.payment_history') }}
                            </h3>
                            <table width="100%"
                                style="border-collapse: collapse; border: 1px solid #ddd; font-size: 12px;">
                                <thead>
                                    <tr style="background-color: #f5f5f5;">
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">
                                            {{ __('invoice.date') }}</th>
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">
                                            {{ __('invoice.amount') }}</th>
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">
                                            {{ __('invoice.payment_mode') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $installments = Modules\Appointment\Models\Installment::where(
                                            'billing_record_id',
                                            $info['patient_encounter']['billingrecord']['id'],
                                        )->get();
                                    @endphp
                                    @if (count(value: $installments) > 0)
                                        @foreach ($installments as $installment)
                                            <tr>
                                                <td style="padding: 8px; border: 1px solid #ddd;">
                                                    {{ date('d M Y', strtotime($installment['date'] ?? $installment['created_at'])) }}
                                                </td>
                                                <td style="padding: 8px; border: 1px solid #ddd;">
                                                    {{ Currency::format($installment['amount']) }}
                                                </td>
                                                <td style="padding: 8px; border: 1px solid #ddd;">
                                                    {{ $installment['payment_mode'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3"
                                                style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                                                {{ __('invoice.no_payments_recorded') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </td>

                    <!-- Payment Summary (Right) -->
                    <td width="40%" valign="top" style="padding-left: 15px;">
                        @php
                            // Calculate service total amount from billing items - matching billing_detail logic
                            $service_total_amount = 0;
                            // $total_tax = 0; // Tax calculation disabled
                        @endphp
                        @if (isset($info['patient_encounter']['billingrecord']['billing_item']))
                            @foreach ($info['patient_encounter']['billingrecord']['billing_item'] as $item)
                                @php
                                    $service_total_amount += $item['total_amount'];
                                @endphp
                            @endforeach
                        @endif
                        @php
                            // Get tax data and billing record - Tax calculation disabled
                            // $tax = $info['appointmenttransaction']['tax_percentage'] ?? null;
                            // $taxData = $tax ? json_decode($tax, true) : [];
                            $transaction = $info['patient_encounter']['billingrecord'] ?? null;

                            // Calculate discount amount - matching billing_detail logic
                            $discount_amount = 0;
                            if ($transaction && $transaction['final_discount_type'] == 'percentage') {
                                $discount_amount = $service_total_amount * ($transaction['final_discount_value'] / 100);
                            } else {
                                $discount_amount = $transaction['final_discount_value'] ?? 0;
                            }

                            // Calculate subtotal after discount
                            $sub_total = $service_total_amount - $discount_amount;

                            // Calculate inclusive tax total for display adjustments - Tax calculation disabled
                            // $inclusiveTaxTotal = 0;
                            // if (is_array($taxData)) {
                            //     foreach ($taxData as $t) {
                            //         if (
                            //             (isset($t['tax_type']) && $t['tax_type'] === 'inclusive') ||
                            //             (isset($t['tax_type']) && $t['tax_scope'] === 'inclusive')
                            //         ) {
                            //             if ($t['type'] === 'percent') {
                            //                 $inclusiveTaxTotal += $service_total_amount * ($t['value'] / 100);
                            //             } elseif ($t['type'] === 'fixed') {
                            //                 $inclusiveTaxTotal += $t['value'];
                            //             }
                            //         }
                            //     }
                            // }
                            $inclusiveTaxTotal = 0; // Tax calculation disabled
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
                            @if ($transaction && $transaction['final_discount'] == 1)
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
                                            {{ Currency::format($sub_total) }}</td>
                                    </tr>
                                </table>
                            @endif
                            {{-- Tax calculation completely disabled --}}
                            @php
                                // Calculate total paid from installments
                                $total_paid_from_installments = 0;
                                if (count($installments) > 0) {
                                    foreach ($installments as $installment) {
                                        $total_paid_from_installments += $installment['amount'];
                                    }
                                }

                                // Calculate final amounts - matching billing_detail logic
                                $amount_due = $sub_total; // Tax calculation disabled
                                $grand_total = $transaction['final_total_amount'] ?? $amount_due;

                                // Determine payment status and amounts
                                $is_paid = $info['appointmenttransaction']['payment_status'] == 1;
                                $total_paid = $transaction['total_paid'] ?? ($info['advance_paid_amount'] ?? 0);
                                $actual_total_paid =
                                    $total_paid_from_installments > 0 ? $total_paid_from_installments : $total_paid;

                                // Calculate paid and remaining amounts
                                $paid_amount = $actual_total_paid;
                                if ($is_paid) {
                                    $remaining_amount = 0;
                                } else {
                                    $remaining_amount = max(0, $grand_total - $paid_amount);
                                }
                            @endphp
                            <table width="100%" class="summary-row total" style="">
                                <tr>
                                    <td width="50%">{{ __('invoice.net_total') }}</td>
                                    <td width="50%" style="text-align: right;">
                                        @if ($transaction && $transaction['final_total_amount'])
                                            {{ Currency::format($transaction['final_total_amount']) }}
                                        @else
                                            {{ Currency::format($grand_total) }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" class="summary-row paid" style="">
                                <tr>
                                    <td width="50%">{{ __('invoice.paid') }}</td>
                                    <td width="50%" style="text-align: right;">
                                        {{ Currency::format($paid_amount) }}</td>
                                </tr>
                            </table>
                            <table width="100%" class="summary-row unpaid">
                                <tr>
                                    <td width="50%">{{ __('invoice.unpaid') }}</td>
                                    <td width="50%" style="text-align: right;">
                                        {{ Currency::format($remaining_amount) }}</td>
                                </tr>
                            </table>

                            @php
                                // Calculate refund if overpaid - matching billing_detail logic
                                $showRefundNote = false;
                                $refundAmount = 0;
                                $final_total = $transaction['final_total_amount'] ?? $grand_total;

                                if ($actual_total_paid > $final_total) {
                                    $showRefundNote = true;
                                    $refundAmount = $actual_total_paid - $final_total;
                                }
                            @endphp

                            @if ($showRefundNote)
                                <table width="100%" class="summary-row"
                                    style=" padding: 8px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
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
                    <div style=" font-size: 14px; font-weight: bold; color: #00C2CB;">
                        {{ config('app.name') }}
                    </div>
                @endif
            </p>
        </div>

    </div>
</body>

</html>
