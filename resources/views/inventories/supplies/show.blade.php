@extends('layouts.user_type.auth')

@section('content')
 <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $supply->name }}</h2>
                </div>   
                <div class="card-body">
                    <p><strong>Descripción:</strong> {{ $supply->description }}</p>
                    <p><strong>Fecha de Creación:</strong> {{ $supply->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Fecha de Actualización:</strong> {{ $supply->updated_at->format('d/m/Y H:i') }}</p>
                    <div class="col-md-12 text-end">
                        <a href="{{ route('supplies.index') }}" class="btn btn-primary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection