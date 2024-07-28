@extends('layouts.user_type.auth')

@section('content')
<div>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Perfil</h1>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <h2>{{$user->name}}</h2>
                            <h3> @if ($user->roles->isNotEmpty())
                                {{ $user->roles->first()->name }}
                                @else
                                No role assigned
                                @endif
                            </h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">descripción general</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar Perfil</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Opciones</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Cambiar contraseña</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                    <h3 class="card-title">Detalles del perfil</h3>

                                    <h5 class="card-title">informacion del perfil</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Nombre de usuario</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Correo Electrónico</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Departamento</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->departamento ? $user->departamento->nombre : 'No disponible' }}</div>
                                    </div>

                                    <h5 class="card-title">informacion Personal</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Nombre </div>
                                        <div class="col-lg-9 col-md-8">@isset($user->informacionPersonal->nombres) {{ $user->informacionPersonal->nombres }} @else No disponible @endisset</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Apellido</div>
                                        <div class="col-lg-9 col-md-8">@isset($user->informacionPersonal->apellidos) {{ $user->informacionPersonal->apellidos }} @else No disponible @endisset</div>
                                    </div>



                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Fecha de nacimiento </div>
                                        <div class="col-lg-9 col-md-8">@isset($user->informacionPersonal->fecha_nacimiento) {{ $user->informacionPersonal->fecha_nacimiento }} @else No disponible @endisset</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Genero</div>
                                        <div class="col-lg-9 col-md-8">@isset($user->informacionPersonal->genero) {{ $user->informacionPersonal->genero }} @else No disponible @endisset</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">DUI:</div>
                                        <div class="col-lg-9 col-md-8">@isset($user->informacionPersonal->dui) {{ $user->informacionPersonal->dui }} @else No disponible @endisset</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">TELEFONO:</div>
                                        <div class="col-lg-9 col-md-8">@isset($user->informacionPersonal->telefono) {{ $user->informacionPersonal->telefono }} @else No disponible @endisset</div>
                                    </div>

                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                    <!-- Profile Edit Form -->
                                    <form class="needs-validation " action="{{ route('user.one_update', $user->id) }}" method="POST" id="updateForm" novalidate>
                                        @csrf
                                        @method('PUT')

                                        <div class="row mb-3">
                                            <label for="name" class="col-md-4 col-lg-4 col-form-label">Usuario</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="name" type="text" class="form-control border-dark @error('name') is-invalid @enderror" id="name" required minlength="6" pattern="[a-zA-Z]+" value="{{ old('name', $user->name) }}">
                                                <div class="invalid-feedback">El nombre debe tener al menos 6 caracteres y solo puede contener letras.</div>
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="email" class="col-md-4 col-lg-4 col-form-label">Correo Electrónico</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="email" type="email" class="form-control border-dark @error('email') is-invalid @enderror" id="email" value="{{ old('email', $user->email) }}" required>
                                                <div class="invalid-feedback">Por favor, introduce un correo electrónico válido.</div>
                                                @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="nombres" class="col-md-4 col-lg-4 col-form-label">Nombres</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="nombres" type="text" class="form-control border-dark @error('nombres') is-invalid @enderror" id="nombres" value="{{ old('nombres', $user->informacionPersonal->nombres ?? '') }}" required minlength="6" pattern="[a-zA-Z]+" title="El nombre debe tener al menos 6 caracteres y solo letras.">
                                                <div class="invalid-feedback">El nombre debe tener al menos 6 caracteres y solo puede contener letras.</div>
                                                @error('nombres')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="apellidos" class="col-md-4 col-lg-4 col-form-label">Apellidos</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="apellidos" type="text" class="form-control border-dark @error('apellidos') is-invalid @enderror" id="apellidos" value="{{ old('apellidos', $user->informacionPersonal->apellidos ?? '') }}" required minlength="6" pattern="[a-zA-Z]+" title="El apellido debe tener al menos 6 caracteres y solo letras.">
                                                <div class="invalid-feedback">El apellido debe tener al menos 6 caracteres y solo puede contener letras.</div>
                                                @error('apellidos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fecha_nacimiento" class="col-md-4 col-lg-4 col-form-label">Fecha de Nacimiento</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="fecha_nacimiento" type="date" class="form-control border-dark @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $user->informacionPersonal->fecha_nacimiento ?? '') }}" required>
                                                <div class="invalid-feedback">Debes tener al menos 16 años.</div>
                                                @error('fecha_nacimiento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="genero" class="col-md-4 col-lg-4 col-form-label">Género</label>
                                            <div class="col-md-6 col-lg-6">
                                                <select name="genero" class="form-control border-dark @error('genero') is-invalid @enderror" id="genero" required>
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
                                        </div>

                                        <div class="row mb-3">
                                            <label for="dui" class="col-md-4 col-lg-4 col-form-label">DUI</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="dui" type="text" class="form-control border-dark @error('dui') is-invalid @enderror" id="dui" value="{{ old('dui', $user->informacionPersonal->dui ?? '') }}" required pattern="\d{8}-\d" title="El DUI debe tener el formato 00000000-0.">
                                                <div class="invalid-feedback">El DUI debe tener el formato 00000000-0.</div>
                                                @error('dui')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="telefono" class="col-md-4 col-lg-4 col-form-label">Teléfono</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="telefono" type="text" class="form-control border-dark @error('telefono') is-invalid @enderror" id="telefono" value="{{ old('telefono', $user->informacionPersonal->telefono ?? '') }}" required pattern="\d{4}-\d{4}" title="El teléfono debe tener el formato 0000-0000.">
                                                <div class="invalid-feedback">El teléfono debe tener el formato 0000-0000.</div>
                                                @error('telefono')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12 text-end">
                                                <button class="btn bg-gradient-dark mt-3 mb-0" type="submit" id="btn-actualizar-usuario-autentificado">Actualizar Usuario</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                                <div class="tab-pane fade pt-3" id="profile-settings">

                                    <!-- Settings Form -->
                                    <form>

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-4 col-form-label">Email Notifications</label>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="changesMade" checked>
                                                    <label class="form-check-label" for="changesMade">
                                                        Changes made to your account
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                                    <label class="form-check-label" for="newProducts">
                                                        Information on new products and services
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="proOffers">
                                                    <label class="form-check-label" for="proOffers">
                                                        Marketing and promo offers
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                                                    <label class="form-check-label" for="securityNotify">
                                                        Security alerts
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form><!-- End settings Form -->

                                </div>

                                <div class="tab-pane fade pt-3 " id="profile-change-password">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif


                                    <form method="POST" action="{{ route('password.update') }}" id="passwordUpdateForm">
                                        @csrf

                                        <div class="row mb-3">
                                            <label for="currentPassword" class="col-md-4 col-lg-4 col-form-label">Contraseña Actual</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="current_password" type="password" class="form-control" id="currentPassword" required>
                                                @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-4 col-form-label">Nueva Contraseña</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="new_password" type="password" class="form-control" id="newPassword" required minlength="8">
                                                @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="renewPassword" class="col-md-4 col-lg-4 col-form-label">Confirmar Nueva Contraseña</label>
                                            <div class="col-md-6 col-lg-6">
                                                <input name="new_password_confirmation" type="password" class="form-control" id="renewPassword" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12 text-end">
                                                <button class="btn bg-gradient-dark mt-3 mb-0" type="submit" id="btn-actualizar-contraseña">Actualizar Contraseña</button>
                                            </div>
                                        </div>
                                    </form>


                                </div>

                            </div><!-- End Bordered Tabs -->

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