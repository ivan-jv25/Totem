<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function probar_api_info_usuario() {
        $usuario = \App\Http\Controllers\ApiController::get_info_usuario_api();
        dd($usuario);
        return;
    }

    public function probar_api_familia() {
        $familia = \App\Http\Controllers\ApiController::get_familia_api();
        dd($familia);
        return;
    }

    public function probar_api_sub_familia() {
        $sub_familia = \App\Http\Controllers\ApiController::get_sub_familia_api();
        dd($sub_familia);
        return;
    }

    public function probar_api_bodega() {
        $bodega = \App\Http\Controllers\ApiController::get_bodega_api();
        dd($bodega);
        return;
    }

    public function probar_api_forma_pago() {
        $forma_pago = \App\Http\Controllers\ApiController::get_forma_pago_api();
        dd($forma_pago);
        return;
    }

    public function probar_api_producto_paginado() {
        $producto_paginado = \App\Http\Controllers\ApiController::get_producto_paginado_api();
        dd($producto_paginado);
        return;
    }

    public function probar_api_producto() {
        $producto = \App\Http\Controllers\ApiController::get_producto_api();
        dd($producto);
        return;
    }

    public function probar_api_generar_venta() {
        $venta = \App\Http\Controllers\ApiController::generar_venta_api();
        dd($venta);
        return;
    }

}