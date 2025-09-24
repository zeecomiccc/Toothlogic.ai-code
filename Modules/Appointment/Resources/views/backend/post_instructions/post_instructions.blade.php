<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.post_instructions') }}</title>
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
    <div class="container">
        <!-- Header Section -->
        {{-- <div style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #333;">
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
                        <img src="{{ $site_logo }}" alt="logo" style="height: 75px; width: auto;">
                    </td>
                </tr>
            </table>
        </div> --}}

        <!-- Main Content -->
        <div class="main-content">
            <!-- Notes Section -->
            <div class="notes-section">
                <div class="notes-box">
                    @if (is_array($postInstructions) || $postInstructions instanceof \Illuminate\Support\Collection)
                        @foreach ($postInstructions as $instruction)
                            <div style="margin-bottom: 30px; page-break-inside: avoid;">
                                {!! $instruction->post_instructions !!}
                            </div>
                            @if (!$loop->last)
                                <hr style="border: 1px solid #ddd; margin: 20px 0;">
                            @endif
                        @endforeach
                    @else
                        @if (!empty($postInstructions->post_instructions))
                            {!! $postInstructions->post_instructions !!}
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="footer">
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
