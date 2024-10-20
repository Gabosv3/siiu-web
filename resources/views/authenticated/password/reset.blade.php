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
                                <button type="submit" class="btn btn-primary" id="btn-restablecer-contraseña">
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

<script></script>

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