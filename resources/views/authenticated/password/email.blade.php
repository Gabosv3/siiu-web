@extends('layouts.user_type.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Restablecer Contraseña') }}</div>

                <div class="card-body">
                    <!-- Mensaje de éxito, mostrado solo si hay un mensaje en la sesión -->
                    @if (session('status'))
                    <!-- Un <div> oculto con el mensaje de éxito para SweetAlert -->
                    <div id="success-message" data-status="{{ session('status') }}"></div>
                    @endif

                    <!-- Formulario para enviar el enlace de restablecimiento de contraseña -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Campo para el correo electrónico -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo Electrónico') }}</label>

                            <div class="col-md-6">
                                <!-- Campo de entrada para el correo electrónico -->
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                <!-- Mensaje de error si el campo de correo electrónico tiene errores -->
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                        @if ($message == 'passwords.user')
                                        {{ __('Correo Electrónico no encontrado') }}
                                        @elseif ($message == 'passwords.throttled')
                                        {{ __('Demasiados intentos de restablecimiento. Por favor, intente nuevamente en :seconds segundos.', ['seconds' => $seconds]) }}
                                        @else
                                        {{ $message }}
                                        @endif
                                    </strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Botón para enviar el formulario -->
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="btn-mail-restablecer-contraseña">
                                    {{ __('Enviar enlace de restablecimiento de contraseña') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Agregar SweetAlert -->
<!-- Incluye el script de SweetAlert desde un CDN -->
<script>
    // Ejecutar cuando el contenido de la página esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Busca el <div> con el mensaje de éxito
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            // Obtiene el mensaje de éxito del atributo data-status
            var status = successMessage.getAttribute('data-status');
            // Muestra el mensaje de éxito con SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Correo Enviado',
                confirmButtonText: 'Aceptar'
            });
        }
    });
</script>
@endsection