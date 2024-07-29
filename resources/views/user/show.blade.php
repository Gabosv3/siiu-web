@extends('layouts.user_type.auth')

@section('content')
<div class="container-md">
    <!-- Sección de información del usuario -->
    <div class="shadow-lg p-3 mb-5 rounded rounded-3">
        <!-- Encabezado de la sección con estilo -->
        <div class="bg-primary rounded rounded-3 p-2 m-2">
            <h4 class="text-white">INFORMACION USUARIO</h4>
        </div>
        <!-- Tabla con información básica del usuario -->
        <table class="table table-borderless">
            <tbody>
                <!-- Fila para mostrar el nombre del usuario -->
                <tr>
                    <th scope="row">USUARIO:</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <!-- Fila para mostrar el correo electrónico del usuario -->
                <tr>
                    <th scope="row">CORREO:</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <!-- Fila para mostrar el nombre del departamento al que pertenece el usuario -->
                <tr>
                    <th scope="row">Departamento:</th>
                    <td>{{ $user->departamento ? $user->departamento->nombre : 'No disponible' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Sección de información general del usuario -->
    <div class="shadow-lg p-3 mb-5 rounded rounded-3">
        <!-- Encabezado de la sección con estilo -->
        <div class="bg-primary rounded rounded-3 p-2 m-2">
            <h4 class="text-white">INFORMACION GENERAL</h4>
        </div>
        <!-- Tabla con información personal general del usuario -->
        <table class="table table-borderless">
            <tbody>
                <!-- Fila para mostrar los apellidos del usuario -->
                <tr>
                    <th scope="row">APELLIDOS:</th>
                    <td>@isset($user->informacionPersonal->apellidos) {{ $user->informacionPersonal->apellidos }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el nombre del usuario -->
                <tr>
                    <th scope="row">NOMBRE:</th>
                    <td>@isset($user->informacionPersonal->nombres) {{ $user->informacionPersonal->nombres }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar la fecha de nacimiento del usuario -->
                <tr>
                    <th scope="row">FECHA NACIMIENTO:</th>
                    <td>@isset($user->informacionPersonal->fecha_nacimiento) {{ $user->informacionPersonal->fecha_nacimiento }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el género del usuario -->
                <tr>
                    <th scope="row">GENERO:</th>
                    <td>@isset($user->informacionPersonal->genero) {{ $user->informacionPersonal->genero }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el DUI del usuario -->
                <tr>
                    <th scope="row">DUI:</th>
                    <td>@isset($user->informacionPersonal->dui) {{ $user->informacionPersonal->dui }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el teléfono del usuario -->
                <tr>
                    <th scope="row">TELEFONO:</th>
                    <td>@isset($user->informacionPersonal->telefono) {{ $user->informacionPersonal->telefono }} @else No disponible @endisset</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection