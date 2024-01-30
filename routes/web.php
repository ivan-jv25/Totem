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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('api/info/usuario', [App\Http\Controllers\HomeController::class, 'probar_api_info_usuario'])->name('api.info.usuario');
Route::get('api/familia', [App\Http\Controllers\HomeController::class, 'probar_api_familia'])->name('api.familia');
Route::get('api/sub/familia', [App\Http\Controllers\HomeController::class, 'probar_api_sub_familia'])->name('api.sub.familia');
Route::get('api/bodega', [App\Http\Controllers\HomeController::class, 'probar_api_bodega'])->name('api.bodega');
Route::get('api/forma/pago', [App\Http\Controllers\HomeController::class, 'probar_api_forma_pago'])->name('api.forma.pago');
Route::get('api/producto/paginado', [App\Http\Controllers\HomeController::class, 'probar_api_producto_paginado'])->name('api.producto.paginado');
Route::get('api/producto', [App\Http\Controllers\HomeController::class, 'probar_api_producto'])->name('api.producto');
Route::get('api/generar/venta', [App\Http\Controllers\HomeController::class, 'probar_api_generar_venta'])->name('api.generar.venta');

require __DIR__.'/auth.php';
