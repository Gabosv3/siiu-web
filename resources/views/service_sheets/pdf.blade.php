<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Servicio #{{ $serviceSheet->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        p {
            font-size: 14px;
            line-height: 1.6;
            margin: 10px 0;
        }

        .section-title {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
        }

        .service-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .service-details p {
            margin: 0;
            flex: 1 1 calc(50% - 15px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        /* Sección de firmas */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
        }

        .signature-box {
            text-align: center;
        }

        .signature-label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .signature {
            width: 250px;
            height: 50px;
            border-top: 1px solid #333;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hoja de Servicio</h1>

        <div class="service-details">
            <p><strong>Ticket ID:</strong> {{ $serviceSheet->ticket_id }}</p>
            <p><strong>Departamento:</strong> {{ $serviceSheet->department->name ?? 'Desconocido' }}</p>
            <p><strong>Usuario:</strong> {{ optional($serviceSheet->user)->name ?? 'Desconocido' }}</p>
            <p><strong>Técnico:</strong> {{ optional($serviceSheet->technician)->name ?? 'Desconocido' }}</p>
            <p><strong>Descripción:</strong> {{ $serviceSheet->description }}</p>
            <p><strong>Observaciones:</strong> {{ $serviceSheet->observations ?? 'No hay observaciones' }}</p>
        </div>

        <div class="section-title">Detalles del Equipo</div>
        <table>
            <tr>
                <th>Categoría</th>
                <td>{{ $serviceSheet->hardware->category->name ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <th>Modelo</th>
                <td>{{ $serviceSheet->hardware->model->name ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Fabricante</th>
                <td>{{ $serviceSheet->hardware->manufacturer->name ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Código de Inventario</th>
                <td>{{ $serviceSheet->hardware->inventory_code ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Número de Serie</th>
                <td>{{ $serviceSheet->hardware->serial_number ?? 'No especificado' }}</td>
            </tr>
        </table>

        <div class="section-title">Insumos Utilizados</div>
        <table>
            <thead>
                <tr>
                    <th>Insumo</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($serviceSheet->supplies_data as $supply)
                    <tr>
                        <td>{{ $supply['name'] }}</td>
                        <td>{{ $supply['quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Sección de Firmas -->
        <div class="signature-section" style="display: flex; justify-content: space-between; width: 100%;">
            <div class="signature-box" style="text-align: left; flex: 1;">
                <span class="signature-label">Firma del Técnico</span>
                <div class="signature" style="border-top: 1px solid #333; margin-top: 30px;"></div>
            </div>
            <div class="signature-box" style="text-align: left; flex: 1;">
                <span class="signature-label">Firma del Usuario</span>
                <div class="signature" style="border-top: 1px solid #333; margin-top: 30px;"></div>
            </div>
        </div>

        <div class="footer">
            <p>Generado el {{ now()->format('d-m-Y H:i:s') }}</p>
            <p>© {{ date('Y') }} - Sistema de gestión de soporte tecnico</p>
        </div>
    </div>
</body>

</html>
