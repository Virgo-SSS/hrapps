<?php

use App\Http\Controllers\DivisiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosisiController;
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

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/',           'index')->name('users.index');
        Route::get('/create',     'create')->name('users.create');
        Route::post('/',          'store')->name('users.store');
        Route::get('/{user}/edit','edit')->name('users.edit');
        Route::put('/{user}',     'update')->name('users.update');
        Route::delete('/{user}',  'destroy')->name('users.destroy');
    });

    Route::controller(DivisiController::class)->prefix('divisi')->group(function () {
        Route::get('/',              'index')->name('divisi.index');
        Route::post('/',             'store')->name('divisi.store');
        Route::get('/{divisi}/edit', 'edit')->name('divisi.edit');
        Route::put('/{divisi}',      'update')->name('divisi.update');
        Route::delete('/{divisi}',   'destroy')->name('divisi.destroy');
    });

    Route::controller(PosisiController::class)->prefix('posisi')->group(function() {
        Route::get('/',              'index')->name('posisi.index');
        Route::post('/',             'store')->name('posisi.store');
        Route::get('/{posisi}/edit', 'edit')->name('posisi.edit');
        Route::put('/{posisi}',      'update')->name('posisi.update');
        Route::delete('/{posisi}',   'destroy')->name('posisi.destroy');

        Route::get('/posisi-by-divisi/{divisi_id}', 'getPosisiByDivisi')->name('posisi.by-divisi');
    });
});
