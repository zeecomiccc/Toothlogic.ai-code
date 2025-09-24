<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MouthArcLab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
            color: #333;
            margin: 0cm;
            padding: 3% 10% 3% 10%;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .tagline {
            font-size: 10px;
            color: #666;
            font-weight: normal;
            letter-spacing: 1px;
        }

        .form-section {
            margin-bottom: 10px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .form-table td {
            padding: 8px 20px 8px 0;
            vertical-align: top;
        }

        .form-table td.left {
            width: 50%;
        }

        .form-table td.right {
            width: 50%;
        }

        .form-group label {
            width: 110px;
            display: inline-block;
            font-weight: bold;
            color: #333;
            margin-right: 10px;
            font-size: 14px;
        }

        .form-line {
            display: inline-block;
            border-bottom: 2px solid #ddd;
            min-width: 150px;
            height: 20px;
            margin-left: 5px;
            font-weight: bold;
            font-size: 14px;
        }
        .teeth-title {
            color: #00C2CB;
            text-align: center;
            font-size: 23px;
            font-weight: bold;
        }

        .teeth-diagram {
            padding: 10px;
            position: relative;
            background: white;
            height: 350px;
        }

        .teeth-cross {
            position: relative;
            width: 100%;
            height: 85%;
        }

        .cross-line-horizontal {
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #ccc;
            top: 65%;
            left: 0;
            margin-top: -1px;
        }

        .cross-line-vertical {
            position: absolute;
            height: 125%;
            width: 2px;
            background-color: #ccc;
            left: 50%;
            top: 0;
            margin-left: -1px;
        }

        .quadrant {
            position: absolute;
            font-size: 14px;
            max-height: 140px;
            overflow-y: auto;
            padding: 5px;
            font-weight: bold;
        }

        .quadrant-label {
            font-weight: bold;
            font-style: italic;
            color: #333;
            padding: 7px;
        }

        .teeth-section {
            padding-bottom: 0%;
            margin-bottom: 0%;
            height: auto; /* ensure the section fully contains the diagram */
        }

        .teeth-numbers {
            font-weight: bold;
            letter-spacing: 2px;
            color: #333;
        }

        .teeth-table {
            margin-top: 10px;
            border-collapse: separate;
            border-spacing: 3px;
            text-align: center;
        }

        .teeth-table td {
            padding: 0;
            text-align: center;
        }

        .tooth-number {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 13px;
            line-height: 1.6;
        }

        .tooth-number.selected {
            background-color: #00C2CB;
            color: white;
        }

        .ur {
            top: 10px;
            left: 25%;
            text-align: center;
            transform: translateX(-50%);
            padding-right: 30px;
            padding-bottom: 80px;
        }

        .ul {
            top: 10px;
            right: 25%;
            text-align: center;
            transform: translateX(50%);
            padding-left: 30px;
            padding-bottom: 80px;
        }

        .lr {
            top: 65%;
            left: 25%;
            text-align: center;
            transform: translateX(-50%);
            padding-right: 30px;
        }

        .ll {
            top: 65%;
            right: 25%;
            text-align: center;
            transform: translateX(50%);
            padding-left: 30px;
        }

        .treatments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .treatments-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 30px;
        }

        .treatments-table td.right-column {
            padding-right: 0;
            padding-left: 30px;
        }

        .treatment-item {
            margin-bottom: 8px;
            font-size: 12px;
        }

        .bullet {
            width: 8px;
            height: 8px;
            background-color: #00C2CB;
            border-radius: 50%;
            display: inline-block;
            margin-right: 12px;
            vertical-align: middle;
        }

        .treatment-text {
            font-size: 12px;
            color: #333;
            display: inline-block;
            line-height: 1.2;
            font-weight: bold;
        }

        .treatments-list {
            max-height: 80px;
            overflow-y: auto;
            margin-top: 7px;
            text-align: left;
        }    
        .additional-info {
            padding-top: 35px; 
            font-size: 14px; 
        }

        .additional-info strong label {
            font-size: 14px;
        }

        .instructions-title {
            color: #00C2CB;
            text-align: center;
            padding: 12px;
            font-size: 23px;
            font-weight: bold;
        }

        .instructions-box {
            min-height: 50px;
            background: white;
        }

        .instruction-line {
            border-bottom: 2px solid #ddd;
            min-height: 25px;
            margin-bottom: 8px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: pre-wrap;
            width: 100%;
            display: block;
        }

        .footer {
            background: #00C2CB;
            color: #fff;
            text-align: center;
            padding: 9px;
            font-size: 13px;
        }

        .footer-fullwidth {
            width: 100vw;
            margin-left: calc(-50vw + 50%);
            left: 0;
            right: 0;
            position: fixed;
            bottom: 0;
            padding-left: 0 !important;
            padding-right: 0 !important;
            box-sizing: border-box;
            z-index: 1000;
        }

        @media print {
            /* Reserve space for fixed header and footer on paper to avoid overlap */
            .header {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                background: #ffffff;
                z-index: 1000;
                padding-top: 5px;
                height: 130px; /* increased header height for print */
            }

            .footer-fullwidth {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                margin: 0;
                padding-left: 0 !important;
                padding-right: 0 !important;
                box-sizing: border-box;
                height: 40px; /* explicit footer height for print */
            }

            body {
                /* leave space for header and footer when printing */
                padding-top: 160px !important; /* header height + small gap */
                padding-bottom: 40px !important; /* footer height + small gap */
            }
        }

        @page {
            margin: 0cm;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                @php
                    $imagePath = public_path('img/logo/mouth_arc_lab_form.png');
                    if (file_exists($imagePath)) {
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $site_logo = 'data:image/png;base64,' . $imageData;
                    } else {
                        $site_logo = '';
                    }
                @endphp
                <img src="{{ $site_logo }}" alt="MOUTHARC" style="width: 60%; height: auto; vertical-align: middle;">
            </div>
        </div>

        @php
            $shadeSelection = [];
            if (!empty($lab->shade_selection)) {
                if (is_string($lab->shade_selection)) {
                    $decoded = json_decode($lab->shade_selection, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $shadeSelection = $decoded;
                    }
                } elseif (is_array($lab->shade_selection)) {
                    $shadeSelection = $lab->shade_selection;
                }
            }
        @endphp

        <div class="form-section">
            @php
                $doctorUser = optional($lab)->doctor;
                $patientUser = optional($lab)->patient;
                $doctorName = $doctorUser->full_name ?? trim(($doctorUser->first_name ?? '').' '.($doctorUser->last_name ?? ''));
                $patientName = $patientUser->full_name ?? trim(($patientUser->first_name ?? '').' '.($patientUser->last_name ?? ''));
            @endphp
            <table class="form-table form-group">
                <tr>
                    <td class="left">
                        <strong><label>{{ __('lab.doctor_name') }}</label></strong>
                        <span class="form-line">{{ $doctorName }}</span>
                    </td>
                    <td class="right">
                        <strong><label>{{ __('lab.invoice_no') }}</label></strong>
                        <span class="form-line">LAB {{ $lab->id ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        <strong><label>{{ __('lab.patient_name') }}</label></strong>
                        <span class="form-line">{{ $patientName }}</span>
                    </td>
                    <td class="right">
                        <strong><label>{{ __('lab.amount') }}</label></strong>
                        <span class="form-line"></span>
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        <strong><label>Cervical Shade</label></strong>
                        <span class="form-line">{{ $shadeSelection['Cervical'] ?? '' }}</span>
                    </td>
                    <td class="right">
                        <strong><label>{{ __('lab.balance') }}</label></strong>
                        <span class="form-line"></span>
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        <strong><label>Middle Shade</label></strong>
                        <span class="form-line">{{ $shadeSelection['Middle'] ?? '' }}</span>
                    </td>
                    <td class="right">
                        <strong><label>{{ __('lab.sign') }}</label></strong>
                        <span class="form-line"></span>
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        <strong><label>Incisal Shade</label></strong>
                        <span class="form-line">{{ $shadeSelection['Incisal'] ?? '' }}</span>
                    </td>
                    <td class="right"></td>
                </tr>
            </table>
        </div>

        @php
            $groupedTeethTreatments = [
                'ur' => ['teeth' => [], 'treatments' => []],
                'ul' => ['teeth' => [], 'treatments' => []],
                'lr' => ['teeth' => [], 'treatments' => []],
                'll' => ['teeth' => [], 'treatments' => []],
            ];

            if (!empty($lab->teeth_treatment_type) && is_array($lab->teeth_treatment_type)) {
                foreach ($lab->teeth_treatment_type as $item) {
                    if (is_array($item) && isset($item['quadrant'])) {
                        $q = strtolower($item['quadrant']);
                        if (!isset($groupedTeethTreatments[$q])) {
                            $groupedTeethTreatments[$q] = ['teeth' => [], 'treatments' => []];
                        }

                        if (isset($item['teeth']) && is_array($item['teeth'])) {
                            $groupedTeethTreatments[$q]['teeth'] = array_values(array_unique(array_merge($groupedTeethTreatments[$q]['teeth'], $item['teeth'])));
                        }

                        if (isset($item['treatments']) && is_array($item['treatments'])) {
                            $groupedTeethTreatments[$q]['treatments'] = array_values(array_unique(array_merge($groupedTeethTreatments[$q]['treatments'], $item['treatments'])));
                        }
                    }
                }

                foreach ($groupedTeethTreatments as $q => &$data) {
                    sort($data['teeth']);
                }
                unset($data);
            }
        @endphp

        <div class="teeth-section">
            <div class="teeth-title">{{ __('lab.select_teeth_for_treatment') }}</div>
            <div class="teeth-diagram">
                <div class="teeth-cross">
                    <div class="cross-line-horizontal"></div>
                    <div class="cross-line-vertical"></div>

                    <div class="quadrant ur">
                        <div class="quadrant-label">{{ __('lab.ur') }}</div>
                        <table class="teeth-table">
                            <tr>
                                <td><div class="tooth-number {{ in_array(18, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">18</div></td>
                                <td><div class="tooth-number {{ in_array(17, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">17</div></td>
                                <td><div class="tooth-number {{ in_array(16, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">16</div></td>
                                <td><div class="tooth-number {{ in_array(15, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">15</div></td>
                                <td><div class="tooth-number {{ in_array(14, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">14</div></td>
                                <td><div class="tooth-number {{ in_array(13, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">13</div></td>
                                <td><div class="tooth-number {{ in_array(12, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">12</div></td>
                                <td><div class="tooth-number {{ in_array(11, $groupedTeethTreatments['ur']['teeth'] ?? []) ? 'selected' : '' }}">11</div></td>
                            </tr>
                        </table>
                        @if(!empty($groupedTeethTreatments['ur']['treatments']))
                            <div class="treatments-list">
                                @foreach($groupedTeethTreatments['ur']['treatments'] as $treatment)
                                    <div class="treatment-item">
                                        <div class="bullet"></div>
                                        <div class="treatment-text">{{ ucwords(str_replace('_', ' ', $treatment)) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="quadrant ul">
                        <div class="quadrant-label">{{ __('lab.ul') }}</div>
                        <table class="teeth-table">
                            <tr>
                                <td><div class="tooth-number {{ in_array(21, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">21</div></td>
                                <td><div class="tooth-number {{ in_array(22, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">22</div></td>
                                <td><div class="tooth-number {{ in_array(23, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">23</div></td>
                                <td><div class="tooth-number {{ in_array(24, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">24</div></td>
                                <td><div class="tooth-number {{ in_array(25, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">25</div></td>
                                <td><div class="tooth-number {{ in_array(26, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">26</div></td>
                                <td><div class="tooth-number {{ in_array(27, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">27</div></td>
                                <td><div class="tooth-number {{ in_array(28, $groupedTeethTreatments['ul']['teeth'] ?? []) ? 'selected' : '' }}">28</div></td>
                            </tr>
                        </table>
                        @if(!empty($groupedTeethTreatments['ul']['treatments']))
                            <div class="treatments-list">
                                @foreach($groupedTeethTreatments['ul']['treatments'] as $treatment)
                                    <div class="treatment-item">
                                        <div class="bullet"></div>
                                        <div class="treatment-text">{{ ucwords(str_replace('_', ' ', $treatment)) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="quadrant lr">
                        <div class="quadrant-label">{{ __('lab.lr') }}</div>
                        <table class="teeth-table">
                            <tr>
                                <td><div class="tooth-number {{ in_array(48, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">48</div></td>
                                <td><div class="tooth-number {{ in_array(47, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">47</div></td>
                                <td><div class="tooth-number {{ in_array(46, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">46</div></td>
                                <td><div class="tooth-number {{ in_array(45, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">45</div></td>
                                <td><div class="tooth-number {{ in_array(44, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">44</div></td>
                                <td><div class="tooth-number {{ in_array(43, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">43</div></td>
                                <td><div class="tooth-number {{ in_array(42, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">42</div></td>
                                <td><div class="tooth-number {{ in_array(41, $groupedTeethTreatments['lr']['teeth'] ?? []) ? 'selected' : '' }}">41</div></td>
                            </tr>
                        </table>
                        @if(!empty($groupedTeethTreatments['lr']['treatments']))
                            <div class="treatments-list">
                                @foreach($groupedTeethTreatments['lr']['treatments'] as $treatment)
                                    <div class="treatment-item">
                                        <div class="bullet"></div>
                                        <div class="treatment-text">{{ ucwords(str_replace('_', ' ', $treatment)) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="quadrant ll">
                        <div class="quadrant-label">{{ __('lab.ll') }}</div>
                        <table class="teeth-table">
                            <tr>
                                <td><div class="tooth-number {{ in_array(31, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">31</div></td>
                                <td><div class="tooth-number {{ in_array(32, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">32</div></td>
                                <td><div class="tooth-number {{ in_array(33, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">33</div></td>
                                <td><div class="tooth-number {{ in_array(34, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">34</div></td>
                                <td><div class="tooth-number {{ in_array(35, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">35</div></td>
                                <td><div class="tooth-number {{ in_array(36, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">36</div></td>
                                <td><div class="tooth-number {{ in_array(37, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">37</div></td>
                                <td><div class="tooth-number {{ in_array(38, $groupedTeethTreatments['ll']['teeth'] ?? []) ? 'selected' : '' }}">38</div></td>
                            </tr>
                        </table>
                        @if(!empty($groupedTeethTreatments['ll']['treatments']))
                            <div class="treatments-list">
                                @foreach($groupedTeethTreatments['ll']['treatments'] as $treatment)
                                    <div class="treatment-item">
                                        <div class="bullet"></div>
                                        <div class="treatment-text">{{ ucwords(str_replace('_', ' ', $treatment)) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="additional-info">
            <table class="form-table form-group">
                <tr>
                    <td class="left">
                        <strong><label>Margin Location</label></strong>
                        <span class="form-line">{{ $lab->margin_location ?? '' }}</span>
                    </td>
                    <td class="right">
                        <strong><label>Contact Tightness</label></strong>
                        <span class="form-line">{{ $lab->contact_tightness ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        <strong><label>Occlusal Scheme</label></strong>
                        <span class="form-line">{{ $lab->occlusal_scheme ?? '' }}</span>
                    </td>
                    <td class="right">
                        <strong><label>Temporary Placed</label></strong>
                        <span class="form-line">{{ $lab->temporary_placed ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="instructions-section">
            <div class="instructions-title">{{ __('lab.specific_instructions') }}</div>
            <div class="instructions-box">
                @if(!empty($lab->notes))
                    <div class="instruction-line">{{ $lab->notes }}</div>
                  
                @else
                    <div class="instruction-line">&nbsp;</div>
                    <div class="instruction-line">&nbsp;</div>
                    <div class="instruction-line">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    <div class="footer footer-fullwidth">
        {{ __('lab.footer_address') }}
    </div>
</body>

</html>
