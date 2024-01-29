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

        try {

            $URL = 'https://sandbox.onedte.com/api/login';
            $json = (object)[ 'empresa' => self::$empresa, 'username' => self::$username, 'password' => self::$password ];
            $json  = json_encode($json);

            $client = new \GuzzleHttp\Client();
            $response = $client->request('post', $URL, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => $json
            ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

            $token_ = $resultado[0]->Token;
            self::store_token($token_);

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_familia_api() {

        $resultado = false;

        $token_ = Configuraciones::find(1);

        try {

            $URL = 'https://sandbox.onedte.com/api/familia' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [
                'headers' => [
                    'token' => $token_->token,
                ],
            ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_sub_familia_api() {

        $resultado = false;

        $token_ = Configuraciones::find(1);

        try {

            $URL = 'https://sandbox.onedte.com/api/subfamilia' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [
                'headers' => [
                    'token' => $token_->token,
                ],
            ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_bodega_api() {

        $resultado = false;

        $token_ = Configuraciones::find(1);

        try {

            $URL = 'https://sandbox.onedte.com/api/bodega' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [
                'headers' => [
                    'token' => $token_->token,
                ],
            ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_forma_pago_api() {

        $resultado = false;

        $token_ = Configuraciones::find(1);

        try {

            $URL = 'https://sandbox.onedte.com/api/formapago' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [
                'headers' => [
                    'token' => $token_->token,
                ],
            ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_producto_paginado_api() {

        $resultado = false;

        $token_ = Configuraciones::find(1);

        $pagina = 1;
        $registros = 20;
        $bodega = 208;

        try {

            $URL = 'https://sandbox.onedte.com/api/productos/paginado/v2' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [
                'headers' => [
                    'token' => $token_->token,
                    'pagina' => $pagina,
                    'registros' => $registros,
                    'bodega' => $bodega,
                ],
            ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function get_producto_api() {

        $resultado = false;

        $token_ = Configuraciones::find(1);

        try {

            $URL = 'https://sandbox.onedte.com/api/inventario/ecommerce/1' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [
                'headers' => [
                    'token' => $token_->token,
                ],
            ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {

            //throw $th;
        }

        return $resultado;

    }

    public static function store_token($token_) {

        $configuracion = new Configuraciones();
        $configuracion->Token = $token_;
        $configuracion->save();

    }

}
