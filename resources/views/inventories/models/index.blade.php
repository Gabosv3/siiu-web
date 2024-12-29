@extends('layouts.user_type.auth')

@section('content')
<div class="container mb-3">
    <h2>Modelos</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('models.create') }}" class="btn bg-gradient-2">Crear Modelo</a>
    </div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
           <button class="nav-link active" id="nav-models-tab" data-bs-toggle="tab" data-bs-target="#nav-models" type="button" role="tab" aria-controls="nav-models" aria-selected="true">Modelos</button>
            <button class="nav-link" id="nav-desactivados-tab" data-bs-toggle="tab" data-bs-target="#nav-desactivados" type="button" role="tab" aria-controls="nav-desactivados" aria-selected="false">Desactivados</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-models" role="tabpanel" aria-labelledby="nav-models-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
                <table id="Principal" class="table align-items-center mb-0 text-center">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Modelo</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Descripción</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de creación</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($models as $key => $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->created_at }}</td>
                            <td>{{ $model->updated_at }}</td>
                            <td>
                                <a href="{{ route('models.show', $model->id) }}" title="Ver Modelo" class="btn btn-cyan-800"><i class="bx bxs-show"></i></a>
                                <a href="{{ route('models.edit', $model->id) }}" title="Editar modelo" class="btn btn-green-600"><i class="bx bxs-edit"></i></a>
                                <form action="{{ route('models.destroy', $model->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar modelo" class="btn btn-red-800"><i class="bx bxs-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-desactivados" role="tabpanel" aria-labelledby="nav-desactivados-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
                <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Modelo</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de eliminación</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedModels as $key => $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->deleted_at }}</td>
                            <td>
                                <form action="{{ route('models.restore', $model->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">Restaurar</button>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Modelos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>





@include('components.script-btn')

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>



@endsection