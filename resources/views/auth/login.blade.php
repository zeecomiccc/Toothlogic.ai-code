<x-auth-layout>
    <x-slot name="title">
        @lang('Login')
    </x-slot>

    <x-auth-card>
        <x-slot name="logo">
            <x-application-logo />
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Social Login -->
        <x-auth-social-login />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ $url ?? route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email"
                         type="email"
                         name="email"
                         :value="old('email')"
                         placeholder="Enter Email"
                         required
                         autofocus
                         pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                         title="Please enter a valid email address." />

                <!-- Custom error message for invalid email format -->
                <span id="emailError" class="text-danger" style="display: none;">Invalid email format</span>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password"
                         type="password"
                         name="password"
                         placeholder="Enter  Password"
                         required
                         minlength="8"
                         autocomplete="current-password"
                         title="Password must be between 8 and 12 characters." />

                <!-- Custom error message for invalid password length -->
                <span id="passwordError" class="text-danger" style="display: none;">Password must be between 8 and 12 characters</span>
            </div>

            <!-- Remember Me -->
            <div class="mt-4">
                <label for="remember_me" class="d-inline-flex">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <span class="ms-2">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Demo Accounts Section -->
            <div class="d-flex align-items-center justify-content-between mt-4">
               
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
               

                <x-button>
                    {{ __('Log in') }}
                </x-button>
            </div>

        </form>
        @if(setting('is_dummy_credentials'))    
        <div>
            <h6 class="text-center border-top py-3 mt-3">Demo Accounts</h6>
            <div class="parent">
                <select name="select" id="SelectUser" class="form-control selectpiker" onchange="getSelectedOption()">
                    <option value="">Select Role</option>
                    <option value="12345678,demo@kivicare.com">Demo Admin</option>
                    <option value="12345678,doctor@kivicare.com">Doctor</option>
                    @if(multiVendor() === "1")
                    <option value="12345678,vendor@kivicare.com">Clinic Admin</option>
                    @endif
                    <option value="12345678,receptionist@kivicare.com">Front Desk Officer</option>
                </select>
            </div>
        </div>
        @endif

        <x-slot name="extra">
            @if (Route::has('register'))
            <p class="text-center text-gray-600 mt-4">
                Do not have an account? <a href="{{ route('register') }}" class="underline hover:text-gray-900">Register</a>.
            </p>
            @endif
        </x-slot>
    </x-auth-card>

    <!-- Include necessary CSS and JavaScript for select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: inherit;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow,
        .select2-container--default .select2-selection--single .select2-selection__clear,
        .select2-container--classic .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        window.onload = function() {
            getSelectedOption();
        };
        $(document).ready(function() {
            $('#SelectUser').select2({
                placeholder: "Select Role",
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $('.select2-container').addClass('wide');
        });


        function getSelectedOption() {
            var selectElement = document.getElementById("SelectUser");
            var selectedOption = selectElement.options[selectElement.selectedIndex];

            if (selectedOption && selectedOption.value !== "") {
                var values = selectedOption.value.split(",");
                var password = values[0];
                var email = values[1];

                domId('email').value = email;
                domId('password').value = password;
            } else {
                domId('email').value = "";
                domId('password').value = "";
            }
        }

        function domId(name) {
            return document.getElementById(name);
        }

        // Custom email validation logic
        document.getElementById('email').addEventListener('input', function() {
            var emailField = document.getElementById('email');
            var emailError = document.getElementById('emailError');

            // Email validation pattern
            var emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;

            if (emailField.value && !emailPattern.test(emailField.value)) {
                emailError.style.display = 'block'; // Show error message
                emailField.classList.add('is-invalid'); // Add error styling if needed
            } else {
                emailError.style.display = 'none'; // Hide error message
                emailField.classList.remove('is-invalid'); // Remove error styling if fixed
            }
        });

        // Custom password validation logic for length
        document.getElementById('password').addEventListener('input', function() {
            var passwordField = document.getElementById('password');
            var passwordError = document.getElementById('passwordError');

            if (passwordField.value.length < 8 || passwordField.value.length > 12) {
                passwordError.style.display = 'block'; // Show error message
                passwordField.classList.add('is-invalid'); // Add error styling
            } else {
                passwordError.style.display = 'none'; // Hide error message
                passwordField.classList.remove('is-invalid'); // Remove error styling
            }
        });
    </script>
</x-auth-layout>
