@extends('layouts.user_type.auth')

@section('title', 'SIIU-asd')

@section('content')
<?php
$fechaActual = date("j M, Y");
?>

<div class="container">
    <div class="row">
        <div class="col-xl-4 col-lg-4">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <h5 class="card-title mb-0">Usuarios</h5>
                    <h2 class="d-flex align-items-center mb-0">
                        {{ $userCount }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-4">
            <div class="card l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                    <h5 class="card-title mb-0">Tickets Abiertos</h5>
                    <h2 class="d-flex align-items-center mb-0">
                        {{ $openTickets }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-4">
            <div class="card l-bg-orange-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                    <h5 class="card-title mb-0">Tickets Cerrados</h5>
                    <h2 class="d-flex align-items-center mb-0">
                        {{ $closedTickets }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card l-bg-purple-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-user-tie"></i></div>
                    <h5 class="card-title mb-0">Técnico con más Tickets</h5>
                    <h2 class="d-flex align-items-center mb-0">
                        {{ $topTechnician->user->name ?? 'N/A' }} ({{ $topTechnician->tickets_count ?? 0 }})
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card l-bg-purple-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas  fa-exclamation-triangle "></i></div>
                    <h5 class="card-title mb-0">Problema mas Frecuente</h5>
                    <h2 class="d-flex align-items-center mb-0">
                        No enciende la computadora (3)
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <canvas id="usersByDayChart"></canvas>
        </div>

        <div class="col-xl-6 col-lg-6">
            <canvas id="hardwareByCategoryChart"></canvas>
        </div>

    </div>

    <div class="row">

        <div class="col-xl-4 col-lg-4">
            <canvas id="ticketsByPriorityChart"></canvas>
        </div>

        <div class="col-xl-4 col-lg-4">
            <canvas id="hardwareWithConflictsChart"></canvas>
        </div>
        <div class="col-xl-4 col-lg-4">
            <canvas id="hardwareWithWarrantyChart"></canvas>
        </div>
    </div>

   
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let dates = <?php echo json_encode($dates); ?>;
    let counts = <?php echo json_encode($counts); ?>;

    let ctx2 = document.getElementById('usersByDayChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Usuarios creados por mes',
                data: counts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    let ticketPriorities = <?php echo json_encode($ticketPriorities); ?>;
    let labels = Object.keys(ticketPriorities);
    let data = Object.values(ticketPriorities);

    let ctx3 = document.getElementById('ticketsByPriorityChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tickets por Prioridad en Proceso',
                data: data,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0'],
            }]
        }
    });

    let hardwareByCategory = <?php echo json_encode($hardwareByCategory); ?>;

    // Extraer las etiquetas (nombres de las categorías) y los valores (totales de hardware)
    let hardwareLabels = hardwareByCategory.map(item => item.category_name);
    let hardwareData = hardwareByCategory.map(item => item.total);

    console.log(hardwareData); // Esto debería mostrar las cantidades de hardware por categoría

    let ctx4 = document.getElementById('hardwareByCategoryChart').getContext('2d');
    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: hardwareLabels, // Usamos los nombres de las categorías como etiquetas
            datasets: [{
                label: 'Hardware por Categoría',
                data: hardwareData, // Usamos las cantidades de hardware
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Asegurarse de que el eje Y comience en 0
                },
            },
            
        }
    });


    let hardwareWithConflicts = <?php echo json_encode($hardwareWithConflicts); ?>;
    let ctx5 = document.getElementById('hardwareWithConflictsChart').getContext('2d');
    new Chart(ctx5, {
        type: 'pie',
        data: {
            labels: ['Con Conflictos', 'Sin Conflictos'],
            datasets: [{
                data: [hardwareWithConflicts, <?php echo e($totalHardware - $hardwareWithConflicts); ?>],
                backgroundColor: ['#ff9f40', '#36a2eb'],
            }]
        }
    });

    let hardwareWithWarranty = <?php echo json_encode($hardwareWithWarranty); ?>;
    let ctx6 = document.getElementById('hardwareWithWarrantyChart').getContext('2d');
    new Chart(ctx6, {
        type: 'pie',
        data: {
            labels: ['Con Garantía', 'Sin Garantía'],
            datasets: [{
                data: [hardwareWithWarranty, <?php echo e($totalHardware - $hardwareWithWarranty); ?>],
                backgroundColor: ['#4bc0c0', '#ffcd56'],
            }]
        }
    });
</script>
@endsection