@extends('layouts.user_type.auth')

@section('content')
<div class="container " style="min-height: 75vh;">
    <h2 class="my-4  p-3">Detalles del Ticket # {{ $ticket->id }}</h2>
    <button class="btn btn-primary my-3" onclick="printTicket()">Imprimir Ticket</button>

    <div class="row ">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ESTADO</h5>
                    <p class="card-text">{{ $ticket->status }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">TECNICO ASIGNADO</h5>
                    <p class="card-text">{{ $ticket->technician ? $ticket->technician->user->name : 'No Asignado' }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">FECHA DE EMISION DEL TICKET</h5>
                    <p class="card-text">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">DETALLES DEL TICKET</h5>
                    <p class="card-text"><b>Título:</b> {{ $ticket->title->name }}</p>
                    <p class="card-text"><b>Descripción:</b> {{ $ticket->description }}</p>
                    <p class="card-text"><b>Lugar:</b> {{ $ticket->user->departament->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-flex align-items-stretch text-center"> <!-- Flexbox aplicado en la fila -->

        <div class="col-sm-6 d-flex"> <!-- Flexbox aplicado en las columnas -->
            <div class="card flex-fill"> <!-- Asegúrate de que la tarjeta llene toda la altura -->
                <h5 class="card-title my-3">HISTORIAL DE ASIGNACIONES</h5>
                @foreach($ticket->assignments as $assignment)
                <div class="row text-center mb-3 align-items-center"> <!-- Alinea verticalmente -->
                    <div class="col-sm-4">
                        <b>{{ ($assignment->initial_date)->format('d/m/Y') }}</b> <br>
                        {{ ($assignment->initial_date)->format('H:i') }}
                    </div>
                    <div class="col-sm-4 d-flex flex-column justify-content-center align-items-center position-relative" style="min-height: 100px;">
                        <div class="vertical-line" style="position: absolute; width: 1px; background-color: #ccc; height: 20px; left: 50%; transform: translateX(-50%); top: 5px; z-index: 0;"></div> <!-- Línea arriba -->
                        <i class="fa fa-id-card" aria-hidden="true" style="font-size: 2rem; z-index: 1;"></i>
                        <div class="vertical-line" style="position: absolute; width: 1px; background-color: #ccc; height: 20px; left: 50%; transform: translateX(-50%); bottom: 5px; z-index: 0;"></div> <!-- Línea abajo -->
                    </div>

                    <div class="col-sm-4">
                        <b>Asignado a:</b> <br>
                        {{ $assignment->technician->user->name }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>


        <div class="col-sm-6 d-flex"> <!-- Flexbox aplicado en las columnas -->
            <div class="card flex-fill"> <!-- Asegúrate de que la tarjeta llene toda la altura -->
                <h5 class="card-title mt-3">Cambio de equipo</h5>
                <!-- Contenido de la segunda tarjeta -->
            </div>
        </div>

    </div>


</div><!-- Agrega atributos data- con los valores de Blade -->
<div id="ticket-data"
     data-id="{{ $ticket->id }}"
     data-status="{{ $ticket->status }}"
     data-created-at="{{ $ticket->created_at->format('d/m/Y H:i') }}"
     data-technician="{{ $ticket->technician ? $ticket->technician->user->name : 'No Asignado' }}"
     data-place="{{ $ticket->user->departament->name }}"
     data-title="{{ $ticket->title->name }}"
     data-description="{{ $ticket->description }}">
</div>

<script>
     function capitalizeFirstLetter(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}


    function printTicket() {
        const ticketData = document.getElementById('ticket-data');
        const ticketId = ticketData.getAttribute('data-id');
        const ticketTitle = capitalizeFirstLetter(ticketData.getAttribute('data-title'));
        const ticketStatus = capitalizeFirstLetter(ticketData.getAttribute('data-status'));
        const ticketCreatedAt = ticketData.getAttribute('data-created-at');
        const ticketTechnician = capitalizeFirstLetter(ticketData.getAttribute('data-technician'));
        const ticketPlace = capitalizeFirstLetter(ticketData.getAttribute('data-place'));
        const ticketDescription = capitalizeFirstLetter(ticketData.getAttribute('data-description'));

        const printContent = `
            <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                        }
                        h2 {
                            color: #004085;
                            border-bottom: 2px solid #004085;
                            padding-bottom: 5px;
                            font-size: 14px;
                            text-align: center;
                        }
                        p {
                            font-size: 12px;
                        }
                        @media print {
                            body {
                                margin: 0;
                                padding: 0;
                            }
                        }
                        @page { size: 50mm 80mm; margin: 0; }
                    </style>
                </head>
                <body>
                    <h2>Detalles del Ticket</h2>
                    <p><strong>Ticket Numero:</strong> ${ticketId}</p>
                    <p><strong>Título:</strong> ${ticketTitle}</p>
                    <p><strong>Estado:</strong> ${ticketStatus}</p>
                    <p><strong>Fecha de creación:</strong> ${ticketCreatedAt}</p>
                    <p><strong>Asignado a:</strong> ${ticketTechnician}</p>
                    <p><strong>Lugar:</strong> ${ticketPlace}</p>
                    <p><strong>Descripcion:</strong> ${ticketDescription}</p>
                </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    }
</script>



@endsection