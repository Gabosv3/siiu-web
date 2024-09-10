@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Agregar Nuevo Software</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('softwares.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nombre_software">Nombre del Software</label>
                <input type="text" name="nombre_software" class="form-control" placeholder="Nombre del Software" required>
            </div>

            <div class="form-group">
                <label for="version">Versión</label>
                <input type="text" name="version" class="form-control" placeholder="Versión" required>
            </div>

            <div class="form-group">
                <label for="fabricante">Fabricante</label>
                <input type="text" name="fabricante" class="form-control" placeholder="Fabricante" required>
            </div>

            <div class="form-group">
                <label for="asignada">Asignada a</label>
                <input type="text" name="asignada" class="form-control" placeholder="Nombre del usuario asignado">
            </div>

            <div class="form-group">
                <label for="ubicacion">Ubicación</label>
                <input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" required>
            </div>

            <div class="form-group">
                <label for="clasificacion_licencia">Clasificación de Licencia</label>
                <input type="text" name="clasificacion_licencia" class="form-control" placeholder="Clasificación de Licencia" required>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input type="text" name="tipo" class="form-control" placeholder="Tipo" required>
            </div>

            <!-- Campos adicionales -->
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" class="form-control" placeholder="Descripción del Software"></textarea>
            </div>

            <div class="form-group">
                <label for="clave_licencia">Clave de Licencia</label>
                <input type="text" name="clave_licencia" class="form-control" placeholder="Clave de Licencia">
            </div>

            <div class="form-group">
                <label for="fecha_compra">Fecha de Compra</label>
                <input type="date" name="fecha_compra" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Guardar</button>
        </form>
    </div>
@endsection