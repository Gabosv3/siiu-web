@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Departamentos</h1>
    <a href="{{ route('departamentos.create') }}" class="btn btn-primary mb-3">Crear Departamento</a>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Usuarios</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Eliminados</button>

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3 ">
                <table id="example" class="table align-items-center mb-0 text-center">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th>
                            <th>NOMBRE</th>
                            <th class="w-15">ACCIONES</th>
                        </tr>
                    </thead>
                    @foreach ($departamentos as $departamento)
                    <tr>
                        <td>{{ $departamento->id }}</td>
                        <td>{{ $departamento->nombre }}</td>
                        <td>
                            <a href="{{ route('departamentos.show', $departamento->id) }}" title="Mostrar departamento" class="btn btn-cyan-800"><i class='bx bxs-show'></i></a>
                            <a href="{{ route('departamentos.edit', $departamento->id) }}" title="Editar departamento" class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                            <form action="{{ route('departamentos.destroy', $departamento->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar departamento" class="btn btn-red-800"><i class='bx bxs-trash'></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        </div>    
    </div>
</div>

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

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": '<i class="fas fa-angle-double-left"></i>',
                    "sLast": '<i class="fas fa-angle-double-right"></i>',
                    "sNext": '<i class="fas fa-angle-right"></i>',
                    "sPrevious": '<i class="fas fa-angle-left"></i>'

                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            },
            dom: '<"top"Bf>rt<"bottom"lip><"clear">',
        });
    });
</script>
@endsection