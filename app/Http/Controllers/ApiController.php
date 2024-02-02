<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuraciones;
use DB;

class ApiController extends Controller
{

    static $empresa  = null;
    static $username = null;
    static $password = null;

    public static function get_info_usuario_api() {

        $resultado = false;

        self::$empresa  = env('EMPRESA_TEST');
        self::$username = env('USERNAME_TEST');
        self::$password = env('PASSWORD_TEST');

        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        try {

            $URL = 'https://sandbox.onedte.com/api/login';
            $json = (object)[ 'empresa' => self::$empresa, 'username' => self::$username, 'password' => self::$password ];
            $json  = json_encode($json);

            $client = new \GuzzleHttp\Client();
            $response = $client->request('post', $URL, [ 'headers' => $header, 'body' => $json ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

            $token_ = $resultado[0]->Token;
            $key = 'ingreso';
            self::store_token($token_, $key);

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_familia_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $header = [ 'token' => $token_->token, ];

        try {

            $URL = 'https://sandbox.onedte.com/api/familia' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_sub_familia_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $header = [ 'token' => $token_->token, ];

        try {

            $URL = 'https://sandbox.onedte.com/api/subfamilia' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_bodega_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $header = [ 'token' => $token_->token, ];

        try {

            $URL = 'https://sandbox.onedte.com/api/bodega' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_forma_pago_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $header = [ 'token' => $token_->token, ];

        try {

            $URL = 'https://sandbox.onedte.com/api/formapago' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_producto_paginado_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $pagina = 1;
        $registros = 20;
        $bodega = 1;

        $header = [
            'token' => $token_->token,
            'pagina' => $pagina,
            'registros' => $registros,
            'bodega' => $bodega,
        ];

        try {

            $URL = 'https://sandbox.onedte.com/api/productos/paginado/v2' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_producto_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $header = [ 'token' => $token_->token, ];

        try {

            $URL = 'https://sandbox.onedte.com/api/inventario/ecommerce/1' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function generar_venta_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $bodega = 1;

        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'token' => $token_->token,
            'id_bodega' => $bodega
        ];

        try {

            $URL = 'https://sandbox.onedte.com/api/venta';

            $encabezado = (object)[
                "Cliente" => "66666666-6",
                "Documento" => 39,
                "Estado" => 1,
                "Observacion" => "",
                "formapago" => 1,
                "Bodega" => 1,
                "Dia" => "03",
                "Mes" => "11",
                "Anio" => 2023,
                "FechaVencimiento" => "2023-11-03",
                "Propina" => 0,
                "Total" => 2000,
                "IVA" => 319,
                "NetoExento" => 0,
                "NetoAfecto" => 1681,
                "Descuento" => 0
            ];

            $detalle = [
                (object)[
                    "Item" => 1,
                    "Codigo" => "L0002",
                    "id_producto" => 9,
                    "Precio" => 1000,
                    "Cantidad" => 1,
                    "Descuento" => 0,
                    "Detallelargo" => "",
                    "Total" => 1000
                ],
                (object)[
                    "Item" => 2,
                    "Codigo" => "L0003",
                    "id_producto" => 10,
                    "Precio" => 100,
                    "Cantidad" => 10,
                    "Descuento" => 0,
                    "Detallelargo" => "",
                    "Total" => 1000
                ]
            ];

            $pago = [
                (object)[
                    "Formapago" => 1,
                    "Total" => 1000
                ]
            ];

            $json = (object)[ 'Encabezado' => $encabezado, 'Detalle' => $detalle, 'Pago' => $pago ];
            $json  = json_encode($json);

            $client = new \GuzzleHttp\Client();
            $response = $client->request('post', $URL, [ 'headers' => $header, 'body' => $json ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_producto_especifico_api() {

        $resultado = false;

        $key = 'ingreso';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $codigo = 6915566000033;
        $tipo = "barra";
        $idbodega = null;
        $idbodega = 1;

        $headersinbodega = [
            'token' => $token_->token,
            'codigo' => $codigo,
            'tipo' => $tipo,
        ];

        $headerbodega = [
            'token' => $token_->token,
            'codigo' => $codigo,
            'tipo' => $tipo,
            'idbodega' => $idbodega,
        ];

        $header = $idbodega == null ? $headersinbodega : $headerbodega;

        try {

            $URL = 'https://sandbox.onedte.com/api/get/producto/especifico' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_giftcard_codigobarra_api() {

        $resultado = false;

        //tiene que cambiar a ingreso la key
        $key = 'prod';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }

        $codigo = 189279027;

        $header = [
            'token' => $token_->token,
            'codigo' => $codigo,
        ];

        try {

            $URL = 'http://onedte.cl/api/giftcard/codigobarra' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

            $token_ = $resultado->token;
            $key = 'giftcard';
            self::store_token($token_, $key);


        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_datos_giftcard() {

        $resultado = false;

        //tiene que cambiar a ingreso la key
        $key = 'prod';
        $token_ = self::get_token($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_token($key);
        }


        $key = 'giftcard';
        $giftcard = self::get_token($key);

        if ($giftcard == null) {
            self::get_giftcard_codigobarra_api();
            $giftcard = self::get_token($key);
        }

        $header = [
            'token' => $token_->token,
            'giftcard' => $giftcard->token,
        ];

        try {

            $URL = 'http://onedte.cl/api/giftcard/token' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    private static function store_token($token_, $key) {

        $token = self::get_token($key);

        if($token == null) {
            $configuracion = new Configuraciones();
            $configuracion->token = $token_;
            $configuracion->key = $key;
            $configuracion->save();
        }

    }

    private static function get_token($key){
        $where = [ ['key', $key] ];
        $configuracion = DB::table('configuraciones')->select('token')->where($where)->first();
        return $configuracion;
    }

}
