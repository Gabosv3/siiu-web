@extends('layouts.user_type.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Restablecer Contraseña') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update.foremail') }}" id="resetPasswordForm">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">



                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Nueva Contraseña') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div id="passwordFeedback">
                                    <div id="lengthFeedback">Debe tener al menos 8 caracteres. <i id="lengthIcon" class="fas fa-times" style="color: red;"></i></div>
                                    <div id="lowercaseFeedback">Debe contener al menos una letra minúscula. <i id="lowercaseIcon" class="fas fa-times" style="color: red;"></i></div>
                                    <div id="uppercaseFeedback">Debe contener al menos una letra mayúscula. <i id="uppercaseIcon" class="fas fa-times" style="color: red;"></i></div>
                                    <div id="numberFeedback">Debe contener al menos un número. <i id="numberIcon" class="fas fa-times" style="color: red;"></i></div>
                                    <div id="symbolFeedback">Debe contener al menos un símbolo (@$!%*?&). <i id="symbolIcon" class="fas fa-times" style="color: red;"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Nueva Contraseña') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                <div id="confirmPasswordFeedback"></div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Restablecer Contraseña') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var passwordInput = document.getElementById('password');
        var confirmPasswordInput = document.getElementById('password-confirm');
        var lengthFeedback = document.getElementById('lengthFeedback');
        var lowercaseFeedback = document.getElementById('lowercaseFeedback');
        var uppercaseFeedback = document.getElementById('uppercaseFeedback');
        var numberFeedback = document.getElementById('numberFeedback');
        var symbolFeedback = document.getElementById('symbolFeedback');
        var confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');
        var lengthIcon = document.getElementById('lengthIcon');
        var lowercaseIcon = document.getElementById('lowercaseIcon');
        var uppercaseIcon = document.getElementById('uppercaseIcon');
        var numberIcon = document.getElementById('numberIcon');
        var symbolIcon = document.getElementById('symbolIcon');

        function validatePassword() {
            var password = passwordInput.value;

            // Check length
            if (password.length >= 8) {
                lengthFeedback.innerHTML = 'Debe tener al menos 8 caracteres. <i class="fas fa-check" style="color: green;"></i>';
                lengthIcon.classList.remove('fa-times');
                lengthIcon.classList.add('fa-check');
            } else {
                lengthFeedback.innerHTML = 'Debe tener al menos 8 caracteres. <i class="fas fa-times" style="color: red;"></i>';
                lengthIcon.classList.remove('fa-check');
                lengthIcon.classList.add('fa-times');
            }

            // Check lowercase
            if (/[a-z]/.test(password)) {
                lowercaseFeedback.innerHTML = 'Debe contener al menos una letra minúscula. <i class="fas fa-check" style="color: green;"></i>';
                lowercaseIcon.classList.remove('fa-times');
                lowercaseIcon.classList.add('fa-check');
            } else {
                lowercaseFeedback.innerHTML = 'Debe contener al menos una letra minúscula. <i class="fas fa-times" style="color: red;"></i>';
                lowercaseIcon.classList.remove('fa-check');
                lowercaseIcon.classList.add('fa-times');
            }

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                uppercaseFeedback.innerHTML = 'Debe contener al menos una letra mayúscula. <i class="fas fa-check" style="color: green;"></i>';
                uppercaseIcon.classList.remove('fa-times');
                uppercaseIcon.classList.add('fa-check');
            } else {
                uppercaseFeedback.innerHTML = 'Debe contener al menos una letra mayúscula. <i class="fas fa-times" style="color: red;"></i>';
                uppercaseIcon.classList.remove('fa-check');
                uppercaseIcon.classList.add('fa-times');
            }

            // Check number
            if (/\d/.test(password)) {
                numberFeedback.innerHTML = 'Debe contener al menos un número. <i class="fas fa-check" style="color: green;"></i>';
                numberIcon.classList.remove('fa-times');
                numberIcon.classList.add('fa-check');
            } else {
                numberFeedback.innerHTML = 'Debe contener al menos un número. <i class="fas fa-times" style="color: red;"></i>';
                numberIcon.classList.remove('fa-check');
                numberIcon.classList.add('fa-times');
            }

            // Check symbol
            if (/[@$!%*?&]/.test(password)) {
                symbolFeedback.innerHTML = 'Debe contener al menos un símbolo (@$!%*?&). <i class="fas fa-check" style="color: green;"></i>';
                symbolIcon.classList.remove('fa-times');
                symbolIcon.classList.add('fa-check');
            } else {
                symbolFeedback.innerHTML = 'Debe contener al menos un símbolo (@$!%*?&). <i class="fas fa-times" style="color: red;"></i>';
                symbolIcon.classList.remove('fa-check');
                symbolIcon.classList.add('fa-times');
            }
        }

        function validateConfirmPassword() {
            var password = passwordInput.value;
            var confirmPassword = confirmPasswordInput.value;

            if (password !== confirmPassword) {
                confirmPasswordFeedback.innerHTML = 'Las contraseñas no coinciden. <i class="fas fa-times" style="color: red;"></i>';
            } else {
                confirmPasswordFeedback.innerHTML = 'Las contraseñas coinciden. <i class="fas fa-check" style="color: green;"></i>';
            }
        }

        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validateConfirmPassword);

        var form = document.getElementById('resetPasswordForm');
        form.addEventListener('submit', function(event) {
            validatePassword();
            validateConfirmPassword();

            // Prevent form submission if there are validation errors
            if (document.querySelectorAll('#passwordFeedback .fa-times').length > 0) {
                event.preventDefault();
            }
        });
    });
</script>

@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '@foreach ($errors->all() as $error) {{ $error }} @endforeach'
        });
    });
</script>

@endif

@endsection