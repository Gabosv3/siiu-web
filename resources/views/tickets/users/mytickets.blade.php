@extends('layouts.user_type.auth')

@section('content')

<div class="container">
<form method="GET" action="{{ route('Mytickets') }}" class="mb-4">
    <div class="row align-items-center">
        <!-- Filtro de estado -->
        <div class="col-md-3 mb-3">
            <label for="status" class="form-label d-flex align-items-center">
                <i class="fas fa-toggle-on me-2"></i> Filtro por Estado
            </label>
            <select name="status" class="form-select" id="status" onchange="this.form.submit()">
                <option value="">Todos</option> <!-- Opción para mostrar todos los tickets -->
                <option value="abierto" {{ request()->get('status') == 'abierto' ? 'selected' : '' }}>Abierto</option>
                <option value="en proceso" {{ request()->get('status') == 'en proceso' ? 'selected' : '' }}>En Proceso</option>
                <option value="resuelto" {{ request()->get('status') == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                <option value="cerrado" {{ request()->get('status') == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>

        <!-- Buscador -->
        <div class="col-md-6 mb-3">
            <label for="search" class="form-label d-flex align-items-center">
                <i class="fas fa-search me-2"></i> Buscar descripción
            </label>
            <input type="text" name="search" class="form-control" id="search" placeholder="Buscar..." value="{{ request()->get('search') }}">
        </div>

        <!-- Registros por página -->
        <div class="col-md-3 mb-3">
            <label for="perPage" class="form-label d-flex align-items-center">
                <i class="fas fa-list me-2"></i> Registros por página
            </label>
            <select name="perPage" class="form-select" id="perPage" onchange="this.form.submit()">
                <option value="10" {{ request()->get('perPage') == '10' ? 'selected' : '' }}>10 registros</option>
                <option value="20" {{ request()->get('perPage') == '20' ? 'selected' : '' }}>20 registros</option>
                <option value="50" {{ request()->get('perPage') == '50' ? 'selected' : '' }}>50 registros</option>
                <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>Todos</option>
            </select>
        </div>

    </div>
</form>

<div class="table-responsive shadow-lg p-3 mb-5 bg-body rounded rounded-3">
    <table id="Principal" class="table align-items-center mb-0 text-center" style="width:100%">
        <thead class="align-middle bg-gradient-2">
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->title->name }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->created_at }}</td>
                <td>
                    <a href="{{ route('Mytickets.show', $ticket->id) }}" class="btn btn-cyan-800 mb-3" title="Ver ticket">
                        <i class="bx bxs-show"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="pagination-container">
    @if($tickets instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $tickets->appends(request()->query())->links('pagination::bootstrap-4') }}
    @endif
</div>
</div>

@include('components.script-btn') <!-- Incluir scripts necesarios -->
<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>

@endsection
