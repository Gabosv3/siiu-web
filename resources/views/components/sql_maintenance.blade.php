@extends('layouts.user_type.auth')

@section('content')
    <div class="mt-1">
        <h1>SQL Mantenimiento </h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="card">
            <div class="card-header">Descargar Tablas</div>
            <div class="card-body">
                <form action="{{ route('sql.download') }}" method="GET">
                    <div class="form-group">
                        <label for="tables">Select Tables</label>
                        @foreach($tables as $table)
                            @php
                                $tableName = array_values((array) $table)[0];
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tables[]" value="{{ $tableName }}">
                                <label class="form-check-label">{{ $tableName }}</label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Descargar SQL</button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">Subir tablas</div>
            <div class="card-body">
                <form action="{{ route('sql.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="sql_file">SQL File</label>
                        <input type="file" class="form-control" name="sql_file" required>
                    </div>
                    <button type="submit" class="btn btn-green-600">Subir SQL</button>
                </form>
            </div>
        </div>
    </div>

    
@endsection