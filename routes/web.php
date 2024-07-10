<?php

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
    return view('welcome');
});

// Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth'])->name('dashboard');

Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'go_to_tienda'])->name('dashboard');

Route::get('iniciar/totem', [App\Http\Controllers\HomeController::class, 'iniciar_totem'])->name('iniciar.totem');

Route::get('api/info/usuario', [App\Http\Controllers\HomeController::class, 'probar_api_info_usuario'])->name('api.info.usuario');

Route::get('api/familia', [App\Http\Controllers\HomeController::class, 'probar_api_familia'])->name('api.familia');
Route::get('api/sub/familia', [App\Http\Controllers\HomeController::class, 'probar_api_sub_familia'])->name('api.sub.familia');
Route::get('api/bodega', [App\Http\Controllers\HomeController::class, 'probar_api_bodega'])->name('api.bodega');
Route::get('api/forma/pago', [App\Http\Controllers\HomeController::class, 'probar_api_forma_pago'])->name('api.forma.pago');
Route::get('api/producto/paginado', [App\Http\Controllers\HomeController::class, 'probar_api_producto_paginado'])->name('api.producto.paginado');
Route::post('api/producto', [App\Http\Controllers\HomeController::class, 'probar_api_producto'])->name('api.producto');
Route::get('api/generar/venta', [App\Http\Controllers\HomeController::class, 'probar_api_generar_venta'])->name('api.generar.venta');
Route::post('api/producto/especifico', [App\Http\Controllers\HomeController::class, 'probar_api_producto_especifico'])->name('api.producto.especifico');
Route::post('api/giftcard/codigobarra', [App\Http\Controllers\HomeController::class, 'probar_api_giftcard_codigobarra'])->name('api.giftcard.codigobarra');
Route::get('api/giftcard/datos', [App\Http\Controllers\HomeController::class, 'probar_api_giftcard_datos'])->name('api.giftcard.datos');


// Route::get('store/familia', [App\Http\Controllers\HomeController::class, 'store_familia'])->name('store.familia');
// Route::get('store/sub/familia', [App\Http\Controllers\HomeController::class, 'store_sub_familia'])->name('store.sub.familia');
// Route::get('store/bodega', [App\Http\Controllers\HomeController::class, 'store_bodega'])->name('store.bodega');
// Route::get('store/forma/pago', [App\Http\Controllers\HomeController::class, 'store_forma_pago'])->name('store.forma.pago');

Route::get('store/producto', [App\Http\Controllers\HomeController::class, 'store_producto'])->name('store.producto');
Route::get('store/datos/venta', [App\Http\Controllers\HomeController::class, 'store_datos_venta'])->name('store.datos.venta');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
