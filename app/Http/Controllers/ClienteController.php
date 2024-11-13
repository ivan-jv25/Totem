<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request){

        $codigo = $request->codigo;

        return view('registro')->with('codigo',$codigo);
    }

    public function registro_cliente(Request $request){

        $rut_cliente = $request->rut;
        $nombre      = $request->nombre;
        $telefono    = $request->telefono;
        $correo      = $request->correo;
        $direccion   = $request->direccion;
        $codigo      = $request->codigo;

        $existe_cliente = \App\Http\Controllers\ApiController::existe_cliente($rut_cliente);

        if(!$existe_cliente->respuesta){

            $cliente = (object)[
                'Rut'         => $rut_cliente,
                'Razonsocial' => $nombre,
                'Giro'        => 'sin giro',
                'Direccion'   => $direccion,
                'Ciudad'      => 'ciudad',
                'Comuna'      => 'comuna',
                'Telefono'    => $telefono,
                'Correo'      => $correo,
            ];

            $response = \App\Http\Controllers\ApiController::crear_cliente($cliente);

        }


        $giftcard = \App\Http\Controllers\ApiController::generar_giftcard($rut_cliente, $codigo);


        return [
            'status' => $giftcard->status
        ];

    }
}
