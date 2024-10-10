@extends('layouts.user_type.auth')
@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong>Autenticación de Dos Factores</strong></div>
                    <div class="card-body">
                        <p>La autenticación de dos factores (2FA) refuerza la seguridad del acceso al requerir dos métodos (también llamados factores) para verificar tu identidad. La autenticación de dos factores protege contra phishing, ingeniería social y ataques de fuerza bruta de contraseñas, y asegura tus inicios de sesión contra atacantes que explotan credenciales débiles o robadas.</p>

                        <!-- Mostrar mensajes de error y éxito -->
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Verificar si el usuario ya tiene configurada la seguridad de inicio de sesión -->
                        @if($data['user']->loginSecurity == null)
                            <!-- Formulario para generar la clave secreta de 2FA -->
                            <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="btn-generar-clave-2fa">
                                        Generar Clave Secreta para Habilitar 2FA
                                    </button>
                                </div>
                            </form>
                        @elseif(!$data['user']->loginSecurity->google2fa_enable)
                            <!-- Mostrar QR y formulario para habilitar 2FA -->
                            1. Escanea este código QR con tu aplicación Google Authenticator. Alternativamente, puedes usar el código: <code>{{ $data['secret'] }}</code><br/>
                            <img src="data:image/svg+xml;base64,{{ base64_encode($data['google2fa_url']) }}" alt="Código QR">
                            <br/><br/>
                            2. Ingresa el código de la aplicación Google Authenticator:<br/><br/>
                            <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                    <label for="secret" class="control-label">Código de Autenticador</label>
                                    <input id="secret" type="password" class="form-control col-md-4" name="secret" required>
                                    @if ($errors->has('verify-code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('verify-code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary" id="btn-habilitar-2fa">
                                    Habilitar 2FA
                                </button>
                            </form>
                        @elseif($data['user']->loginSecurity->google2fa_enable)
                            <!-- Mensaje cuando 2FA está habilitado -->
                            <div class="alert alert-success">
                                2FA está actualmente <strong>habilitado</strong> en tu cuenta.
                            </div>
                            <p>Si deseas desactivar la Autenticación de Dos Factores, confirma tu contraseña y haz clic en el botón Desactivar 2FA.</p>
                            <!-- Formulario para deshabilitar 2FA -->
                            <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                    <label for="change-password" class="control-label">Contraseña Actual</label>
                                    <input id="current-password" type="password" class="form-control col-md-4" name="current-password" required>
                                    @if ($errors->has('current-password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('current-password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary" id="btn-desactivar-2fa">Desactivar 2FA</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
