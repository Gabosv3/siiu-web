@extends('layouts.user_type.auth')

@section('content')

    <div class="card mt-4 p-3" style="min-height: 75vh;">
        <h2>Características del Modelo {{ $model->name }}</h2>

        @if ($model->modelCharacteristics->isEmpty())
            <p>No hay características disponibles.</p>
        @else
            <ul>
                @foreach ($model->modelCharacteristics as $rel)
                    <li>{{ $rel->characteristic->name }}: {{ $rel->value }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
