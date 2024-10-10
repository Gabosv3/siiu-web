@extends('layouts.user_type.auth')

@section('content')
<div class="container card d-flex justify-content-center align-items-center " style="min-height: 75vh;">
    
    <form action="{{ route('crear.tickets') }}" class="row g-3 w-60 justify-content-center align-items-center "  method="POST">
        @csrf
        <h2>Crear Ticket</h2>
        <div class="col-md-4 col-sm-12 mb-3">
            <label for="user_id" class="form-label">Usuario</label>
            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
        </div>

        
        <div class="col-md-4 col-sm-12 mb-3">
            <label for="user_id" class="form-label">Departamento</label>
            <input type="text" class="form-control" value="{{ auth()->user()->departament->name }}" readonly>
        </div>
        <div class="col-md-8 col-sm-12 mb-3">
            <label for="title" class="form-label">Título del Ticket</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="col-md-8 col-sm-12 mb-3">
            <label for="description" class="form-label">Descripción del problema</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        
        <div class="col-md-8 text-end">
            <button type="submit" class="btn btn-primary">Crear Ticket</button>
        </div>
        
    </form>
</div>
@endsection
