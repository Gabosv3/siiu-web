@extends('layouts.user_type.auth')



@section('content')
<div class="container">
    <h1>Detalles de la Software</h1>
    <div class="card">
        <div class="card-header">
            software #{{ $software->id }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Software: {{ $software->software_name }}</h5>
            <p class="card-text"><strong>Fabricante:</strong> {{ $software->manufacturer->name }}</p>


            <!-- Listar las licencias -->
            @if($licencias->isNotEmpty())
            <ul>
                @foreach($licencias as $licencia)
                <li>Licencia ID: {{ $licencia->id }} - {{ $licencia->license_key }}</li>
                @endforeach
            </ul>
            @else
            <p>No hay licencias vinculadas a este software.</p>
            @endif
            <a href="{{ route('softwares.edit', $software->id) }}" class="btn btn-primary">Editar Software</a>
            <a href="{{ route('softwares.index') }}" class="btn btn-secondary">Volver a la Lista</a>
        </div>
    </div>
</div>
@endsection