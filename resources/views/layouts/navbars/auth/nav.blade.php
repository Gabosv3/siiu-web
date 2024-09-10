@php
// Mapa de traducción de rutas
$translations = [
'home' => 'Inicio',
'user' => 'Usuarios',
'role' => 'Roles',
'edit' => 'Editar',
'create' => 'Crear',
'one_edit' => 'Editar',
'two_factor_auth' => 'Autenticación de dos factores',
// Añade más traducciones según sea necesario
];

// Obtén la ruta actual
$currentPath = Request::path();

// Divide la ruta en segmentos
$segments = explode('/', $currentPath);

// Inicializa el camino acumulado y el array para guardar los items del breadcrumb
$path = '';
$breadcrumbItems = [];

// Recorre cada segmento, tradúcelo y genera el enlace correspondiente
foreach ($segments as $index => $segment) {
// Acumula el camino hasta el segmento actual
$path .= '/' . $segment;

// Verifica si el segmento tiene una traducción, si no, usa el segmento como está
$translatedSegment = array_key_exists($segment, $translations) ? $translations[$segment] : ucfirst($segment);

// Si el segmento es 'edit', 'create', o 'one_edit', no generes un enlace
if (in_array($segment, ['edit', 'create', 'one_edit'])) {
$breadcrumbItems[] = '<span class="text-dark" id="btn-Module">' . $translatedSegment . '</span>';
} else {
// Genera el enlace del breadcrumb
$breadcrumbItems[] = '<a href="' . url($path) . '">' . $translatedSegment . '</a>';
}
}

// Junta los segmentos del breadcrumb en una cadena
$breadcrumbHtml = implode(' / ', $breadcrumbItems);

@endphp

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 border-radius-xl" >
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark mx-3" href="{{ url('/') }}">{{ $translations['home'] }}</a>
                </li>
                {!! $breadcrumbHtml !!}
            </ol>
        </nav>
    </div>

    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
        <ul class="navbar-nav  justify-content-end">
            <li class="nav-item dropdown d-flex align-items-center">
                <a class="nav-link dropdown-toggle text-body p-0 mx-4" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Usuario">
                    <i class="fa fa-user me-sm-1"></i> Usuario
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a title="Editar Perfil" href="{{ route('user.one_edit', Auth::user()->id) }}" class="dropdown-item text-center" id="btn-oneedit-module">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
                        </svg> Editar perfil
                    </a>
                    <form method="POST" action="{{route('signOut')}}" style="display: inline;">
                        @csrf
                        <button title="Cerrar sesión" id="btn-sign-out-module" type="submit" class="dropdown-item text-center" style="background: none;border: none;padding: 0;text-decoration: none;color: inherit;">
                            <i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </a>
            </li>
        </ul>
    </div>
    </div>
</nav>
<!-- End Navbar -->