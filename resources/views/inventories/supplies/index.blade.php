@extends('layouts.user_type.auth')

@section('content')

    <h1>Listado de Insumos</h1>


    @if(request()->has('category_id') && request()->input('category_id') !== 'all')
        <a href="{{ route('supplies.create', ['category_id' => request()->input('category_id')]) }}" class="btn btn-primary">Agregar Insumo</a>
        @endif
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <!-- Botones de navegación para las pestañas -->
            <button class="nav-link active" id="nav-categories-tab" data-bs-toggle="tab" data-bs-target="#nav-categories" type="button" role="tab" aria-controls="nav-categories" aria-selected="true">INSUMOS</button>
            <button class="nav-link" id="nav-deactivated-tab" data-bs-toggle="tab" data-bs-target="#nav-deactivateds" type="button" role="tab" aria-controls="nav-deactivateds" aria-selected="false">DESACTIVADOS</button>
        </div>
    </nav>


    <div class="tab-content" id="nav-tabContent">
        <!-- Contenido de la pestaña de categorías -->
        <div class="tab-pane fade show active" id="nav-categories" role="tabpanel" aria-labelledby="nav-categories-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
                <table id="Principal" class="table align-items-center mb-0 text-center" style="width: 100%;">
                    <thead  class="table-primary text-center">
                        <tr>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplies as $supply)
                        <tr>
                            <td>{{ $supply->name }}</td>
                            <td>{{ $supply->category->name }}</td>
                            <td>{{ $supply->quantity }}</td>
                            <td>{{ $supply->unit }}</td>
                            <td>{{ ucfirst($supply->status) }}</td>
                            <td>
                                <a href="{{ route('supplies.show', $supply->id) }}" title="Ver Insumo" class="btn btn-cyan-800"><i class="bx bxs-show"></i></a>
                                <a title="Editar Insumo" href="{{ route('supplies.edit', $supply->id) }}" class="btn btn-green-600 "><i class='bx bxs-edit-alt'></i></a>
                                <form action="{{ route('supplies.destroy', $supply->id) }}" method="POST" style="display:inline-block;" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Eliminar Insumo" type="submit" class="btn btn-red-800"><i class="bx bxs-trash"></i></button>
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
                <table id="restaurar" class="table align-items-center mb-0 text-center " style="width:100%">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>FECHA ELIMINACION</th>
                            <th class="w-15">RESTORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deletedSupplies as $supply)
                        <tr>
                            <td>{{ $supply->id }}</td>
                            <td>{{ $supply->name }}</td>
                            <td>{{ $supply->deleted_at }}</td>
                            <td>
                                @can('user.restore')
                                <!-- Form to restore department -->
                                <form action="{{ route('supplies.restore', $supply->id) }}" class="formulario-restaurar" method="POST">
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
</div>
@endsection
