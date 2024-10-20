@extends('layouts.user_type.auth')

@section('content')
<div class="container-md">
    <!-- Sección de información del usuario -->
    <div class="shadow-lg p-3 mb-5 rounded rounded-3">
        <!-- Encabezado de la sección con estilo -->
        <div class="bg-gradient-2 rounded rounded-3 p-2 m-2">
            <h4 class="text-white">USER INFORMATION</h4>
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
                    <th scope="row">EMAIL:</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <!-- Fila para mostrar el nombre del departamento al que pertenece el usuario -->
                <tr>
                    <th scope="row">DEPARTAMENTO:</th>
                    <td>{{ $user->departament ? $user->departament->name : 'No disponible' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Sección de información general del usuario -->
    <div class="shadow-lg p-3 mb-5 rounded rounded-3">
        <!-- Encabezado de la sección con estilo -->
        <div class="bg-gradient-2 rounded rounded-3 p-2 m-2">
            <h4 class="text-white">GENERAL INFORMATION</h4>
        </div>
        <!-- Tabla con información personal general del usuario -->
        <table class="table table-borderless">
            <tbody>
                <!-- Fila para mostrar los apellidos del usuario -->
                <tr>
                    <th scope="row">APELLIDOS:</th>
                    <td>@isset($user->personal_informations->last_names) {{ $user->personal_informations->last_names }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el nombre del usuario -->
                <tr>
                    <th scope="row">NOMBRES:</th>
                    <td>@isset($user->personal_informations->first_name) {{ $user->personal_informations->first_name }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar la fecha de nacimiento del usuario -->
                <tr>
                    <th scope="row">FECHA DE NACIMIENTO:</th>
                    <td>@isset($user->personal_informations->date_of_birth) {{ $user->personal_informations->date_of_birth }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el género del usuario -->
                <tr>
                    <th scope="row">GENERO:</th>
                    <td>@isset($user->personal_informations->gender) {{ $user->personal_informations->gender }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el DUI del usuario -->
                <tr>
                    <th scope="row">DUI:</th>
                    <td>@isset($user->personal_informations->dui) {{ $user->personal_informations->dui }} @else No disponible @endisset</td>
                </tr>
                <!-- Fila para mostrar el teléfono del usuario -->
                <tr>
                    <th scope="row">TELEFONO:</th>
                    <td>@isset($user->personal_informations->phone) {{ $user->personal_informations->phone }} @else No disponible @endisset</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
