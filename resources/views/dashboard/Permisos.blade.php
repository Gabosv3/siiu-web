@extends('layouts.user_type.auth')

@section('title','SIIU-asd' )


@section('content')
<?php
$fechaActual = date("j M, Y"); // M: mes (e.g. Jan), j: día del mes (1-31), Y: año (4 dígitos)
?>



<div class="container">
    <div class="row ">
        <div class="col-xl-4 col-lg-4">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Usuarios</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $userCount }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><?= $fechaActual ?></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-green" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <div class="card l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Ticket Resolved</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                578
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span>10% <i class="fa fa-arrow-up"></i></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-orange" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <div class="card l-bg-orange-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Revenue Today</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                $11.61k
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span>2.5% <i class="fa fa-arrow-up"></i></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contenedor para el gráfico de usuarios creados por día -->
        <div class="col-xl-6 col-lg-6">
            <canvas id="usersByDayChart"></canvas>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Obtener los datos de usuarios creados por día desde PHP usando json_encode()
    var dates = <?php echo json_encode($dates); ?>;
    var counts = <?php echo json_encode($counts); ?>;
    console.log('Fechas:', dates); // Verificación en consola
    console.log('Conteos:', counts); // Verificación en consola

    // Crear gráfico de línea para usuarios creados por día
    var ctx2 = document.getElementById('usersByDayChart').getContext('2d');
    var usersByDayChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: dates, // Fechas en el eje X
            datasets: [{
                label: 'Usuarios creados por día',
                data: counts, // Conteo de usuarios por día
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                fill: true,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    type: 'category', // Usamos 'category' si no queremos formato de tiempo
                }
            }
        }
    });
</script>
@endsection