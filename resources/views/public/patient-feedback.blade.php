<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ language_direction() }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('feedback.patient_feedback_title') }}</title>
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" href="{{ asset(setting('logo')) }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset(setting('favicon')) }}">
    <link rel="shortcut icon" href="{{ asset(setting('favicon')) }}">
    <link rel="icon" type="image/ico" href="{{ asset(setting('favicon')) }}" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Phosphor Icons -->
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/fill/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #00C2CB;
            --bs-secondary: #4A4A4A;
            --bs-success: #219653;
            --bs-warning: #faa938;
            --bs-danger: #c03221;
            --bs-info: #08b1ba;
            --bs-light: #f8f9fa;
            --bs-dark: #1E1E24;
            --bs-gray: #6E8192;
            --bs-gray-dark: #343a40;
            --bs-body-bg: #f8f9fa;
            --bs-body-color: #212529;
            --bs-border-color: #dee2e6;
            --bs-card-bg: #ffffff;
            --bs-navbar-bg: #ffffff;
            --bs-navbar-color: #212529;
        }

        [data-bs-theme="dark"] {
            --bs-body-bg: #1a1a1a;
            --bs-body-color: #ffffff;
            --bs-border-color: #495057;
            --bs-card-bg: #2d2d2d;
            --bs-navbar-bg: #2d2d2d;
            --bs-navbar-color: #ffffff;
            --bs-light: #2a2a2a;
        }

        [data-bs-theme="dark"] .nav-icon {
            color: #ffffff;
        }

        [data-bs-theme="dark"] .nav-icon:hover {
            background-color: rgba(0, 194, 203, 0.2);
            color: #00d4e0;
        }

        [data-bs-theme="dark"] .language-selector {
            color: #ffffff;
        }

        [data-bs-theme="dark"] .language-selector:hover {
            background-color: rgba(0, 194, 203, 0.2);
            color: #00d4e0;
        }

        /* Logo Theme Switching */
        .logo-light {
            display: block !important;
        }
        
        .logo-dark {
            display: none !important;
        }
        
        [data-bs-theme="dark"] .logo-light {
            display: none !important;
        }
        
        [data-bs-theme="dark"] .logo-dark {
            display: block !important;
        }

        /* Form validation styles */
        .form-control.is-invalid {
            border-color: var(--bs-danger);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control.is-invalid:focus {
            border-color: var(--bs-danger);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: var(--bs-danger);
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            font-family: 'Poppins';
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar */
        .navbar {
            background-color: var(--bs-navbar-bg) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--bs-primary) !important;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: var(--bs-navbar-color) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: var(--bs-primary);
            color: white !important;
        }

        .nav-icon {
            background: none;
            border: none;
            color: var(--bs-body-color);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
            font-size: 1.2rem;
            border-radius: 8px;
        }

        .nav-icon:hover {
            background-color: rgba(0, 194, 203, 0.1);
            color: var(--bs-primary);
            transform: scale(1.05);
        }

        .language-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            color: var(--bs-body-color);
            transition: all 0.3s ease;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .language-selector:hover {
            background-color: rgba(0, 194, 203, 0.1);
            color: var(--bs-primary);
        }

        .language-selector img {
            width: 20px;
            height: auto;
        }

        .language-dropdown {
            background-color: var(--bs-card-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .language-dropdown .dropdown-item {
            color: var(--bs-body-color);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .language-dropdown .dropdown-item:hover {
            background-color: var(--bs-primary);
            color: white;
        }

        .language-dropdown .dropdown-item img {
            width: 20px;
            height: auto;
            margin-right: 0.5rem;
        }

        /* Main Container */
        .main-container {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .feedback-container {
            background: var(--bs-card-bg);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 900px;
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #00a3b3 100%);
            color: white;
            padding: 2rem 2rem;
            text-align: center;
            position: relative;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .doctor-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.3);
            margin: 0 auto 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            position: relative;
            z-index: 1;
        }

        .header-section h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .header-section p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .header-section small {
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .form-section {
            padding: 3rem 2rem;
        }

        .section-title {
            color: var(--bs-primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.75rem;
            color: var(--bs-body-color);
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            border: 2px solid var(--bs-border-color);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background-color: var(--bs-card-bg);
            color: var(--bs-body-color);
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 194, 203, 0.25);
            background-color: var(--bs-card-bg);
        }

        .form-control::placeholder {
            color: var(--bs-gray);
        }

        .rating-scale {
            margin-top: 1rem;
        }

        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: var(--bs-light);
            border-radius: 0.75rem;
            border: 1px solid var(--bs-border-color);
        }

        .radio-group .form-check {
            flex: 1;
            min-width: 120px;
            justify-content: flex-start;
        }

        [data-bs-theme="dark"] .radio-group {
            background-color: #2a2a2a;
            border-color: #495057;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-check {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            border: 2px solid var(--bs-primary);
            border-radius: 50%;
            width: 1.2em;
            height: 1.2em;
            transition: all 0.3s ease;
            background-color: var(--bs-card-bg);
            position: relative;
            margin: 0;
            flex-shrink: 0;
        }

        [data-bs-theme="dark"] .form-check-input {
            background-color: #2a2a2a;
            border-color: var(--bs-primary);
        }

        .form-check-input:checked {
            border-color: var(--bs-primary);
            background-color: var(--bs-primary);
            
            background-position: center;
            background-repeat: no-repeat;
        }

        .form-check-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 194, 203, 0.25);
        }

        .form-check-label {
            font-size: 0.9rem;
            margin-bottom: 0;
            color: var(--bs-body-color);
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        [data-bs-theme="dark"] .form-check-label {
            color: #ffffff;
        }

        [data-bs-theme="dark"] .form-check:hover .form-check-input {
            border-color: #00d4e0;
            transform: scale(1.05);
        }

        [data-bs-theme="dark"] .form-check:hover .form-check-label {
            color: #00d4e0;
        }

        /* Number input styling */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Rating scale radio buttons */
        .rating-scale .radio-group {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .rating-scale .form-check {
            margin: 0;
            min-width: 30px;
            justify-content: center;
        }

        .rating-scale .form-check-input {
            width: 1em;
            height: 1em;
        }

        .rating-scale .form-check-label {
            font-size: 0.8rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #00a3b3 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 3rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 194, 203, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 194, 203, 0.4);
            color: white;
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
        }

        .text-danger {
            color: var(--bs-danger) !important;
        }

        .section-divider {
            border-top: 2px solid var(--bs-border-color);
            margin: 2.5rem 0;
            padding-top: 1.5rem;
        }

        .rating-label {
            font-size: 0.8rem;
            color: var(--bs-gray);
            margin: 0 0.75rem;
        }

        /* Star Rating Styles */
        .star {
            cursor: pointer;
            transition: transform 0.3s ease;
            display: inline-block;
            margin: 0 3px;
        }

        .star:hover {
            transform: scale(1.2);
        }

        .star.selected .icon-fill {
            display: inline-block !important;
            color: #ffc107 !important;
        }

        .star.selected .icon-normal {
            display: none !important;
        }

        .icon-fill {
            display: none;
        }

        .icon-normal {
            display: inline-block;
            color: var(--bs-gray) !important;
        }

        .star .icon {
            font-size: 1.8rem;
        }

        .rating-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 5px;
        }

        .rating-list li {
            display: inline-block;
        }

        .is-invalid {
            border-color: var(--bs-danger) !important;
        }

        .border-danger {
            border: 2px solid var(--bs-danger) !important;
            border-radius: 0.75rem;
            padding: 0.75rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-section {
                padding: 2rem 1rem;
            }
            
            .form-section {
                padding: 2rem 1rem;
            }
            
            .radio-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .radio-group .form-check {
                min-width: auto;
                width: 100%;
            }
            
            .rating-list {
                justify-content: center;
            }

            .rating-scale .radio-group {
                gap: 0.15rem;
            }

            .rating-scale .form-check {
                min-width: 25px;
            }
        }

        /* RTL Support */
        [dir="rtl"] .me-2 {
            margin-right: 0 !important;
            margin-left: 0.5rem !important;
        }

        [dir="rtl"] .ms-2 {
            margin-left: 0 !important;
            margin-right: 0.5rem !important;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" class="logo-light" style="height: 40px; display: block;">
                <img src="{{ asset('img/logo/dark_logo.png') }}" alt="Logo" class="logo-dark" style="height: 40px; display: none;">
            </a>
            
            <div class="navbar-nav ms-auto align-items-center">
                <!-- Theme Toggle -->
                <button class="nav-icon" id="themeToggle" title="Toggle Theme">
                    <i class="ph ph-sun" id="themeIcon"></i>
                </button>
                
                <!-- Language Selector -->
                <div class="dropdown">
                    <button class="language-selector" type="button" data-bs-toggle="dropdown" title="Select Language">
                        <i class="ph ph-globe"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <ul class="dropdown-menu language-dropdown">
                        @foreach (config('app.available_locales') as $locale => $title)
                            <li>
                                <a class="dropdown-item" href="{{ route('language.switch', $locale) }}">
                                    <img src="{{ asset('flags/' . $locale . '.png') }}" 
                                         alt="flag"
                                         onerror="this.src='{{ asset('flags/globe.png') }}'">
                                    {{ $title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container">
        <div class="container">
            <div class="feedback-container">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="doctor-avatar">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h2 class="mb-2">{{ __('feedback.patient_feedback_form') }}</h2>
                </div>

                <!-- Form Section -->
                <div class="form-section">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('patient.feedback.store') }}" id="feedbackForm">
                         @csrf
                         <input type="hidden" name="doctor_id" value="{{ $doctor->id ?? 19 }}">
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         
                         
                        <!-- Patient Information Section -->
                        <div class="section-title">
                            <i class="fas fa-user"></i>{{ __('feedback.patient_information') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="form-label">{{ __('feedback.first_name') }}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $customer->first_name ?? '' }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="form-label">{{ __('feedback.last_name') }}</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $customer->last_name ?? '' }}">

                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">{{ __('feedback.email_address') }}</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email ?? '' }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{ __('feedback.phone_number') }}</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $customer->mobile ?? '' }}">

                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="age" class="form-label">{{ __('feedback.age') }}</label>
                                    <input type="number" class="form-control" id="age" name="age" value="{{ $customer && $customer->date_of_birth ? \Carbon\Carbon::parse($customer->date_of_birth)->age : '' }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="treatments" class="form-label">{{ __('feedback.treatments_received') }}</label>
                                    <textarea class="form-control" id="treatments" name="treatments" rows="2" placeholder="{{ __('feedback.treatments_placeholder') }}"></textarea>

                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('feedback.how_did_you_hear_about_us') }}</label>
                                    <div class="radio-group">
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referral_source" id="socialMedia" value="Social Media">
                                            <label class="form-check-label" for="socialMedia">{{ __('feedback.social_media') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referral_source" id="walkin" value="Walk In">
                                            <label class="form-check-label" for="walkin">{{ __('feedback.walk_in') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referral_source" id="friendFamily" value="Friend/Family">
                                            <label class="form-check-label" for="friendFamily">{{ __('feedback.friend_family') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referral_source" id="other" value="Other">  
                                            <label class="form-check-label" for="other">{{ __('feedback.other') }}</label>
                                        </div>
                                    </div>
                                    <div id="referral_other_group" class="mt-2" style="display: none;">
                                        <input type="text" class="form-control" id="referral_source_other" name="referral_source_other" value="" placeholder="{{ __('feedback.other_specify') }}">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clinic_location" class="form-label">{{ __('feedback.clinic_location') }}</label>
                                    <input type="text" class="form-control" id="clinic_location" name="clinic_location" value="" placeholder="{{ __('feedback.clinic_location_placeholder') }}">

                                </div>
                            </div>
                            
                        </div>


                         <!-- Ratings Section -->
                         <div class="section-title">
                             <i class="fas fa-chart-bar"></i>{{ __('feedback.ratings') }}
                         </div>


                        <!-- Experience Rating -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('feedback.overall_experience_with_doctor') }}</label>
                                    <div class="mt-3">
                                        <ul class="rating-list">
                                            @for($i = 1; $i <= 10; $i++)
                                            <li data-value="{{ $i }}" class="star experience-star">
                                                <span class="icon">
                                                    <i class="ph-fill ph-star icon-fill"></i>
                                                    <i class="ph ph-star icon-normal"></i>
                                                </span>
                                            </li>
                                            @endfor
                                        </ul>
                                        <input type="hidden" name="experience_rating" id="experience_rating_value" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor's Explanation Rating -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('feedback.dentist_explanation') }}
                                    </label>
                                    <div class="rating-scale mt-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="radio-group d-flex gap-3">
                                                <span class="text-muted small">{{ __('feedback.not_at_all') }}</span>
                                                @for($i = 1; $i <= 10; $i++)
                                                <div class="form-check">
                                                                                                         <input class="form-check-input" type="radio" name="dentist_explanation" id="explanation{{$i}}" value="{{$i}}">
                                                    <label class="form-check-label small" for="explanation{{$i}}">{{$i}}</label>
                                                </div>
                                                @endfor
                                                <span class="text-muted small">{{ __('feedback.yes_completely') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Pricing Satisfaction -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('feedback.pricing_satisfaction') }}</label>
                                    <div class="mt-3">
                                        <ul class="rating-list">
                                            @for($i = 1; $i <= 10; $i++)
                                            <li data-value="{{ $i }}" class="star pricing-star">
                                                <span class="icon">
                                                    <i class="ph-fill ph-star icon-fill"></i>
                                                    <i class="ph ph-star icon-normal"></i>
                                                </span>
                                            </li>
                                            @endfor
                                        </ul>
                                        <input type="hidden" name="pricing_satisfaction" id="pricing_rating_value" value="">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Staff Courtesy -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('feedback.staff_courtesy') }}
                                    </label>
                                    <div class="rating-scale mt-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="radio-group d-flex gap-3">
                                                <span class="text-muted small">{{ __('feedback.poor') }}</span>
                                                @for($i = 1; $i <= 10; $i++)
                                                <div class="form-check">
                                                                                                         <input class="form-check-input" type="radio" name="staff_courtesy" id="courtesy{{$i}}" value="{{$i}}">
                                                    <label class="form-check-label small" for="courtesy{{$i}}">{{$i}}</label>
                                                </div>
                                                @endfor
                                                <span class="text-muted small">{{ __('feedback.excellent') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Treatment Satisfaction -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('feedback.treatment_satisfaction') }}
                                    </label>
                                    <div class="mt-3">
                                        <ul class="rating-list">
                                            @for($i = 1; $i <= 10; $i++)
                                            <li data-value="{{ $i }}" class="star treatment-star">
                                                <span class="icon">
                                                    <i class="ph-fill ph-star icon-fill"></i>
                                                    <i class="ph ph-star icon-normal"></i>
                                                </span>
                                            </li>
                                            @endfor
                                        </ul>
                                        <input type="hidden" name="treatment_satisfaction" id="treatment_rating_value" value="">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="review_msg" class="form-label">{{ __('feedback.comments_and_suggestions') }}</label>
                                    <textarea class="form-control" id="review_msg" name="review_msg" rows="4" placeholder="{{ __('feedback.comments_placeholder') }}"></textarea>
                                
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                         <div class="text-center mt-5 pt-3">
                             <button type="submit" class="btn btn-submit" id="submitBtn">
                                 <i class="fas fa-paper-plane me-2"></i>{{ __('feedback.submit_feedback') }}
                             </button>
                         </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedRating = 0;
            let selectedExperienceRating = 0;
            let selectedPricingRating = 0;
            let selectedTreatmentRating = 0;

            const overallStars = document.querySelectorAll('.overall-star');
            const experienceStars = document.querySelectorAll('.experience-star');
            const pricingStars = document.querySelectorAll('.pricing-star');
            const treatmentStars = document.querySelectorAll('.treatment-star');


            // Theme Toggle Functionality
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;

            // Load saved theme
            const savedTheme = localStorage.getItem('data-bs-theme') || 'light';
            html.setAttribute('data-bs-theme', savedTheme);
            updateThemeIcon(savedTheme);
            updateLogo(savedTheme);

            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('data-bs-theme', newTheme);
                updateThemeIcon(newTheme);
                updateLogo(newTheme);
            });

            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.className = 'ph ph-moon';
                } else {
                    themeIcon.className = 'ph ph-sun';
                }
            }

            function updateLogo(theme) {
                const logoLight = document.querySelector('.logo-light');
                const logoDark = document.querySelector('.logo-dark');
                
                if (theme === 'dark') {
                    logoLight.style.display = 'none';
                    logoDark.style.display = 'block';
                } else {
                    logoLight.style.display = 'block';
                    logoDark.style.display = 'none';
                }
            }

            // Star Rating Functionality
            function highlightStars(starElements, rating) {
                starElements.forEach(function(star) {
                    const starValue = parseInt(star.getAttribute('data-value'));
                    if (starValue <= rating) {
                        star.classList.add('selected');
                    } else {
                        star.classList.remove('selected');
                    }
                });
            }



            // Overall star rating
            overallStars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedRating = parseInt(this.getAttribute('data-value'));
                    document.getElementById('rating_value').value = selectedRating;
                    highlightStars(overallStars, selectedRating);
                });

                star.addEventListener('mouseenter', function() {
                    const hoverValue = parseInt(this.getAttribute('data-value'));
                    highlightStars(overallStars, hoverValue);
                });

                star.addEventListener('mouseleave', function() {
                    highlightStars(overallStars, selectedRating);
                });
            });

            // Experience star rating
            experienceStars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedExperienceRating = parseInt(this.getAttribute('data-value'));
                    document.getElementById('experience_rating_value').value = selectedExperienceRating;
                    highlightStars(experienceStars, selectedExperienceRating);
                });

                star.addEventListener('mouseenter', function() {
                    const hoverValue = parseInt(this.getAttribute('data-value'));
                    highlightStars(experienceStars, hoverValue);
                });

                star.addEventListener('mouseleave', function() {
                    highlightStars(experienceStars, selectedExperienceRating);
                });
            });

            // Pricing star rating
            pricingStars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedPricingRating = parseInt(this.getAttribute('data-value'));
                    document.getElementById('pricing_rating_value').value = selectedPricingRating;
                    highlightStars(pricingStars, selectedPricingRating);
                });

                star.addEventListener('mouseenter', function() {
                    const hoverValue = parseInt(this.getAttribute('data-value'));
                    highlightStars(pricingStars, hoverValue);
                });

                star.addEventListener('mouseleave', function() {
                    highlightStars(pricingStars, selectedPricingRating);
                });
            });

            // Treatment star rating
            treatmentStars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedTreatmentRating = parseInt(this.getAttribute('data-value'));
                    document.getElementById('treatment_rating_value').value = selectedTreatmentRating;
                    highlightStars(treatmentStars, selectedTreatmentRating);
                });

                star.addEventListener('mouseenter', function() {
                    const hoverValue = parseInt(this.getAttribute('data-value'));
                    highlightStars(treatmentStars, hoverValue);
                });

                star.addEventListener('mouseleave', function() {
                    highlightStars(treatmentStars, selectedTreatmentRating);
                });
            });

            // Show/hide referral source other field
            const referralRadios = document.querySelectorAll('input[name="referral_source"]');
            const referralOtherGroup = document.getElementById('referral_other_group');
            const referralOtherInput = document.getElementById('referral_source_other');

            referralRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'Other') {
                        referralOtherGroup.style.display = 'block';
                        referralOtherInput.required = true;
                    } else {
                        referralOtherGroup.style.display = 'none';
                        referralOtherInput.required = false;
                        referralOtherInput.value = '';
                    }
                });
            });

            // Initialize stars on page load
            highlightStars(overallStars, selectedRating);
            highlightStars(experienceStars, selectedExperienceRating);
            highlightStars(pricingStars, selectedPricingRating);
            highlightStars(treatmentStars, selectedTreatmentRating);

            

             // Form submission with validation and error handling
             const form = document.getElementById('feedbackForm');
             form.addEventListener('submit', function(e) {
                 // Basic validation
                 const requiredFields = ['first_name', 'last_name', 'email', 'rating'];
                 let isValid = true;
                 let firstInvalidField = null;
                 
                 requiredFields.forEach(fieldName => {
                     const field = form.querySelector(`[name="${fieldName}"]`);
                     if (field && !field.value.trim()) {
                         isValid = false;
                         if (!firstInvalidField) firstInvalidField = field;
                         field.classList.add('is-invalid');
                     } else if (field) {
                         field.classList.remove('is-invalid');
                     }
                 });
                 
                 if (!isValid) {
                     e.preventDefault();
                     if (firstInvalidField) {
                         firstInvalidField.focus();
                         showError('{{ __("feedback.please_fill_required_fields") }}');
                     }
                     return false;
                 }
                 
                 // Show loading state
                 const submitBtn = document.getElementById('submitBtn');
                 submitBtn.disabled = true;
                 submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("feedback.submitting") }}';
                 
                 // Let the form submit naturally
                 return true;
             });
             
             // Error display function
             function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger alert-dismissible fade show';
                errorDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                 
                 // Insert error at the top of the form
                 const form = document.getElementById('feedbackForm');
                 form.insertBefore(errorDiv, form.firstChild);
                 
                 // Auto-remove after 5 seconds
                 setTimeout(() => {
                     if (errorDiv.parentNode) {
                         errorDiv.remove();
                     }
                 }, 5000);
             }
        });
    </script>
</body>
</html>
