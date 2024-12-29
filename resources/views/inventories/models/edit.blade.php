@extends('layouts.user_type.auth')

@section('content')
<div class="card mt-4 p-3" style="min-height: 80vh;">

    <form action="{{ route('models.update', $model->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name_modelo">Nombre del modelo:</label>
            <input type="text" id="name_modelo" name="name_modelo" class="form-control" value="{{ $model->name_modelo }}">
        </div>
        <div class="form-group">        
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>

</div>
@endsection