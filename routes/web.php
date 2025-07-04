<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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

Route::get('/', function () { return view('welcome'); });
Auth::routes();


Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return "Optimización y caché limpiados";
});


Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('iniciar/totem', [App\Http\Controllers\HomeController::class, 'iniciar_totem'])->name('iniciar.totem');
    Route::post('api/token/bodega', [App\Http\Controllers\ApiController::class, 'login_usuario'])->name('api.token.bodega');

    // Totem
    Route::get('/Totem/Inicio', [App\Http\Controllers\HomeController::class, 'view_totem'])->name('totem.index');
    Route::post('/Totem/Shop', [App\Http\Controllers\HomeController::class, 'view_totem_shop'])->name('totem.shop');


    Route::get('/lista/productos', [App\Http\Controllers\ProductoController::class, 'obtener_productos'])->name('lista.productos');

    //Consulta Cliente
    Route::post('api/giftcard/codigobarra', [App\Http\Controllers\HomeController::class, 'giftcard_codigobarra'])->name('api.giftcard.codigobarra');


    // Colores
    Route::get('/colores', [App\Http\Controllers\ColoresController::class, 'index'])->name('colores.index');
    Route::post('/colores/actualizar', [App\Http\Controllers\ColoresController::class, 'update'])->name('colores.update');

    // Imagenes
    Route::get('/imagenes', [App\Http\Controllers\ImagenesController::class, 'index'])->name('imagenes.index');
    Route::post('/imagenes/logo', [App\Http\Controllers\ImagenesController::class, 'subir_logo'])->name('imagenes.logo');
    Route::post('/imagenes/logo/principal', [App\Http\Controllers\ImagenesController::class, 'subir_logo_principal'])->name('imagenes.logo.principal');

    Route::post('/imagenes/banner', [App\Http\Controllers\ImagenesController::class, 'subir_banner'])->name('imagenes.banner');
    Route::get('/imagenes/lista', [App\Http\Controllers\ImagenesController::class, 'lista_banner'])->name('imagenes.lista');
    Route::get('/imagenes/eliminar', [App\Http\Controllers\ImagenesController::class, 'eliminar_banner'])->name('imagenes.eliminar');

    Route::get('/Registro', [App\Http\Controllers\ClienteController::class, 'index'])->name('registro.cliente');
    Route::post('Registro/Cliente', [App\Http\Controllers\ClienteController::class, 'registro_cliente'])->name('registro.cliente.store');
    
});