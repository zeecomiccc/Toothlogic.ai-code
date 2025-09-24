@extends('frontend::layouts.auth_layout')
@section('title',  __('frontend.register'))

@section('content')
    <div class="auth-container" id="login"
        style="background-image: url('{{ asset('img/frontend/auth-bg.png') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
        <div class="container h-100 min-vh-100">
            <div class="row h-100 min-vh-100 align-items-center">
                <div class="col-xl-4 col-lg-5 col-md-6 my-5">
                    <div class="auth-card">
                        <div class="text-center">
                          <!--   <div class="mb-5">
                            <div class="logo-default">
                                  <a class="navbar-brand text-primary" href="{{ route('frontend.index') }}"> 
                                      <div class="logo-main">
                              
                                          <div class="logo-normal">
                                              <img src="{{ asset(setting('logo')) }}" height="50" alt="{{ app_name() }}">
                                              
                                          </div>
                                          <div class="logo-dark d-none">
                                              <img src="{{ asset(setting('dark_logo')) }}" height="50" alt="{{ app_name() }}">
                                          </div>
                                      </div>
                                  </a>
                              </div>
                            </div> -->
                            @include('frontend::components.partials.logo')
                            <div class="auth-card-content mt-3">
                                <p class="text-danger mb-1" id="error_message"></p>
                                <form id="registerForm" action="post" class="requires-validation" data-toggle="validator" novalidate>
                                    @csrf
                                    <input type="hidden" name="user_type" id="user_type" value="user">
                                    <div class="input-group custom-input-group mb-3">
                                        <input type="text" class="form-control" placeholder="First Name"
                                            name="first_name" id="first_name" autofocus>
                                        <span class="input-group-text"><i class="ph ph-user"></i></span>
                                    </div>
                                    <div class="invalid-feedback text-danger" id="first_name_error">First Name field is
                                        required</div>
                                    <div class="input-group custom-input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Last Name" name="last_name"
                                            id="last_name" >
                                        <span class="input-group-text"><i class="ph ph-user"></i></span>
                                    </div>
                                    <div class="invalid-feedback text-danger" id="last_name_error">Last Name field is
                                        required</div>
                                    <div class="input-group custom-input-group mb-3">
                                        <input type="tel" class="form-control" placeholder="Contact Number"
                                            name="mobile" id="contact_number">
                                        <span class="input-group-text"><i class="ph ph-phone-call"></i></span>
                                    </div>
                                    <div class="invalid-feedback text-danger w-100" id="contact_number_error">Contact
                                        Number field is required</div>
                                        <div class="input-group custom-input-group mb-3 flex-column align-items-start">
                                        <div class="d-flex w-100">
                                            <input type="email" class="form-control" placeholder="E-mail ID" name="email" id="email">
                                            <span class="input-group-text"><i class="ph ph-envelope-simple"></i></span>
                                        </div>
                                        <div class="invalid-feedback" id="email-error"
                                            style="color: #dc3545; font-size: 1.1rem; font-weight: 500; display: none; text-align: left; width: 100%; margin-top: 4px;">
                                            Invalid email format
                                        </div>
                                    </div>

                                    <div class="input-group custom-input-group mb-3">
                                        <input type="password" class="form-control" placeholder="Enter password"
                                            id="password" name="password">
                                        <span class="input-group-text">
                                            <i class="ph ph-eye-slash" id="togglePassword"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback text-danger" id="password_error">Password field is
                                        required</div>
                                    <div class="input-group custom-input-group">
                                        <input type="password" class="form-control" placeholder="Enter Confirm password"
                                            id="confirm_password" name="confirm_password" >
                                        <span class="input-group-text">
                                            <i class="ph ph-eye-slash" id="toggleConfirmPassword"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback text-danger" id="confirm_password_error">Confirm
                                        Password field is required</div>
                                    <div class="d-flex my-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" required>
                                        </div>
                                        <p class="m-0 font-size-14 text-body">I agree to the <a
                                                class="text-decoration-underline" target="_blank"
                                                href="{{ route('pages', ['slug' => 'terms-conditions']) }}"> {{ __('frontend.term_condition') }}</a>
                                            and <a class="text-decoration-underline" target="_blank"
                                                href="{{ route('pages', ['slug' => 'privacy-policy']) }}"> {{ __('frontend.privacy_policy') }}</a></p>
                                    </div>
                                    <button type="submit" id="register-button" class="btn btn-secondary w-100"
                                        data-signup-text="{{ __('frontend.sign_up') }}">Sign Up</button>
                                    <div class="d-flex justify-content-center gap-1 mt-4">
                                        <span class="font-size-14 text-body">Already have an account?</span>
                                        <a href="{{ route('login-page') }}" class="text-secondary font-size-14 fw-bold">Sign In</a>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    <script>
        const loginUrl = "{{ route('user-login') }}";
        const homeUrl = "{{ route('frontend.index') }}";
        const rgitsterUrl = "{{ route('api.register') }}";
    </script>
    <script src="{{ asset('js/auth.min.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.querySelector("#contact_number");
            var iti = window.intlTelInput(input, {
                initialCountry: "in",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" // To handle number formatting
            });

            input.addEventListener("countrychange", function () {
                var fullPhoneNumber = iti.getNumber(); 
                document.getElementById('contact_number').value = fullPhoneNumber;
            });

            input.addEventListener("blur", function () {
                var fullPhoneNumber = iti.getNumber();
                document.getElementById('contact_number').value = fullPhoneNumber;
            });  
            
            // Email validation on blur
            document.querySelector('input[name="email"]').addEventListener('blur', function() {
            const email = this.value;
            const emailError = document.getElementById('email-error');
            const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;

            if (email && !emailPattern.test(email)) {
                this.classList.add('is-invalid');
                emailError.style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                emailError.style.display = 'none';
            }
            });
        });
    </script>
@endsection
