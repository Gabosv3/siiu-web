<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación de Dos Factores</title>
    <!-- Incluyendo Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col-md-8 ">
                <div class="card">
                    <div class="card-header">Autenticación de Dos Factores</div>
                    <div class="card-body">
                        <p>La autenticación de dos factores (2FA) refuerza la seguridad del acceso al requerir dos métodos (también llamados factores) para verificar tu identidad. La autenticación de dos factores protege contra phishing, ingeniería social y ataques de fuerza bruta de contraseñas, y asegura tus inicios de sesión contra atacantes que explotan credenciales débiles o robadas.</p>

                        <!-- Mostrar mensajes de error -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Formulario para ingresar el pin de Google Authenticator -->
                        <p>Ingresa el pin de la aplicación Google Authenticator:</p>
                        <form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
                            @csrf
                            <div class="form-group{{ $errors->has('one_time_password') ? ' has-error' : '' }}">
                                <label for="one_time_password" class="control-label">Contraseña de un solo uso</label>
                                <input id="one_time_password" name="one_time_password" class="form-control col-md-4" type="text" required />
                                @if ($errors->has('one_time_password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('one_time_password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button class="btn btn-primary mt-3" type="submit" id="btn-autentificar-2fa">Autenticar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>