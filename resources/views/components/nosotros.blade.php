<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <title>Equipo DEV</title>
</head>

<body>
    <div class="hero">
        <h1>Nuestro Equipo</h1>
    </div>
    <div class="container">

        <div class="evento">
            <div class="foto" data-aos="fade-down">
                <img src="{{ asset('img/azucena.png') }}" alt="">
                <h4 class="text-center">Scrum Master</h4>
            </div>
            <h3 class="fecha">Azucena Merlios</h3>
        </div>
        <div class="evento">
            <div class="foto" data-aos="fade-up">
                <img src="{{ asset('img/gabriel.jpg') }}" alt="">
                <h4 class="text-center">Developer Web</h4>
            </div>
            <h3 class="fecha">Gabriel Alegria</h3>
        </div>
        <div class="evento">
            <div class="foto" data-aos="fade-left">
                <img src="{{ asset('img/3.png') }}" alt="">
                <h4 class="text-center">Developer Mobile</h4>
            </div>
            <h3 class="fecha">Claire Mata</h3>
        </div>
        <div class="evento">
            <div class="foto" data-aos="fade-right">
                <img src="{{ asset('img/4.png') }}" alt="">
            </div>
            <h3 class="fecha">Junnior Villalta</h3>
        </div>
        <div class="evento">
            <div class="foto" data-aos="fade-up">
                <img src="{{ asset('img/5.png') }}" alt="">
            </div>
            <h3 class="fecha">Equipo Completo</h3>
        </div>
    </div>

    <div class="btn-container">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver a inicio</a>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
