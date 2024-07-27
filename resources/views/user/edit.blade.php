@extends('layouts.user_type.auth')

@section('content')
<div>
    <!-- Contenedor principal de la vista -->
    <div class="container-fluid">
        <!-- Encabezado de la página con una imagen de fondo -->
        <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('/assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-1 opacity-6"></span>
        </div>

        <!-- Tarjeta que contiene la información del usuario -->
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <!-- Columna para mostrar el nombre del usuario y su rol -->
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <!-- Nombre del usuario -->
                        <h5 class="mb-1">
                            {{$user->informacionPersonal->nombres}}
                        </h5>
                        <!-- Rol del usuario, si está asignado -->
                        <p class="mb-0 font-weight-bold text-sm">
                            @if ($user->roles->isNotEmpty())
                            {{ $user->roles->first()->name }}
                            @else
                            Sin rol asignado
                            @endif
                        </p>
                    </div>
                </div>
                <!-- Columna para los enlaces de navegación de pestañas -->
                <div class="col-lg-2 col-auto my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <!-- Elemento de la pestaña para el perfil -->
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="javascript:;" role="tab" aria-controls="overview" aria-selected="true">
                                    <!-- Icono de perfil -->
                                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                        <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Rounded-Icons" transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                                    <g id="box-3d-50" transform="translate(603.000000, 0.000000)">
                                                        <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                                                        <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                        <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <!-- Texto de la pestaña -->
                                    <span class="ms-1">{{ __('Perfil') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Contenedor para el formulario   -->
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3 text-center">
                <h4 class="mb-0">{{ __('INFORMACION DEL USUARIO') }}</h4>
            </div>
            <div class="card-body pt-4 p-3 d-flex justify-content-center">
                <!-- Formulario para editar los usuarios -->
                <form class="row g-3 text-dark text-center needs-validation" action="{{ route('user.update', $user->id) }}" method="POST" id="updateForm" novalidate>
                    @csrf
                    @method('PUT')
                    <!-- Campo para ingresar el nombre del usuario -->
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Usuario</label>
                        <input name="name" type="text" class="border-dark form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp" required minlength="6" pattern="[a-zA-Z]+" value="{{ old('name', $user->name) }}">
                        <div class="invalid-feedback">El nombre debe tener al menos 6 caracteres y solo puede contener letras.</div>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar el correo del usuario -->
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input name="email" type="email" class="border-dark form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" value="{{ old('email', $user->email) }}" required>
                        <div class="invalid-feedback">Por favor, introduce un correo electrónico válido.</div>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar al departamento al cual pertence el usuario-->
                    <div class="col-md-4 mb-3">
                        <label for="departamento_id">Departamento</label>
                        <select name="departamento_id" id="departamento_id" class="form-control @error('departamento_id') is-invalid @enderror" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" {{ $user->departamento_id == $departamento->id ? 'selected' : '' }}>
                                {{ $departamento->nombre }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor, selecciona un departamento.</div>
                        @error('departamento_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar  la contraseña-->
                    <div class="col-md-4 mb-3">
                        <label for="password" class="form-label">Contraseña (opcional)</label>
                        <input name="password" type="password" class="border-dark form-control @error('password') is-invalid @enderror" id="password" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$">
                        <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres, incluir letras mayúsculas y minúsculas, números y caracteres especiales, y no debe contener espacios en blanco.</div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar la contraseña y verificar que este sean igual al otro campo-->
                    <div class="col-md-4 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input name="password_confirmation" type="password" class="border-dark form-control" id="password_confirmation">
                        <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                    </div>
                    <!-- Campo para ingresar los apellidos del usuario-->
                    <div class="col-md-4 mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input name="apellidos" type="text" class="border-dark form-control @error('apellidos') is-invalid @enderror" id="apellidos" value="{{ old('apellidos', $user->informacionPersonal->apellidos ?? '') }}" required minlength="6" pattern="[a-zA-Z]+" title="El apellido debe tener al menos 6 caracteres y solo letras.">
                        <div class="invalid-feedback">El apellido debe tener al menos 6 caracteres y solo puede contener letras.</div>
                        @error('apellidos')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar Nombre del usuario-->
                    <div class="col-md-4 mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input name="nombres" type="text" class="border-dark form-control @error('nombres') is-invalid @enderror" id="nombres" value="{{ old('nombres', $user->informacionPersonal->nombres ?? '') }}" required minlength="6" pattern="[a-zA-Z]+" title="El nombre debe tener al menos 6 caracteres y solo letras.">
                        <div class="invalid-feedback">El nombre debe tener al menos 6 caracteres y solo puede contener letras.</div>
                        @error('nombres')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar fecha de nacimiento del usuario-->
                    <div class="col-md-4 mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" class="border-dark form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $user->informacionPersonal->fecha_nacimiento ?? '') }}" required>
                        <div class="invalid-feedback">Debes tener al menos 16 años.</div>
                        @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar Genero del usuario-->
                    <div class="col-md-4 mb-3">
                        <label for="genero" class="form-label">Género</label>
                        <select name="genero" class="border-dark form-control @error('genero') is-invalid @enderror" id="genero" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Masculino" {{ old('genero', $user->informacionPersonal->genero ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $user->informacionPersonal->genero ?? '') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero', $user->informacionPersonal->genero ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <div class="invalid-feedback">Por favor, selecciona un género.</div>
                        @error('genero')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar Documento unico de editificacion del usuario-->
                    <div class="col-md-4 mb-3">
                        <label for="dui" class="form-label">DUI</label>
                        <input name="dui" type="text" class="border-dark form-control @error('dui') is-invalid @enderror" id="dui" value="{{ old('dui', $user->informacionPersonal->dui ?? '') }}" required pattern="\d{8}-\d" title="El DUI debe tener el formato 00000000-0.">
                        <div class="invalid-feedback">El DUI debe tener el formato 00000000-0.</div>
                        @error('dui')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para ingresar numero telefonico del usuario-->
                    <div class="col-md-4 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input name="telefono" type="text" class="border-dark form-control @error('telefono') is-invalid @enderror" id="telefono" value="{{ old('telefono', $user->informacionPersonal->telefono ?? '') }}" required pattern="\d{4}-\d{4}" title="El teléfono debe tener el formato 0000-0000.">
                        <div class="invalid-feedback">El teléfono debe tener el formato 0000-0000.</div>
                        @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- grupo para hacer la asignacion de roles al usaurio -->
                    <div class="form-group">
                        <h3>Roles</h3>
                        <hr>
                        <div class="row">
                            @foreach ($roles as $index => $role)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input role-checkbox" type="radio" name="roles[]" value="{{ $role->id }}" id="role-{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            </div>
                            @if ($index % 2 == 1)
                        </div>
                        <div class="row">
                            @endif
                            @endforeach
                        </div>
                        <div class="invalid-feedback">Por favor, selecciona exactamente un rol.</div>
                    </div>
                    <!-- Boton para hacer los cambios al usuario -->
                    <div class="col-md-12 text-end">
                        <button class="btn bg-gradient-dark mt-3 mb-0" type="submit" id="actualizar_usuario">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Validaciones tipo Javascript para los campos del usuario -->
<script src="{{ asset('assets/js/Usuarios/UserEdit.js') }}"></script>
@endsection