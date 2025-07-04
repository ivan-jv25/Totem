<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Enums\SERVIDOR;
use App\Models\Configuraciones;
use App\Models\Bodega;
use DB;


class ApiController extends Controller{

    
    public static $endpoint = SERVIDOR::DEV;
    protected $token        = '';
    protected $rut_empresa  = '';
    

    public function __construct(){
        $this->token       = config('app.TOKEN_API');
        $this->rut_empresa = config('app.EMPRESA');
    }


    public function producto_paginado(int $pagina,int $id_bodega, string $buscador = '' , int $registros = 20){

        $client = new Client();

        $URL = self::$endpoint . 'productos/paginado/v2';
        

        try {
            $response = $client->request('GET',$URL, [
                'headers' => [
                    'token'        =>  $this->token,
                    'pagina'       => $pagina,
                    'registros'    => $registros,
                    'subfamilia'   => 0,
                    'buscador'     => $buscador,
                    'bodega'       => $id_bodega,
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);

            return [
                'status' => true,
                'data' => $body
            ];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 500;
            $message = $response ? $response->getBody()->getContents() : $e->getMessage();

            return[
                'status' => false,
                'error' => 'Error al consumir la API externa',
                'detalle' => $message
            ];
        }
    }

    public function generar_venta($json, $id_bodega){

        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'token' => $this->token,
            'id_bodega' => $id_bodega
        ];

        $response = null;
        $status = false;

        try {

            $URL = $this->url . 'venta';
            $json  = json_encode($json);

            $client = new Client();
            $response = $client->request('post', $URL, [ 'headers' => $header, 'body' => $json ]);
            $response = json_decode($response->getBody()->getContents(), true);

            $status = true;
            
        } catch (\Throwable $th) {
            //throw $th;
        }

        return [
            'status'=>$status,
            'fecha'=>date("Y-m-d"),
            'hora'=>date("H:i"),
            'dte'=>$response
        ];
    }

    public function get_bodegas() {

        $resultado = [];

        

        $header = [ 'token' => $this->token, ];

        try {

            $URL = self::$endpoint.'bodega' ;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('get',$URL, [ 'headers' => $header ]);

            $resultado = $response->getBody()->getContents();
            $resultado = json_decode($resultado);

        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        return $resultado;

    }

    public function login_usuario(Request $request){
        
        $empresa  = $this->rut_empresa;
        $username = $request->username;
        $password = $request->password;
        $id_bodega_utilizar = (int)$request->id_bodega;
    
        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        try {
            
            $URL = self::$endpoint . 'login';
            $json = json_encode([ 'empresa'  => $empresa, 'username' => $username, 'password' => $password ]);
    
            $client = new \GuzzleHttp\Client();
            $response = $client->request('post', $URL, [
                'headers' => $header,
                'body'    => $json
            ]);
    
            $resultado = json_decode($response->getBody()->getContents());
    
            if (!is_array($resultado) || empty($resultado[0]->Token) || empty($resultado[0]->id_bodega)) {
                \Log::warning('Respuesta inesperada al intentar login_usuario', [ 'response' => $resultado, 'request'  => $request->all() ]);
                return [ 'status' => false, 'mensaje' => 'Respuesta inesperada de la API.' ];
            }
    
            $token_    = $resultado[0]->Token;
            $id_bodega = (int)$resultado[0]->id_bodega;

            if ($id_bodega !== $id_bodega_utilizar) {
                \Log::warning('El id_bodega recibido no coincide con el solicitado.', [ 'id_bodega_api' => $id_bodega, 'id_bodega_utilizar' => $id_bodega_utilizar ]);
                return [ 'status' => false, 'mensaje' => 'La bodega no coincide.' ];
            }
            
            $bodega = Bodega::find($id_bodega);

            if (!$bodega) {
                \Log::error('No se encontró la bodega en la base de datos.', [ 'id_bodega' => $id_bodega ]);
                return [ 'status' => false, 'mensaje' => 'Bodega no encontrada.' ];
            }

            $bodega->token = $token_;
            $bodega->save();

            \Log::info('Login exitoso y token guardado para bodega.', [ 'id_bodega' => $id_bodega, 'usuario'   => $username ]);

            return [ 'status' => true ];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            $mensaje = $response ? $response->getBody()->getContents() : $e->getMessage();
            \Log::error('Error de conexión al intentar login_usuario', [ 'exception' => $mensaje, 'request'   => $request->all() ]);
            return [ 'status' => false, 'mensaje' => 'Error de conexión con la API.' ];
        } catch (\Throwable $th) {
            \Log::error('Excepción inesperada en login_usuario', [ 'exception' => $th->getMessage(), 'request'   => $request->all() ]);
            return [ 'status' => false, 'mensaje' => 'Error inesperado.' ];
        }
    }

    public function get_giftcard_codigobarra_api(string $codigo){

        $header = [
            'token'  => $this->token,
            'codigo' => $codigo,
        ];
    
        try {
            $URL = self::$endpoint . 'giftcard/codigobarra';
            $client = new \GuzzleHttp\Client();
            $response = $client->request('get', $URL, [ 'headers' => $header ]);
    
            $body = $response->getBody()->getContents();
            $json = json_decode($body);
    
            if (isset($json->token) && !empty($json->token)) {
                return [
                    'status' => true,
                    'token'  => $json->token
                ];
            } else {
                \Log::warning('Respuesta inesperada en get_giftcard_codigobarra_api', [ 'codigo' => $codigo, 'response' => $body ]);
                return [
                    'status' => false,
                    'mensaje' => 'Token de giftcard no encontrado.'
                ];
            }
        } catch (\Throwable $th) {
            \Log::error('Error en get_giftcard_codigobarra_api', [ 'codigo' => $codigo, 'exception' => $th->getMessage() ]);
            return [
                'status' => false,
                'mensaje' => 'Error al consultar la API de giftcard.'
            ];
        }
    }
    
    public function get_datos_giftcard(string $token_giftcard){

        $header = [
            'token'    => $this->token,
            'giftcard' => $token_giftcard,
        ];
    
        try {
            $URL = self::$endpoint . 'giftcard/token';
            $client = new \GuzzleHttp\Client();
            $response = $client->request('get', $URL, [ 'headers' => $header ]);
    
            $body = $response->getBody()->getContents();
            $json = json_decode($body);
    
            if ($json) {
                return [
                    'status' => true,
                    'data'   => $json
                ];
            } else {
                \Log::warning('Respuesta inesperada en get_datos_giftcard', [ 'token_giftcard' => $token_giftcard, 'response' => $body ]);
                return [
                    'status' => false,
                    'mensaje' => 'No se encontraron datos para la giftcard.'
                ];
            }
        } catch (\Throwable $th) {
            \Log::error('Error en get_datos_giftcard', [ 'token_giftcard' => $token_giftcard, 'exception' => $th->getMessage() ]);
            return [
                'status' => false,
                'mensaje' => 'Error al consultar la API de datos de giftcard.'
            ];
        }
    }
}






