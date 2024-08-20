<!-- Navbar -->

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
    if (array_key_exists($segment, $translations)) {
        // Acumula el camino hasta el segmento actual
        $path .= '/' . $segment;
        
        // Si el segmento es 'edit', 'create' o 'one_edit', no generes un enlace
        if (in_array($segment, ['edit', 'create', 'one_edit'])) {
            $breadcrumbItems[] = '<span class="text-dark" id="btn-Module">' . $translations[$segment] . '</span>';
        } else {
            // Genera el enlace del breadcrumb
            $breadcrumbItems[] = '<a href="' . url($path) . '">' . $translations[$segment] . '</a>';
        }
    }
}

// Junta los segmentos del breadcrumb en una cadena
$breadcrumbHtml = implode(' / ', $breadcrumbItems);

@endphp


<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4  border-radius-xl " id="navbarBlur">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark mx-3" href="{{ url('/') }}">{{ $translations['home'] }}</a>
                </li>
                {!! $breadcrumbHtml !!}
            </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item dropdown d-flex align-items-center">
                    <a class="nav-link dropdown-toggle text-body p-0 mx-4" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user me-sm-1"></i> Usuario
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a href="{{ route('user.one_edit', Auth::user()->id) }}" class="dropdown-item text-center" id="btn-oneedit-module">
                            <i class="fa fa-pencil cursor-pointer"></i> Editar perfil
                        </a>
                        <form method="POST" action="{{route('signOut')}}" style="display: inline;">
                            @csrf
                            <button id="btn-sign-out-module" type="submit" class="dropdown-item text-center" style="background: none;border: none;padding: 0;text-decoration: none;color: inherit;">
                                <i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->