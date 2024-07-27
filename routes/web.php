<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SQLMaintenanceController;
use App\Http\Controllers\LoginSecurityController;
use App\Http\Controllers\UserController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Route;


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
    //Iniciar session
    Route::get('Login', [AuthController::class, 'login'])->name('login');
    //Verficar datos de session
    Route::post('Login', [AuthController::class, 'loginVerify'])->name('login.verify');
});



Route::middleware(['auth', 'prevent-back-history', 'two_fa'])->group(function () {
    //DASHBOAR
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/Administrativo', [DashboardController::class, 'Principal'])->middleware('can:dashboard')->name('Permisos');
    Route::get('/dashboard/Operativo', [DashboardController::class, 'Secundario'])->name('Secundario');
    Route::get('/nosotros', [DashboardController::class, 'Nosotros'])->name('Nosotros');
    //usuario
    Route::resource('user', UserController::class);
    Route::put('/user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
    route::get('/user/{id}/one_edit', [UserController::class, 'one_edit'])->name('user.one_edit');
    Route::match(['put', 'patch'], '/user/one_update/{user}', [UserController::class, 'one_update'])->name('user.one_update');


    //Roles
    Route::resource('role', RoleController::class);
    Route::put('/role/{role}/restore', [RoleController::class, 'restore'])->name('role.restore');

    //Departamentos
    Route::resource('departamentos', DepartamentoController::class);
    Route::put('/departamentos/{departamento}/restore', [DepartamentoController::class, 'restore'])->name('departamento.restore');
    //
    Route::resource('categorias', CategoriaController::class);
    Route::put('/categorias/{categorias}/restore', [CategoriaController::class, 'restore'])->name('categorias.restore');

    Route::get('/forbidden', function () {
        throw new AuthorizationException();
    });
    Route::get('/SQL-Mantenimiento', [SQLMaintenanceController::class, 'index'])->name('sql');
    Route::get('/sql/download', [SQLMaintenanceController::class, 'download'])->name('sql.download');
    Route::post('/sql/upload', [SQLMaintenanceController::class, 'upload'])->name('sql.upload');

    Route::prefix('two_factor_auth')->group(function () {
        Route::get('/', [LoginSecurityController::class, 'show2faForm'])->name('show2FASettings');
        Route::post('generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
        Route::post('enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
        Route::post('disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');
        Route::middleware('two_fa')->post('/2faVerify', [LoginSecurityController::class, 'verify2fa'])->name('2faVerify');
    });

    //Cerrar session
    Route::post('signOut', [AuthController::class, 'signOut'])->name('signOut');
});




// test middleware
