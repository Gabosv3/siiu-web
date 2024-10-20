@extends('layouts.user_type.auth')

@section('content')
    <div>
        <main id="main" class="main">
            <div class="pagetitle">
                <h1>Perfil</h1>
            </div>
            <section class="section profile">
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Tarjeta de perfil -->
                        <div class="card">
                            <!-- Cuerpo de la tarjeta -->
                            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                                <!-- Nombre del usuario -->
                                <h2>{{ $user->name }}</h2>
                                <!-- Título que muestra el rol del usuario -->
                                <h3>
                                    <!-- Verificar si el usuario tiene roles asignados -->
                                    @if ($user->roles->isNotEmpty())
                                        <!-- Mostrar el primer rol del usuario -->
                                        {{ $user->roles->first()->name }}
                                    @else
                                        <!-- Mostrar mensaje si no hay roles asignados -->
                                        No role assigned
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body pt-3">
                                <!-- Pestañas con bordes -->
                                <ul class="nav nav-tabs nav-tabs-bordered">
                                    <!-- Elemento de la pestaña 1: Descripción general -->
                                    <li class="nav-item">
                                        <!-- Botón para activar la pestaña de descripción general -->
                                        <button class="nav-link active" data-bs-toggle="tab"
                                            data-bs-target="#profile-overview" id="btn-nav-general">
                                            General
                                        </button>
                                    </li>
                                    <!-- Elemento de la pestaña 2: Editar Perfil -->
                                    <li class="nav-item">
                                        <!-- Botón para activar la pestaña de edición de perfil -->
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit"
                                            id="btn-nav-editar-Perfil">
                                            Editar Perfil
                                        </button>
                                    </li>
                                    <!-- Elemento de la pestaña 3: Opciones -->
                                    <li class="nav-item">
                                        <!-- Botón para activar la pestaña de opciones -->
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings"
                                            id="btn-nav-opciones">
                                            Opciones
                                        </button>
                                    </li>
                                    <!-- Elemento de la pestaña 4: Cambiar contraseña -->
                                    <li class="nav-item">
                                        <!-- Botón para activar la pestaña de cambio de contraseña -->
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#profile-change-password" id="btn-nav-contraseña">
                                            Contraseña
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-2">
                                    <!-- Contenido de la pestaña "Descripción General del Perfil" -->
                                    <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                        <!-- Título de la sección -->
                                        <h3 class="card-title">Detalles del perfil</h3>

                                        <!-- Subtítulo de la sección -->
                                        <h5 class="card-title mt-3">Información del perfil</h5>

                                        <!-- Fila: Nombre de usuario -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Nombre de usuario</div>
                                            <!-- Valor del nombre de usuario -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">{{ $user->name }}</div>
                                        </div>

                                        <!-- Fila: Correo Electrónico -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Correo Electrónico</div>
                                            <!-- Valor del correo electrónico -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">{{ $user->email }}</div>
                                        </div>

                                        <!-- Fila: Departamento -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Departamento</div>
                                            <!-- Valor del departamento (si está disponible) -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">
                                                {{ $user->departamento ? $user->departamento->nombre : 'No disponible' }}
                                            </div>
                                        </div>

                                        <!-- Subtítulo de la sección -->
                                        <h5 class="card-title mt-3">Información Personal</h5>

                                        <!-- Fila: Nombre -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Nombre</div>
                                            <!-- Valor del nombre personal (si está disponible) -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">
                                                @isset($user->informacionPersonal->nombres)
                                                    {{ $user->informacionPersonal->nombres }}
                                                @else
                                                    No disponible
                                                @endisset
                                            </div>
                                        </div>
                                        <!-- Fila: Apellido -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Apellido</div>
                                            <!-- Valor del apellido personal (si está disponible) -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">
                                                @isset($user->informacionPersonal->apellidos)
                                                    {{ $user->informacionPersonal->apellidos }}
                                                @else
                                                    No disponible
                                                @endisset
                                            </div>
                                        </div>
                                        <!-- Fila: Fecha de nacimiento -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Fecha de nacimiento</div>
                                            <!-- Valor de la fecha de nacimiento (si está disponible) -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">
                                                @isset($user->informacionPersonal->fecha_nacimiento)
                                                    {{ $user->informacionPersonal->fecha_nacimiento }}
                                                @else
                                                    No disponible
                                                @endisset
                                            </div>
                                        </div>
                                        <!-- Fila: Género -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Género</div>
                                            <!-- Valor del género (si está disponible) -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">
                                                @isset($user->informacionPersonal->genero)
                                                    {{ $user->informacionPersonal->genero }}
                                                @else
                                                    No disponible
                                                @endisset
                                            </div>
                                        </div>
                                        <!-- Fila: DUI -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">DUI</div>
                                            <!-- Valor del DUI (si está disponible) -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">
                                                @isset($user->informacionPersonal->dui)
                                                    {{ $user->informacionPersonal->dui }}
                                                @else
                                                    No disponible
                                                @endisset
                                            </div>
                                        </div>
                                        <!-- Fila: Teléfono -->
                                        <div class="row">
                                            <!-- Etiqueta -->
                                            <div class="col-lg-3 col-md-4  mt-3">Teléfono</div>
                                            <!-- Valor del teléfono (si está disponible) -->
                                            <div class="col-lg-9 col-md-8 mt-3 text-dark">
                                                @isset($user->informacionPersonal->telefono)
                                                    {{ $user->informacionPersonal->telefono }}
                                                @else
                                                    No disponible
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Contenido de la pestaña "Editar Perfil" -->
                                    <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                        <!-- Formulario para editar el perfil -->
                                        <form class="needs-validation" action="{{ route('user.one_update', $user->id) }}"
                                            method="POST" id="updateForm" novalidate>
                                            @csrf
                                            @method('PUT')
                                            <!-- Fila: Usuario -->
                                            <div class="row mb-3">
                                                <label for="name"
                                                    class="col-md-4 col-lg-4 col-form-label">Usuario</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <input name="name" type="text"
                                                        class="form-control border-dark @error('name') is-invalid @enderror"
                                                        id="name" required minlength="6" pattern="[a-zA-Z]+"
                                                        value="{{ old('name', $user->name) }}">
                                                    <div class="invalid-feedback">El nombre debe tener al menos 6 caracteres
                                                        y solo puede contener letras.</div>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Fila: Correo Electrónico -->
                                            <div class="row mb-3">
                                                <label for="email" class="col-md-4 col-lg-4 col-form-label">Correo
                                                    Electrónico</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <input name="email" type="email"
                                                        class="form-control border-dark @error('email') is-invalid @enderror"
                                                        id="email" value="{{ old('email', $user->email) }}" required>
                                                    <div class="invalid-feedback">Por favor, introduce un correo electrónico
                                                        válido.</div>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Fila: Nombres -->
                                            <div class="row mb-3">
                                                <label for="nombres"
                                                    class="col-md-4 col-lg-4 col-form-label">Nombres</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <input name="nombres" type="text"
                                                        class="form-control border-dark @error('nombres') is-invalid @enderror"
                                                        id="nombres"
                                                        value="{{ old('nombres', $user->informacionPersonal->nombres ?? '') }}"
                                                        required minlength="6" pattern="[a-zA-Z]+"
                                                        title="El nombre debe tener al menos 6 caracteres y solo letras.">
                                                    <div class="invalid-feedback">El nombre debe tener al menos 6
                                                        caracteres y solo puede contener letras.</div>
                                                    @error('nombres')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Fila: Apellidos -->
                                            <div class="row mb-3">
                                                <label for="apellidos"
                                                    class="col-md-4 col-lg-4 col-form-label">Apellidos</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <input name="apellidos" type="text"
                                                        class="form-control border-dark @error('apellidos') is-invalid @enderror"
                                                        id="apellidos"
                                                        value="{{ old('apellidos', $user->informacionPersonal->apellidos ?? '') }}"
                                                        required minlength="6" pattern="[a-zA-Z]+"
                                                        title="El apellido debe tener al menos 6 caracteres y solo letras.">
                                                    <div class="invalid-feedback">El apellido debe tener al menos 6
                                                        caracteres y solo puede contener letras.</div>
                                                    @error('apellidos')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Fila: Fecha de Nacimiento -->
                                            <div class="row mb-3">
                                                <label for="fecha_nacimiento"
                                                    class="col-md-4 col-lg-4 col-form-label">Fecha de Nacimiento</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <input name="fecha_nacimiento" type="date"
                                                        class="form-control border-dark @error('fecha_nacimiento') is-invalid @enderror"
                                                        id="fecha_nacimiento"
                                                        value="{{ old('fecha_nacimiento', $user->informacionPersonal->fecha_nacimiento ?? '') }}"
                                                        required>
                                                    <div class="invalid-feedback">Debes tener al menos 16 años.</div>
                                                    @error('fecha_nacimiento')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Fila: Género -->
                                            <div class="row mb-3">
                                                <label for="genero"
                                                    class="col-md-4 col-lg-4 col-form-label">Género</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <select name="genero"
                                                        class="form-control border-dark @error('genero') is-invalid @enderror"
                                                        id="genero" required>
                                                        <option value="">Seleccione una opción</option>
                                                        <option value="Masculino"
                                                            {{ old('genero', $user->informacionPersonal->genero ?? '') == 'Masculino' ? 'selected' : '' }}>
                                                            Masculino</option>
                                                        <option value="Femenino"
                                                            {{ old('genero', $user->informacionPersonal->genero ?? '') == 'Femenino' ? 'selected' : '' }}>
                                                            Femenino</option>
                                                        <option value="Otro"
                                                            {{ old('genero', $user->informacionPersonal->genero ?? '') == 'Otro' ? 'selected' : '' }}>
                                                            Otro</option>
                                                    </select>
                                                    <div class="invalid-feedback">Por favor, selecciona un género.</div>
                                                    @error('genero')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Fila: DUI -->
                                            <div class="row mb-3">
                                                <label for="dui" class="col-md-4 col-lg-4 col-form-label">DUI</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <input name="dui" type="text"
                                                        class="form-control border-dark @error('dui') is-invalid @enderror"
                                                        id="dui"
                                                        value="{{ old('dui', $user->informacionPersonal->dui ?? '') }}"
                                                        required pattern="\d{8}-\d"
                                                        title="El DUI debe tener el formato 00000000-0.">
                                                    <div class="invalid-feedback">El DUI debe tener el formato 00000000-0.
                                                    </div>
                                                    @error('dui')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Fila: Teléfono -->
                                            <div class="row mb-3">
                                                <label for="telefono"
                                                    class="col-md-4 col-lg-4 col-form-label">Teléfono</label>
                                                <div class="col-md-6 col-lg-6">
                                                    <input name="telefono" type="text"
                                                        class="form-control border-dark @error('telefono') is-invalid @enderror"
                                                        id="telefono"
                                                        value="{{ old('telefono', $user->informacionPersonal->telefono ?? '') }}"
                                                        required pattern="\d{4}-\d{4}"
                                                        title="El teléfono debe tener el formato 0000-0000.">
                                                    <div class="invalid-feedback">El teléfono debe tener el formato
                                                        0000-0000.</div>
                                                    @error('telefono')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Botón de actualización -->
                                            <div class="row mb-3">
                                                <div class="col-md-12 text-end">
                                                    <button class="btn bg-gradient-dark mt-3 mb-0" type="submit"
                                                        id="btn-actualizar-usuario-autentificado">Actualizar
                                                        Usuario</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Contenedor de la pestaña "Opciones" -->
                                    <div class="tab-pane fade pt-3" id="profile-settings">
                                        <!-- Verificación de la configuración de 2FA -->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>2FA</h4>
                                            </div>
                                            @if (isset($user->loginSecurity) && $user->loginSecurity->google2fa_enable)
                                                <div class="col-md-6">
                                                    <span class="badge bg-success">Activado</span>
                                                    <!-- Indicador de 2FA activado -->
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    <span class="badge bg-danger">Desactivado</span>
                                                    <!-- Indicador de 2FA desactivado -->
                                                </div>
                                            @endif
                                        </div>    
                                            <!-- Enlace para configurar 2FA -->
                                            <a class="btn bg-gradient-dark mt-3 mb-0"
                                                href="{{ route('show2FASettings') }}">
                                                <span class="ms-1">{{ __('Configurar 2FA') }}</span>
                                            </a>
                                            <!-- Verificación de correo electrónico -->
                                            @if (Auth::user()->hasVerifiedEmail())
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <h4>Correo Electrónico</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span class="badge bg-success">Verificado</span>
                                                        <!-- Indicador de correo verificado -->
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-danger">No Verificado</span>
                                                <!-- Indicador de correo no verificado -->
                                            @endif
                                        </div>
                                        <!-- Contenedor de la pestaña "Cambiar contraseña" -->
                                        <div class="tab-pane fade pt-3 " id="profile-change-password">
                                            <!-- Mostrar mensajes de error si existen -->
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <!-- Formulario para actualizar la contraseña -->
                                            <form method="POST" action="{{ route('password.update') }}"
                                                id="passwordUpdateForm">
                                                @csrf
                                                <!-- Campo para la contraseña actual -->
                                                <div class="row mb-3">
                                                    <label for="currentPassword"
                                                        class="col-md-4 col-lg-4 col-form-label">Contraseña Actual</label>
                                                    <div class="col-md-6 col-lg-6">
                                                        <input name="current_password" type="password"
                                                            class="form-control" id="currentPassword" required>
                                                        @error('current_password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Campo para la nueva contraseña -->
                                                <div class="row mb-3">
                                                    <label for="newPassword"
                                                        class="col-md-4 col-lg-4 col-form-label">Nueva
                                                        Contraseña</label>
                                                    <div class="col-md-6 col-lg-6">
                                                        <input name="new_password" type="password" class="form-control"
                                                            id="newPassword" required minlength="8">
                                                        @error('new_password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Campo para confirmar la nueva contraseña -->
                                                <div class="row mb-3">
                                                    <label for="renewPassword"
                                                        class="col-md-4 col-lg-4 col-form-label">Confirmar
                                                        Nueva Contraseña</label>
                                                    <div class="col-md-6 col-lg-6">
                                                        <input name="new_password_confirmation" type="password"
                                                            class="form-control" id="renewPassword" required>
                                                    </div>
                                                </div>
                                                <!-- Botón para actualizar la contraseña -->
                                                <div class="row mb-3">
                                                    <div class="col-md-12 text-end">
                                                        <button class="btn bg-gradient-dark mt-3 mb-0" type="submit"
                                                            id="btn-actualizar-contraseña">Actualizar Contraseña</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </main><!-- End #main -->
        <script src="{{ asset('assets/js/Usuarios/UserOneEdit.js') }}"></script>
        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    var icon = 'success';
                    var title = 'Éxito';
                    var text = "{{ session('status') }}"

                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: text
                    });
                });
            </script>
        @elseif($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var icon = 'error';
                    var title = 'Error';
                    var text = 'No se actualizó correctamente el usuario';

                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: text
                    });
                });
            </script>
        @endif


    @endsection
