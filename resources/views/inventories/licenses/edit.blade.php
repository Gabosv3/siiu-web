@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Editar Licencia</h1>
    <form action="{{ route('licenses.update', $license->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="software_id">Software</label>
            <select name="software_id" id="software_id" class="form-control">
                @foreach($softwares as $soft)
                <option value="{{ $soft->id }}" {{ $license->software_id == $soft->id ? 'selected' : '' }}>
                    {{ $soft->software_name }} ({{ $soft->version }})
                </option>
                @endforeach
            </select>
            @error('software_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="license_key">Clave de Licencia</label>
            <input type="text" name="license_key" id="license_key" class="form-control" value="{{ $license->license_key }}">
            @error('license_key')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="expiration_date">Fecha de Expiración</label>
            <input type="date" name="expiration_date" id="expiration_date" class="form-control" value="{{ $license->expiration_date }}">
            @error('expiration_date')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('licenses.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    $(document).ready(function() {

        // Inicializar Select2
        function initializeSelect2(selector) {
            $(selector).select2({
                placeholder: "Seleccione una opción",
                theme: "bootstrap-5",
                width: '100%',
            });
        }

        initializeSelect2('#software_id');
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
@endif
@endsection