@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Listado de Softwares</h1>

    <a href="{{ route('softwares.create') }}" class="btn btn-primary mb-3">Agregar Software</a>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <!-- Botones de navegación para las pestañas -->
            <button class="nav-link active" id="nav-categories-tab" data-bs-toggle="tab" data-bs-target="#nav-categories" type="button" role="tab" aria-controls="nav-categories" aria-selected="true">SOFTWARE</button>
            <button class="nav-link" id="nav-deactivated-tab" data-bs-toggle="tab" data-bs-target="#nav-deactivateds" type="button" role="tab" aria-controls="nav-deactivateds" aria-selected="false">DESACTIVADOS</button>
        </div>
    </nav>


    <div class="tab-content" id="nav-tabContent">
        <!-- Contenido de la pestaña de categorías -->
        <div class="tab-pane fade show active" id="nav-categories" role="tabpanel" aria-labelledby="nav-categories-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
                <table id="Principal" class="table align-items-center mb-0 text-center" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Versión</th>
                            <th>Fabricante</th>
                            <th>Licencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($softwares as $software)
                        <tr>
                            <td>{{ $software->id }}</td>
                            <td>{{ $software->software_name }}</td>
                            <td>{{ $software->version }}</td>
                            <td>{{ $software->manufacturer->name }}</td>
                            <td><a title="Ver Licencias" href="{{ route('licenses.index', ['software_id' => $software->id]) }}" class="btn btn-cyan-800"><i class="fa fa-key"></i></a></td>
                            <td>
                                <a href="{{ route('softwares.show', $software->id) }}" title="Ver Software" class="btn btn-cyan-800"><i class="bx bxs-show"></i></a>
                                <a title="Editar Software" href="{{ route('softwares.edit', $software->id) }}" class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                                <form action="{{ route('softwares.destroy', $software->id) }}" method="POST" style="display:inline-block;" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Eliminar Software" type="submit" class="btn btn-red-800"><i class="bx bxs-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Contenido de la pestaña de desactivados -->
        <div class="tab-pane fade" id="nav-deactivateds" role="tabpanel" aria-labelledby="nav-deactivated-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
                <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>FECHA ELIMINACION</th>
                            <th class="w-15">RESTORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($softwaresdeleted as $software)
                        <tr>
                            <td>{{ $software->id }}</td>
                            <td>{{ $software->software_name }}</td>
                            <td>{{ $software->deleted_at }}</td>
                            <td>
                                <form action="{{ route('softwares.restore', $software->id) }}" class="formulario-restaurar" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button id="btn-restore-software" class="btn btn-cyan-800 mb-3" type="submit">Restore</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@include('components.script-btn') <!-- Incluir scripts necesarios -->

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script> <!-- Cargar scripts de tablas -->

@if (session('success')) <!-- Mostrar mensaje de opción si hay un estado en la sesión -->
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}", // Muestra el mensaje de sesión
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@elseif (session('error'))
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('warning') }}", // Muestra el mensaje de sesión
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif


@endsection