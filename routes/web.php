<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;

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



Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    //DASHBOAR
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/Administrativo', [DashboardController::class, 'Principal'])->middleware('can:dashboard')->name('Permisos');
    Route::get('/dashboard/Operativo', [DashboardController::class, 'Secundario'])->name('Secundario');
    //usuario
    Route::resource('user', UserController::class);
    Route::put('/user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
    route::get('/user/{id}/one_edit',[UserController::class, 'one_edit'])->name('user.one_edit');
    Route::match(['put', 'patch'], '/user/one_update/{user}', [UserController::class, 'one_update'])->name('user.one_update');
    //Roles
    Route::resource('role', RoleController::class);
    Route::put('/role/{role}/restore', [RoleController::class, 'restore'])->name('role.restore');
    //Departamentos
    Route::resource('departamentos', DepartamentoController::class);
    //
    Route::get('/forbidden', function () {
        throw new AuthorizationException();
    });
    //Cerrar session
    Route::post('signOut', [AuthController::class, 'signOut'])->name('signOut');
});
