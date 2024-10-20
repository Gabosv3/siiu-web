@extends('layouts.user_type.auth')

@section('content')
<h1>Departamentos/Secciones/Unidades</h1>

<a href="{{ route('departaments.create') }}" class="btn bg-gradient-2 mb-3">Crear Departamento</a>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-departamentos-tab" data-bs-toggle="tab" data-bs-target="#nav-departamentos" type="button" role="tab" aria-controls="nav-departamentos" aria-selected="true">Departamentos</button>
        <button class="nav-link" id="nav-desactivados-tab" data-bs-toggle="tab" data-bs-target="#nav-desactivados" type="button" role="tab" aria-controls="nav-desactivados" aria-selected="false">Desactivados</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <!-- departamentos activos  -->
    <div class="tab-pane fade show active" id="nav-departamentos" role="tabpanel" aria-labelledby="nav-departamentos-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <div class="table-responsive">
                <table id="Principal" class="table align-items-center mb-0 text-center" style="width:100%">
                    <thead class="align-middle bg-gradient-2">
                        <tr>
                            <th>#</th>
                            <th>NOMBRE</th>
                            <th>CODIGO</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->code }}</td>

                            <td>
                                <a href="{{ route('departaments.show', $department->id) }}" title="Ver departamentos" class="btn btn-cyan-800"><i class='bx bxs-show'></i></a>
                                <a href="{{ route('departaments.edit', $department->id) }}" title="Editar departamentos" class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                                <form action="{{ route('departaments.destroy', $department->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" title="Eliminar departamento" class="btn btn-red-800"><i class='bx bxs-trash'></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Departamentos desactivados -->
    <div class="tab-pane fade" id="nav-desactivados" role="tabpanel" aria-labelledby="nav-desactivados-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>CODIGO</th>
                        <th>NOMBRE</th>
                        <th>FECHA ELIMINACION</th>
                        <th class="w-15">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deletedDepartments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->code }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->deleted_at }}</td>
                        <td>
                            @can('user.restore')
                            <!-- Form to restore department -->
                            <form action="{{ route('departamentos.restore', $department->id) }}" class="formulario-restaurar" method="POST">
                                @csrf
                                @method('PUT')
                                <button id="btn-restore-department" class="btn btn-cyan-800 mb-3" type="submit">Restore</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('components.script-btn')

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>

@if (session('status')) <!-- Mostrar mensaje de éxito si hay un estado en la sesión -->
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('status') }}", // Muestra el mensaje de sesión
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif

@endsection
