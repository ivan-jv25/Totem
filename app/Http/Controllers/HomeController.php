<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Bodega;
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
        return $sub_familia;
    }

    public function probar_api_bodega() {
        $bodega = \App\Http\Controllers\ApiController::get_bodega_api();
        return $bodega;
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

            $id_familia = $value->id;
            $nombre = $value->nombre;
            $estado = $value->estado;

            $existe = self::familia_existe($id_familia);
            if (!$existe) {

                $familia = new Familia();

                $familia->id_familia = $id_familia;
                $familia->nombre     = $nombre;
                $familia->estado     = $estado;
                $respuesta = $familia->save();

            }

        }

        return;

    }

    public function store_sub_familia() {

        $sub_familia = $this->probar_api_sub_familia();

        $sub_familia = json_decode($sub_familia);

        foreach ($sub_familia as $key => $value) {

            $id_sub_familia = $value->id;
            $id_familia = $value->id_familia;
            $nombre = $value->nombre;
            $estado = $value->estado;

            $existe = self::sub_familia_existe($id_sub_familia);
            if (!$existe) {

                $sub_familia = new SubFamilia();

                $sub_familia->id_sub_familia = $id_sub_familia;
                $sub_familia->id_familia = $id_familia;
                $sub_familia->nombre     = $nombre;
                $sub_familia->estado     = $estado;
                $respuesta = $sub_familia->save();

            }

        }

        return;

    }

    public function store_bodega() {

        $bodega = $this->probar_api_bodega();

        $bodega = json_decode($bodega);

        foreach ($bodega as $key => $value) {

            $id_bodega = $value->id;
            $nombre = $value->descripcion;

            $existe = self::bodega_existe($id_bodega);
            if (!$existe) {

                $bodega = new Bodega();

                $bodega->id_bodega = $id_bodega;
                $bodega->nombre     = $nombre;
                $respuesta = $bodega->save();

            }

        }

        return;

    }

    private function familia_existe($id_familia) {

        $where = [ ['id_familia', $id_familia] ];

        $familias = DB::table('familias')->select('id')->where($where)->first();

        return $familias == null ? false : true;

    }

    private function sub_familia_existe($id_sub_familia) {

        $where = [ ['id_sub_familia', $id_sub_familia] ];

        $sub_familias = DB::table('sub_familias')->select('id')->where($where)->first();

        return $sub_familias == null ? false : true;

    }

    private function bodega_existe($id_bodega) {

        $where = [ ['id_bodega', $id_bodega] ];

        $bodega = DB::table('bodega')->select('id')->where($where)->first();

        return $bodega == null ? false : true;

    }
}
