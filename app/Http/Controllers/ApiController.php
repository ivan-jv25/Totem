<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuraciones;
use DB;

class ApiController extends Controller
{

    public static $endpoint = 'http://192.168.1.7/';

    public static function get_info_usuario_api() {

        $resultado = null;


        $empresa  =  config('app.AUTH')['EMPRESA_TEST'];
        $username  =  config('app.AUTH')['USERNAME_TEST'];
        $password  =  config('app.AUTH')['PASSWORD_TEST'];

        $header = [ 'Content-Type' => 'application/json', 'Accept' => 'application/json', ];

        try {

            $URL = self::$endpoint.'api/login';
            $json = (object)[ 'empresa' => $empresa, 'username' => $username, 'password' => $password ];
            $json  = json_encode($json);

            $client = new \GuzzleHttp\Client();
            $response = $client->request('post', $URL, [ 'headers' => $header, 'body' => $json ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

            $token_    = $resultado[0]->Token;
            $id_bodega = $resultado[0]->id_bodega;
            
            self::store_value($token_, 'token');
            self::store_value($id_bodega, 'id_bodega');

        } catch (\Throwable $th) { }

        return $resultado;
    }

    public static function get_familia_api() {

        $resultado = [];
        $token = self::get_value('token');
        
        if ($token == null) {
            self::get_info_usuario_api();
            $token = self::get_value($key);
        }

        $header = [ 'token' => $token, ];

        try {

            $URL = self::$endpoint.'api/familia' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);
        } catch (\Throwable $th) { }
        return $resultado;
    }

    public static function get_sub_familia_api() {

        $resultado = [];

        $key = 'token';
        $token_ = self::get_value($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value($key);
        }

        

        

        $header = [ 'token' => $token_, ];

        try {

            $URL = self::$endpoint.'api/subfamilia' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        
        } catch (\Throwable $th) {
            //throw $th;
            
        }
        return $resultado;

    }

    public static function get_bodega_api() {

        $resultado = [];

        $key = 'token';
        $token_ = self::get_value($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value($key);
        }

        $header = [ 'token' => $token_, ];

        try {

            $URL = self::$endpoint.'api/bodega' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_forma_pago_api() {

        $resultado = [];

        $key = 'token';
        $token_ = self::get_value($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value($key);
        }

        $header = [ 'token' => $token_, ];

        try {

            $URL = self::$endpoint.'api/formapago' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $resultado;

    }

    public static function get_producto_paginado_api() {

        $resultado = false;

        $key = 'token';
        $token_ = self::get_value($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value($key);
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

            $URL = $endpoint.'api/productos/paginado/v2' ;

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

        $key = 'token';
        $token_ = self::get_value($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value($key);
        }

        $header = [ 'token' => $token_->token, ];

        try {

            $URL = $endpoint.'api/inventario/ecommerce/1' ;

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

        $key = 'token';
        $token_ = self::get_value($key);

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value($key);
        }

        $bodega = self::get_value('id_bodega');

        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'token' => $token_->token,
            'id_bodega' => $bodega
        ];

        try {

            $URL = $endpoint.'api/venta';

            $encabezado = (object)[
                "Cliente" => "66666666-6",
                "Documento" => 39,
                "Estado" => 1,
                "Observacion" => "",
                "formapago" => 1,
                "Bodega" => $bodega,
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

    public static function get_producto_especifico_api(string $codigo, int $id_bodega) {

        $resultado = false;

        
        $token_ = self::get_value('token');

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value('token');
        }

        
        $tipo = "barra";
        
        $header = [
            'token' => $token_,
            'codigo' => $codigo,
            'tipo' => $tipo,
            'idbodega' => $id_bodega,
        ];

        try {

            $URL = self::$endpoint.'api/get/producto/especifico' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            if($response->getStatusCode() == 200){ $resultado = json_decode($response->getBody()->getContents(),TRUE); }
            
        } catch (\Throwable $th) { }

        return $resultado;
    }

    public static function get_giftcard_codigobarra_api(string $codigo) {

        $resultado = false;

        //tiene que cambiar a token la key
        
        $token_ = self::get_value('token');

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value('token');
        }

        //$codigo = 189279027;

        $header = [
            'token' => $token_,
            'codigo' => $codigo,
        ];

        try {

            $URL = self::$endpoint.'api/giftcard/codigobarra' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

            

            $resultado = $resultado->token;
            // $key = 'giftcard';
            // self::store_value($token_, $key);


        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_datos_giftcard(string $token_giftcard) {

        $resultado = false;

        $token_ = self::get_value('token');

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value('token');
        }

        $header = [
            'token' => $token_,
            'giftcard' => $token_giftcard,
        ];

        try {

            $URL = self::$endpoint.'api/giftcard/token' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();

            $resultado = json_decode($resultado);
            

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    private static function store_value($value, $key) {

        $configuracion = Configuraciones::where('key', $key)->first();
        if($configuracion == null){ $configuracion = new Configuraciones(); }

        $configuracion->token = $value;
        $configuracion->key = $key;
        $configuracion->save();
    }

    private static function get_value($key){
        $where = [ ['key', $key] ];
        $configuracion = DB::table('configuraciones')->select('token')->where($where)->first();
        return $configuracion == null ? null : $configuracion->token;
    }

}
