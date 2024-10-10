<?php

use App\Http\Controllers\Modulos\AssignmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Modulos\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Modulos\RoleController;
use App\Http\Controllers\Modulos\SQLMaintenanceController;
use App\Http\Controllers\Modulos\LoginSecurityController;
use App\Http\Controllers\Modulos\PasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\Modulos\CategoriesController;
use App\Http\Controllers\Modulos\DepartamentController;
use App\Http\Controllers\Modulos\FabricanteController;
use App\Http\Controllers\Modulos\HardwareController;
use App\Http\Controllers\Modulos\LicenseController;
use App\Http\Controllers\Modulos\ModeloController;
use App\Http\Controllers\Modulos\UserController;
use App\Http\Controllers\Modulos\SoftwareController;
use App\Http\Controllers\Modulos\SpecialtyController;
use App\Http\Controllers\Modulos\TechnicianController;
use App\Http\Controllers\Modulos\TecnicoController;
use App\Http\Controllers\Modulos\TicketController;
use App\Models\Ticket;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Redirecciona la raíz del sitio a la página de inicio de sesión.
Route::get('/', function () {
    return redirect()->route('login');
});

// Grupo de rutas accesibles solo para usuarios invitados (no autenticados).
Route::group(['middleware' => 'guest'], function () {
    // Muestra la vista de inicio de sesión.
    Route::get('Login', [AuthController::class, 'login'])->name('login');

    // Verifica las credenciales de inicio de sesión y autentica al usuario.
    Route::post('Login', [AuthController::class, 'loginVerify'])->name('login.verify');
});

// Verificación de correo electrónico para usuarios autenticados.
Route::middleware('auth')->group(function () {
    // Muestra una notificación para verificar el correo si aún no está verificado.
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Verifica el correo del usuario usando un enlace firmado.
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill(); // Marca el correo como verificado.
        return redirect('/dashboard'); // Redirige al usuario al dashboard después de la verificación.
    })->middleware(['signed'])->name('verification.verify');

    // Reenvía el enlace de verificación de correo al usuario.
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');
});

// Rutas relacionadas con la solicitud de restablecimiento de contraseña.
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Rutas relacionadas con el restablecimiento de la contraseña.
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/resets', [ResetPasswordController::class, 'reset'])->name('password.update.foremail');

// Grupo de rutas accesibles solo para usuarios autenticados, con autenticación y verificación de dos factores.
Route::middleware(['auth', 'prevent-back-history', 'two_fa', 'verified'])->group(function () {
    // Muestra el dashboard principal.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Muestra el dashboard para usuarios con permisos administrativos.
    Route::get('/dashboard/Administrativo', [DashboardController::class, 'Principal'])->middleware('can:dashboard')->name('Permisos');

    // Muestra el dashboard operativo.
    Route::get('/dashboard/Operativo', [DashboardController::class, 'Secundario'])->name('Secundario');

    // Página de "Nosotros".
    Route::get('/nosotros', [DashboardController::class, 'Nosotros'])->name('Nosotros');

    // Rutas CRUD para el recurso de usuarios.
    Route::resource('user', UserController::class);

    // Restaura un usuario eliminado.
    Route::put('/user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');

    // Muestra una vista para editar un solo campo del usuario.
    Route::get('/user/{id}/one_edit', [UserController::class, 'one_edit'])->name('user.one_edit');

    // Actualiza un solo campo del usuario.
    Route::match(['put', 'patch'], '/user/one_update/{user}', [UserController::class, 'one_update'])->name('user.one_update');

    // Actualiza la contraseña del usuario.
    Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');

    // Rutas CRUD para el recurso de roles.
    Route::resource('role', RoleController::class);

    // Restaura un rol eliminado.
    Route::put('/role/{role}/restore', [RoleController::class, 'restore'])->name('role.restore');

    // Rutas CRUD para el recurso de departamentos.
    Route::resource('departaments', DepartamentController::class);

    // Restaura un departamento eliminado.
    Route::put('/departaments/{departament}/restore', [DepartamentController::class, 'restore'])->name('departamentos.restore');

    // Rutas CRUD para el recurso de categorías.
    Route::resource('categories', CategoriesController::class);

    // Muestra las vistas relacionadas con inventarios de categorías.
    Route::get('inventarios', [CategoriesController::class, 'categoryViews'])->name('categories.views');

    // Restaura una categoría eliminada.
    Route::put('/categories/{category}/restore', [CategoriesController::class, 'restore'])->name('categories.restore');

    // Muestra la vista de SQL Mantenimiento.
    Route::get('/SQL-Mantenimiento', [SQLMaintenanceController::class, 'index'])->name('sql');

    // Descarga una copia de seguridad de la base de datos.
    Route::get('/sql/download', [SQLMaintenanceController::class, 'download'])->name('sql.download');

    // Sube una copia de seguridad a la base de datos.
    Route::post('/sql/upload', [SQLMaintenanceController::class, 'upload'])->name('sql.upload');

    // Rutas relacionadas con la autenticación de dos factores.
    Route::prefix('two_factor_auth')->group(function () {
        // Muestra la configuración de 2FA.
        Route::get('/', [LoginSecurityController::class, 'show2faForm'])->name('show2FASettings');

        // Genera una clave secreta para 2FA.
        Route::post('generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');

        // Habilita la autenticación de dos factores.
        Route::post('enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');

        // Deshabilita la autenticación de dos factores.
        Route::post('disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');

        // Verifica el código 2FA cuando se accede a áreas protegidas.
        Route::middleware('two_fa')->post('/2faVerify', [LoginSecurityController::class, 'verify2fa'])->name('2faVerify');
    });

    // Rutas CRUD para inventarios de software y hardware.
    Route::resource('inventarios/softwares', SoftwareController::class);

    // Restaura un software eliminado.
    Route::put('/inventarios/software/{software}/restore', [SoftwareController::class, 'restore'])->name('softwares.restore');

    Route::resource('inventarios/hardwares', HardwareController::class);

    // Restaura un hardware eliminado.
    Route::put('/inventarios/hardware/{hardware}/restore', [HardwareController::class, 'restore'])->name('hardwares.restore');

    // Sube un archivo CSV para los inventarios.
    Route::post('/upload-csv', [CsvController::class, 'upload'])->name('csv.upload');

    // Crea un nuevo fabricante.
    Route::post('/fabricantes', [FabricanteController::class, 'store'])->name('fabricantes.store');

    // Crea un nuevo modelo asociado a un fabricante.
    Route::post('/modelos/store', [ModeloController::class, 'store'])->name('modelos.store');

    // Obtiene los modelos asociados a un fabricante.
    Route::get('/modelos/por-fabricante/{fabricante}', [ModeloController::class, 'getModelosPorFabricante']);

    // Rutas CRUD para las licencias de software.
    Route::resource('inventarios/licenses', LicenseController::class);

    // Restaura una licencia eliminada.
    Route::put('/inventarios/licenses/{license}/restore', [LicenseController::class, 'restore'])->name('licenses.restore');

    // Rutas CRUD para tickets.
    Route::resource('/tickets', TicketController::class);
    // Muestra la vista de tickets.
    Route::get('/createtickets', [TicketController::class, 'crearTicketindex'])->name('vista.tickets');
    // Crea un nuevo ticket.
    Route::post('/ticketscreate', [TicketController::class, 'createTicket'])->name('crear.tickets');

    // Rutas CRUD para técnicos.
    Route::resource('technician', TechnicianController::class);

    // Rutas CRUD para especialidades.
    Route::post('/specialties', [SpecialtyController::class, 'store'])->name('specialties.store');

    // asignaciones de tickets
    Route::get('/assignments', [AssignmentController::class, 'index']);
    Route::get('/tickets/{id}/assign', [AssignmentController::class, 'showAssignForm'])->name('tickets.assignForm');
    Route::post('/tickets/{id}/assign', [AssignmentController::class, 'assign'])->name('tickets.assign');
    Route::get('/mytickets', [TicketController::class, 'Mytickets'])->name('Mytickets');
    Route::get('/get-technicians/{specialty_id}', [AssignmentController::class, 'getTechniciansBySpecialty'])->name('get-technicians');


    Route::match(['get', 'post'], '/botman', function () {
        $config = [];
    
        // Crear una instancia de BotMan
        $botman = BotManFactory::create($config);

        
    
        // Escucha para crear un usuario
        $botman->hears('listar usuarios', function (BotMan $bot) {
            $bot->reply("Mostrando la lista de usuarios. Haz clic aquí: <a href=\"" . route('user.index') . "\">Haz clic aquí</a>");
            
        });
    
        // Escucha para crear un usuario
        $botman->hears('crear usuario', function (BotMan $bot) {
            $bot->reply("Creando un nuevo usuario. Haz clic aquí: <a href=\"" . route('user.create') . "\">Haz clic aquí</a>");
            
        });

    
        // Escucha cualquier mensaje desconocido
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Lo siento, no entendí eso. Intenta con un comando como "crear usuario", "listar usuarios", o "mostrar inventarios".');
        });
    
        // Procesar todas las interacciones
        $botman->listen();
    });

    // Cerrar sesión.
    Route::post('signOut', [AuthController::class, 'signOut'])->name('signOut');
});

// Muestra una página de error de autorización cuando el acceso es denegado.
Route::get('/forbidden', function () {
    throw new AuthorizationException();
});
