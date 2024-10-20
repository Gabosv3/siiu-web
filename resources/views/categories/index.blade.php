@extends('layouts.user_type.auth')

@section('content')
<h1>Categories</h1> <!-- Título de la página -->
<a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">CREAR CATEGORIA</a> <!-- Botón para crear una nueva categoría -->

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <!-- Botones de navegación para las pestañas -->
        <button class="nav-link active" id="nav-categories-tab" data-bs-toggle="tab" data-bs-target="#nav-categories" type="button" role="tab" aria-controls="nav-categories" aria-selected="true">CATEGORIAS</button>
        <button class="nav-link" id="nav-deactivated-tab" data-bs-toggle="tab" data-bs-target="#nav-deactivateds" type="button" role="tab" aria-controls="nav-deactivateds" aria-selected="false">DESACTIVADOS</button>
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <!-- Contenido de la pestaña de categorías -->
    <div class="tab-pane fade show active" id="nav-categories" role="tabpanel" aria-labelledby="nav-categories-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="Principal" class="table align-items-center mb-0 text-center" style="width: 100%;">
                <thead class="table-primary text-center">
                    <tr>
                        <th>ID</th>
                        <th>IMAGEN</th>
                        <th>NOMBRE</th>
                        <th>CODIGO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterar sobre cada categoría y mostrar en la tabla -->
                    @foreach($categories as $key => $category)
                    <tr>
                        <td>{{ $key + 1 }}</td> <!-- Mostrar el índice -->
                        <td>
                            @if($category->image) <!-- Verificar si hay imagen -->
                                <img src="{{ asset($category->image) }}" alt="Category {{ $category->name }}" class="img-thumbnail" style="height: 72px;">
                                 @else
                                <span>Not available</span> <!-- Mensaje si no hay imagen -->
                            @endif
                        </td>
                        <td>{{ $category->name }}</td> <!-- Nombre de la categoría -->
                        <td>{{ $category->code }}</td> <!-- Código de la categoría -->
                        <td>
                            <!-- Botones de acción para mostrar, editar y eliminar -->
                            <a href="{{ route('categories.show', $category->id) }}" title="Ver Categoria" class="btn btn-cyan-800"><i class='bx bxs-show'></i></a>
                            <a href="{{ route('categories.edit', $category->id) }}" title="Editar Categoria" class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;" class="formulario-eliminar">
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

    <!-- Contenido de la pestaña de desactivados -->
    <div class="tab-pane fade" id="nav-deactivateds" role="tabpanel" aria-labelledby="nav-deactivated-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead class="table-primary text-center">
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>CODIGO</th>
                        <th>FECHA ELIMINACION</th>
                        <th class="w-15">RESTORE</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterar sobre cada categoría eliminada y mostrar en la tabla -->
                    @foreach($categoriesDeleted as $key => $category)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->code }}</td>
                        <td>{{ $category->deleted_at }}</td> <!-- Fecha de eliminación -->
                        <td>
                            @can('user.restore') <!-- Verificar permiso para restaurar -->
                            <!-- Formulario para restaurar la categoría -->
                            <form action="{{ route('categories.restore', $category->id) }}" class="formulario-restaurar" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-cyan-800 mb-3" type="submit">Restore</button>
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

@include('components.script-btn') <!-- Incluir scripts necesarios -->

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script> <!-- Cargar scripts de tablas -->

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
