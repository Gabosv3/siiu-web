@extends('layouts.user_type.auth')

@section('content')
<div class="container card d-flex justify-content-center align-items-center " style="min-height: 75vh;">

    <form action="{{ route('crear.tickets') }}" class="row g-3 w-60 justify-content-center align-items-center " method="POST">
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
        <div class="col-md-4 col-sm-12 mb-3">
            <label for="title_id">Titulo</label>
            <select name="title_id" id="title_id" class="form-control">
                @foreach ($titles as $title)
                <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach
            </select>
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

<script>
     $(document).ready(function() {
        function initializeSelect2(selector) {
            $(selector).select2({
                placeholder: "Seleccione una opción",
                theme: "bootstrap-5",
                width: '100%',
            });
        }

        initializeSelect2('#title_id');

    });
</script>

@if (session('success'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: "{{ session('success') }}",
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
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Error de Validación',
                html: `
                    <ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
@endif
@endsection