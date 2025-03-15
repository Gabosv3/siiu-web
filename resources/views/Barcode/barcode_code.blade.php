@extends('layouts.user_type.auth')

@section('content')
<!-- resources/views/barcode_scanner.blade.php -->
<div class="container card d-flex justify-content-center align-items-center " style="min-height: 75vh;">
    <h2>Escanea el Código de Barras</h2>
    <form action="{{ route('procesar.codigo') }}" method="GET" id="barcodeForm">
        
        <input type="text" name="barcode" id="barcodeInput" class="form-control" placeholder="Escanee el código aquí" autofocus>
        <button type="submit" class="btn btn-primary mt-3">Procesar Código</button>
    </form>
</div>

<script>
    document.getElementById('barcodeInput').addEventListener('input', function() {
        // Enviar el formulario automáticamente al escanear el código
        if (this.value.length > 5) { // Ajusta el número mínimo de caracteres según tu código de barras
            document.getElementById('barcodeForm').submit();
        }
    });
</script>
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
