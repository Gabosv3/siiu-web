<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Modulos\CategoriaController;
use App\Http\Controllers\Modulos\DashboardController;
use App\Http\Controllers\Modulos\DepartamentoController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Modulos\RoleController;
use App\Http\Controllers\Modulos\SQLMaintenanceController;
use App\Http\Controllers\Modulos\LoginSecurityController;
use App\Http\Controllers\Modulos\PasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Modulos\UserController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'guest'], function () {
    // Iniciar sesión
    Route::get('Login', [AuthController::class, 'login'])->name('login');
    // Verificar datos de sesión
    Route::post('Login', [AuthController::class, 'loginVerify'])->name('login.verify');
});

// Verificación de correo electrónico
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');
});

// Rutas para solicitud de restablecimiento de contraseña
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Rutas para restablecimiento de contraseña
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/resets', [ResetPasswordController::class, 'reset'])->name('password.update.foremail');

Route::middleware(['auth', 'prevent-back-history', 'two_fa', 'verified'])->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/Administrativo', [DashboardController::class, 'Principal'])->middleware('can:dashboard')->name('Permisos');
    Route::get('/dashboard/Operativo', [DashboardController::class, 'Secundario'])->name('Secundario');
    Route::get('/nosotros', [DashboardController::class, 'Nosotros'])->name('Nosotros');
    
    // Usuario
    Route::resource('user', UserController::class);
    Route::put('/user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
    Route::get('/user/{id}/one_edit', [UserController::class, 'one_edit'])->name('user.one_edit');
    Route::match(['put', 'patch'], '/user/one_update/{user}', [UserController::class, 'one_update'])->name('user.one_update');

    Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');
    
    // Roles
    Route::resource('role', RoleController::class);
    Route::put('/role/{role}/restore', [RoleController::class, 'restore'])->name('role.restore');
    
    // Departamentos
    Route::resource('departamentos', DepartamentoController::class);
    Route::put('/departamentos/{departamento}/restore', [DepartamentoController::class, 'restore'])->name('departamento.restore');
    
    // Categorias
    Route::resource('categorias', CategoriaController::class);
    Route::put('/categorias/{categorias}/restore', [CategoriaController::class, 'restore'])->name('categorias.restore');
    
    // SQL Mantenimiento
    Route::get('/SQL-Mantenimiento', [SQLMaintenanceController::class, 'index'])->name('sql');
    Route::get('/sql/download', [SQLMaintenanceController::class, 'download'])->name('sql.download');
    Route::post('/sql/upload', [SQLMaintenanceController::class, 'upload'])->name('sql.upload');
    
    // Two Factor Authentication
    Route::prefix('two_factor_auth')->group(function () {
        Route::get('/', [LoginSecurityController::class, 'show2faForm'])->name('show2FASettings');
        Route::post('generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
        Route::post('enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
        Route::post('disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');
        Route::middleware('two_fa')->post('/2faVerify', [LoginSecurityController::class, 'verify2fa'])->name('2faVerify');
    });
    
    // Cerrar sesión
    Route::post('signOut', [AuthController::class, 'signOut'])->name('signOut');
});

Route::get('/forbidden', function () {
    throw new AuthorizationException();
});
