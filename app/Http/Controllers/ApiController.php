<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuraciones;
use App\Models\Token;
use DB;

class ApiController extends Controller
{

    // public static $endpoint = 'http://onedte.cl/';
    public static $endpoint = 'http://192.168.1.101/';

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

    public function login_usuario(Request $request){

        $empresa  =  config('app.AUTH')['EMPRESA_TEST'];
        $username = $request->username;
        $password = $request->password;
        $id_bodega_utilizar = (int)$request->id_bodega;


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
            $id_bodega = (int)$resultado[0]->id_bodega;

            if($id_bodega != $id_bodega_utilizar){ return [ 'status'=>false ]; }

            $token = Token::select()->where('id_bodega', $id_bodega)->first();

            if($token == null){
                $token = new Token();
                $token->id_bodega = $id_bodega;
                $token->token = $token_;
            }else{
                $token->token = $token_;
            }
            $token->save();


            return [ 'status'=>true ]; 

        } catch (\Throwable $th) { dd($th); }

        return [ 'status'=>false ]; 
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

    public static function generar_venta_api(Request $request) {

        try { date_default_timezone_set("America/Santiago"); } catch (\Throwable $th) { }

        // dd($request);
        $response = false;
        $id_bodega = (int)$request->id_bodega;

        $token_ = self::get_token_by_bodega($id_bodega);
        

        if ($token_ == null) {
            self::get_info_usuario_api();
            $token_ = self::get_value('token');
        }

        // dd($token_);
        
        $cliente = $request->cliente;
        $detalle_compra = $request->detalle;
        $montos = $request->montos;

        $fecha_vencimiento = date("Y-m-d");
        $dia = date('d');
        $mes = date('m');
        $anio = date('Y');
        $mes = (strlen($mes) == 1) ? "0" . $mes : $mes;
        $dia = (strlen($dia) == 1) ? "0" . $dia : $dia;

        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'token' => $token_,
            'id_bodega' => $id_bodega
        ];

        $encabezado = (object)[
            "Cliente" => $cliente['rut_cliente'],
            "Documento" => 39,
            "Estado" => 1,
            "Observacion" => "",
            "formapago" => 1,
            "Bodega" => $id_bodega,
            "Dia" => $dia,
            "Mes" => $mes,
            "Anio" => $anio,
            "FechaVencimiento" => $fecha_vencimiento,
            "Propina" => 0,
            "Total" => $montos['total'],
            "IVA" => $montos['iva'],
            "NetoExento" => 0,
            "NetoAfecto" => $montos['neto'],
            "Descuento" => 0
        ];
        
        $detalle = [];

        $count = 1;
        foreach ($detalle_compra as $key => $d) {
            $detalle_aux = (object)[
                "Item" => $count,
                "Codigo" => $d['codigo'],
                "id_producto" => $d['id'],
                "Precio" => $d['precio_venta'],
                "Cantidad" => $d['cantidad'],
                "Descuento" => 0,
                "Detallelargo" => "",
                "Total" => $d['total'],
            ];

            $count++;

            array_push($detalle, $detalle_aux);
        }

        $pago = [
            (object)[
                "Formapago" => 1,
                "Total" => $montos['total']
            ]
        ];

        $json = (object)[ 'Encabezado' => $encabezado, 'Detalle' => $detalle, 'Pago' => $pago ];
        $json  = json_encode($json);
        
        try {
            $URL = self::$endpoint.'api/venta';
            $client = new \GuzzleHttp\Client();
            $response = $client->request('post', $URL, [ 'headers' => $header, 'body' => $json ]);

            $response = $response->getBody()->getContents();

            $response = json_decode($response);

        } catch (\Throwable $th) {
            //throw $th;
        }

        return [
            'status'=>true,
            'fecha'=>date("Y-m-d"),
            'hora'=>date("H:i"),
            'dte'=>$response
        ];

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

            $resultado = null;
            //throw $th;
        }

        return $resultado;

    }

    public static function get_datos_giftcard(string $token_giftcard) {

        $resultado = null;

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
            $resultado = null;
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

    private function get_token_by_bodega(int $id_bodega){

        $where = [ ['id_bodega', $id_bodega] ];
        $configuracion = DB::table('bodega_token')->select('token')->where($where)->first();
        return $configuracion == null ? null : $configuracion->token;

    }



}

