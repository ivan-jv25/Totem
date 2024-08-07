<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Bodega;
use App\Models\FormaPago;
use App\Models\Producto;
use App\Models\DatoVenta;

use Validator;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $bodegas = Bodega::select('id_bodega', 'nombre')->get();
        

        return view('home')->with('bodegas',$bodegas);
    }

    public function go_to_tienda(Request $request){

        

        $id_bodega = $request->tienda;


        return view('dashboard')->with('id_bodega',$id_bodega);
    }

    public function iniciar_totem(){
        // Consigue un token de ingreso
        $this->probar_api_info_usuario();

        // Rellena BD
        // $this->store_familia();
        // $this->store_sub_familia();
        $this->store_bodega();
        // $this->store_forma_pago();


        echo "Pre Carga de Informacion lista";

    }



    public function probar_api_info_usuario() {
        $usuario = \App\Http\Controllers\ApiController::get_info_usuario_api();
        
        return $usuario;
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
        return $forma_pago;
    }

    public function probar_api_producto_paginado() {
        $producto_paginado = \App\Http\Controllers\ApiController::get_producto_paginado_api();
        return $producto_paginado;
    }

    public function probar_api_producto() {
        $producto = \App\Http\Controllers\ApiController::get_producto_api();
        return $producto;
    }

    public function probar_api_generar_venta() {
        $venta = \App\Http\Controllers\ApiController::generar_venta_api();
        return $venta;
    }

    public function probar_api_producto_especifico(Request $request) {

        $validator = Validator::make($request->all(), [ 'codigo_barra' => 'required|string|max:13', ]);
        if ($validator->fails()) { return new JsonResponse([ 'status'=>false, 'mensaje' => 'Favor revisar Datos Ingresados', 'error' => $validator->failed(), 'alert' => 'error' ], 500); }


        $codigo_barra = (string)$request->codigo_barra;
        $id_bodega = (int)$request->id_bodega;

        $prod_especifico = \App\Http\Controllers\ApiController::get_producto_especifico_api($codigo_barra, $id_bodega);

        $respuesta = count($prod_especifico) == 0 ? false : true;
        
        return new JsonResponse([ 'status'=>$respuesta, 'producto' => $prod_especifico ], 200);
    }

    public function probar_api_giftcard_codigobarra(Request $request) {
        $codigo = (string)$request->codigo;
        $giftcard_codigobarra = \App\Http\Controllers\ApiController::get_giftcard_codigobarra_api($codigo);

        if($giftcard_codigobarra == null){
            return [ 'status'=>false ];
        }
        

        $giftcard_datos = \App\Http\Controllers\ApiController::get_datos_giftcard($giftcard_codigobarra);

        if($giftcard_codigobarra == null){
            return [ 'status'=>false ];
        }

        return [
            'status'=>true,
            'data'=>$giftcard_datos
        ];
        
    }

    public function probar_api_giftcard_datos() {
        $giftcard_datos = \App\Http\Controllers\ApiController::get_datos_giftcard();
        return $giftcard_datos;
    }



    public function store_familia() {

        
        $familia = \App\Http\Controllers\ApiController::get_familia_api();
        

        foreach ($familia as $key => $value) {

            $id_familia = $value->id;
            $nombre = $value->nombre;
            $estado = $value->estado;

            $existe = self::familia_existe($id_familia);
            ($existe);
            if (!$existe) {

                $familia = new Familia();

                $familia->id_familia = $id_familia;
                $familia->nombre     = $nombre;
                $familia->estado     = $estado;
                $respuesta = $familia->save();

            } else {

                $familia = Familia::where('id_familia',$id_familia)->first();
                
                $familia->nombre     = $nombre;
                $familia->estado     = $estado;
                $respuesta = $familia->save();

            }

        }

        return;

    }

    public function store_sub_familia() {

        
        $sub_familia = \App\Http\Controllers\ApiController::get_sub_familia_api();

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

        
        $bodega = \App\Http\Controllers\ApiController::get_bodega_api();

        

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

    public function store_forma_pago() {

        $forma_pago = \App\Http\Controllers\ApiController::get_forma_pago_api();

        foreach ($forma_pago as $key => $value) {

            

            $id_forma_pago = $value->id;
            $nombre = $value->nombre;
            $sii = $value->sii;
            $estado = $value->estado;
            $efectivo = $value->efectivo == null ? 0 : 1;
            

            $existe = self::forma_pago_existe($id_forma_pago);
            if (!$existe) {

                $forma_pago = new FormaPago();

                $forma_pago->id_forma_pago = $id_forma_pago;
                $forma_pago->nombre        = $nombre;
                $forma_pago->sii           = $sii;
                $forma_pago->estado        = $estado;
                $forma_pago->efectivo      = $efectivo;
                $respuesta = $forma_pago->save();

            }

        }

        return;

    }

    public function store_producto() {

        $producto = $this->probar_api_producto();

        $producto = json_decode($producto);

        foreach ($producto as $key => $value) {

            $id_producto = $value->id;
            $nombre = $value->nombre;
            $precio_venta = $value->precio_venta;
            $precio_venta_neto = $value->precio_venta_neto;
            $codigo = $value->codigo;
            $codigo_barra = $value->codigo_barra;
            $id_familia = $value->id_familia;
            $tipo = $value->tipo;
            $tipo2 = $value->tipo2;
            $exento = $value->exento;
            $imagen = $value->imagen;
            $stock = $value->stock;

            $existe = self::producto_existe($id_producto);
            if (!$existe) {

                $producto = new Producto();

                $producto->id_producto = $id_producto;
                $producto->nombre = $nombre;
                $producto->precio_venta = $precio_venta;
                $producto->precio_venta_neto = $precio_venta_neto;
                $producto->codigo = $codigo;
                $producto->codigo_barra = $codigo_barra;
                $producto->id_familia = $id_familia;
                $producto->tipo = $tipo;
                $producto->tipo2 = $tipo2;
                $producto->exento = $exento;
                $producto->imagen = $imagen;
                $producto->stock = $stock;

                $respuesta = $producto->save();

            }

        }

        return;

    }

    public function store_datos_venta() {

        $venta = $this->probar_api_generar_venta();

        $venta = json_decode($venta);

        $estado = $venta->estado;
        $numVenta = $venta->numVenta;

        $datos_venta = new DatoVenta();
        $datos_venta->estado = $estado;
        $datos_venta->numVenta = $numVenta;
        $respuesta = $datos_venta->save();

        return;

    }



    private function familia_existe($id_familia) {

        $where = [ ['id_familia', $id_familia] ];

        $familias = DB::table('familias')->select('id')->where($where)->first();

        return $familias != null ;

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

    private function forma_pago_existe($id_forma_pago) {

        $where = [ ['id_forma_pago', $id_forma_pago] ];

        $forma_pago = DB::table('forma_pago')->select('id')->where($where)->first();

        return $forma_pago == null ? false : true;

    }

    private function producto_existe($id_producto) {

        $where = [ ['id_producto', $id_producto] ];

        $producto = DB::table('productos')->select('id')->where($where)->first();

        return $producto == null ? false : true;

    }
    
}
