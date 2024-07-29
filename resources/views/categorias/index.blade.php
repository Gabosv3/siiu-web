@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Categorías</h1>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary mb-3">Crear Categoría</a>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Usuarios</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Eliminados</button>

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3 ">
                <table id="Principal" class="table align-items-center mb-0 text-center">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th class="w-15">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($categorias as $key => $categoria)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->codigo }}</td>
                            <td>
                                <a href="{{ route('categorias.show', $categoria->id) }}" title="Mostrar Categoria" class="btn btn-cyan-800"><i class='bx bxs-show'></i></a>
                                <a href="{{ route('categorias.edit', $categoria->id) }}" title="Editar Categoria" class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline-block;" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar Categoria" class="btn btn-red-800"><i class='bx bxs-trash'></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3 ">
                <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100% ">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th>Fecha Eliminación</th>
                            <th class="w-15">Restaurar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoriasDelets as $key => $categoria)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->codigo }}</td>
                            <td>{{ $categoria->deleted_at }}</td>
                            <td>
                                @can('user.restore')
                                <!-- Formulario para restaurar el usuario -->
                                <form action="{{ route('categorias.restore', $categoria->id) }}" class="formulario-restaurar" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-cyan-800  mb-3" type="submit">Restaurar</button>
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
</div>

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