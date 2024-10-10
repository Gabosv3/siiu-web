@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Detalles de la Licencia</h1>
    <div class="card">
        <div class="card-header">
            Licencia #{{ $license->id }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Software: {{ $license->software->software_name }} ({{ $license->software->version }})</h5>
            <p class="card-text"><strong>Clave de Licencia:</strong> {{ $license->license_key }}</p>
            <p class="card-text"><strong>Fecha de Expiración:</strong> {{ $license->expiration_date }}</p>
            <p class="card-text"><strong>Notas:</strong> {{ $license->notes }}</p>
            <a href="{{ route('licenses.edit', $license->id) }}" class="btn btn-primary">Editar Licencia</a>
            <a href="{{ route('licenses.index') }}" class="btn btn-secondary">Volver a la Lista</a>
        </div>
    </div>
</div>
@endsection
