<?php

use App\Http\Controllers\CutiController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosisiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();


Route::middleware('auth')->group(function(){
    Route::get('/', function() {
        return redirect()->route('home');
    });
    Route::controller(HomeController::class)->prefix('home')->group(function(){
        Route::get('/', 'index')->name('home');
    });

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('divisi', DivisiController::class)->except(['show','create']);

    Route::get('posisi/posisi-by-divisi/{divisi_id}', [PosisiController::class,'getPosisiByDivisi'])->name('posisi.by-divisi');
    Route::resource('posisi', PosisiController::class)->except(['show','create']);

    Route::get('cuti/request', [CutiController::class,'request'])->name('cuti.request');
    Route::resource('cuti', CutiController::class);

    Route::get('role/{role}/permission', [RoleController::class,'permissions'])->name('role.permissions');
    Route::resource('role', RoleController::class)->except(['show']);
    Route::resource('permission', PermissionController::class)->except(['show', 'create', 'edit']);
});
