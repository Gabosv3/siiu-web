@extends('layouts.user_type.auth')

@section('content')
<h1>Departamentos</h1>
<a href="{{ route('departamentos.create') }}" class="btn bg-gradient-2 mb-3">Crear Departamento</a>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-departamentos-tab" data-bs-toggle="tab" data-bs-target="#nav-departamentos" type="button" role="tab" aria-controls="nav-departamentos" aria-selected="true">Departamentos</button>
        <button class="nav-link" id="nav-desactivados-tab" data-bs-toggle="tab" data-bs-target="#nav-desactivados" type="button" role="tab" aria-controls="nav-desactivados" aria-selected="false">Desactivados</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <!-- Pestaña de Departamentos Activos -->
    <div class="tab-pane fade show active" id="nav-departamentos" role="tabpanel" aria-labelledby="nav-departamentos-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <div class="table-responsive">
                <table id="Principal" class="table align-items-center mb-0 text-center" style="width:100%">
                    <thead class="align-middle bg-gradient-2">
                        <tr>
                            <th>#</th>
                            <th>NOMBRE</th>
                            <th>CÓDIGO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departamentos as $departamento)
                        <tr>
                            <td>{{ $departamento->id }}</td>
                            <td>{{ $departamento->nombre }}</td>
                            <td>{{ $departamento->codigo }}</td>

                            <td>
                                <a href="{{ route('departamentos.show', $departamento->id) }}" title="Mostrar departamento" class="btn btn-cyan-800"><i class='bx bxs-show'></i></a>
                                <a href="{{ route('departamentos.edit', $departamento->id) }}" title="Editar departamento" class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                                <form action="{{ route('departamentos.destroy', $departamento->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
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

    <!-- Pestaña de Departamentos Desactivados -->
    <div class="tab-pane fade" id="nav-desactivados" role="tabpanel" aria-labelledby="nav-desactivados-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Fecha Eliminación</th>
                        <th class="w-15">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departamentosDelets as $departamento)
                    <tr>
                        <td>{{ $departamento->id }}</td>
                        <td>{{ $departamento->codigo }}</td>
                        <td>{{ $departamento->nombre }}</td>
                        <td>{{ $departamento->deleted_at }}</td>
                        <td>
                            @can('user.restore')
                            <!-- Formulario para restaurar el usuario -->
                            <form action="{{ route('departamentos.restore', $departamento->id) }}" class="formulario-restaurar" method="POST">
                                @csrf
                                @method('PUT')
                                <button id="btn-restaurar-departamento" class="btn btn-cyan-800 mb-3" type="submit">Restaurar</button>
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

@if (session('status'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Departamento actualizado exitosamente.',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif

@endsection