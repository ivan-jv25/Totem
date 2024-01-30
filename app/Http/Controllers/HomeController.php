<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Familia;
use DB;

class HomeController extends Controller
{

    public function probar_api_info_usuario() {
        $usuario = \App\Http\Controllers\ApiController::get_info_usuario_api();
        dd($usuario);
        return;
    }

    public function probar_api_familia() {
        $familia = \App\Http\Controllers\ApiController::get_familia_api();
        return $familia;
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

    public function store_familia() {

        $familia = $this->probar_api_familia();

        $familia = json_decode($familia);

        foreach ($familia as $key => $value) {

            $id_empresa = $value->id_empresa;
            $nombre = $value->nombre;
            $estado = $value->estado;

            $existe = self::familia_existe($id_empresa, $nombre);
            if (!$existe) {

                $familia = new Familia();

                $familia->id_empresa = $id_empresa;
                $familia->nombre     = $nombre;
                $familia->estado     = $estado;
                $respuesta = $familia->save();

            }

        }

        return;

    }

    private function familia_existe($id_empresa,$nombre,$id = 0) {

        $where = [ ['id_empresa', $id_empresa], ['nombre', $nombre], ['id', '<>', $id] ];

        $familias = DB::table('familias')->select('id')->where($where)->first();

        return $familias == null ? false : true;

    }
}
