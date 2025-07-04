<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller{

    public function obtener_productos(Request $request){

        $pagina = (int)$request->pagina;
        $id_bodega = (int)$request->id_bodega;
        $buscador = (string)$request->buscador;

        
        $api =new APIController();
        $productos = $api->producto_paginado($pagina,$id_bodega, $buscador);

        
        if($productos['status']){
        
            foreach ($productos['data'] as $key => &$value) { 
                $value['url_local'] = ArchivosController::cacheImage($value['imagen']);
            }
            unset($value);
        }
        
        $lista = $productos['status'] ? $productos['data'] : [];

        return response()->json($productos);
    }
}
