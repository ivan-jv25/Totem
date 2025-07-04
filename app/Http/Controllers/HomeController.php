<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use DB;
use App\Models\Bodega;
use App\Models\Banner;

use Illuminate\Support\Facades\Log;

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

        $bodegas = Bodega::select('id', 'nombre','token')->get();
        return view('home')->with('bodegas',$bodegas);
    }

    public function view_totem(Request $request){   
        
        $id_bodega = (int)$request->tienda;
        
        return view('totem.dashboard')->with('id_bodega',$id_bodega);
    }
    public function view_totem_shop(Request $request){


        $porcentaje = $request->porcentaje;
        $rut_cliente = $request->rut_cliente;
        $razon_social = $request->razon_social;
        $correo = $request->correo;

        $cliente = [
            'porcentaje'=>$porcentaje,
            'rut_cliente'=>$rut_cliente,
            'razon_social'=>$razon_social,
            'correo'=>$correo,
        ];
        $id_bodega = $request->id_bodega;

        $lista_banner = ImagenesController::obtenerUrlsDeImagenesBanner();
        $lista_categoria = $this->lista_categoria_aux();

        return view('totem.shop')->with('cliente',$cliente)->with('id_bodega',$id_bodega)->with('lista_banner',$lista_banner)->with('lista_categoria',$lista_categoria);

    }

    public function iniciar_totem(){
        

        // Rellena BD
        // $this->store_familia();
        // $this->store_sub_familia();
        $this->carga_bodega();
        // $this->store_forma_pago();


        echo "Pre Carga de Informacion lista";

    }

    public function giftcard_codigobarra(Request $request) {

        $codigo = (string)$request->codigo;

        $api = new APIController();


        $giftcard = $api->get_giftcard_codigobarra_api($codigo);

        if(!$giftcard['status']){ return $giftcard; }

        $giftcard_datos = $api->get_datos_giftcard($giftcard['token']);
        
        return (array)$giftcard_datos;
    }

    private static function carga_bodega(){
        try {
            $api = new APIController();
            $bodegas = $api->get_bodegas();
    
            if (empty($bodegas) || !is_array($bodegas)) {
                Log::warning('No se recibieron bodegas desde la API.');
                return;
            }
    
            // Obtener todos los id_bodega existentes de una sola vez
            $idsExistentes = DB::table('bodegas')->pluck('id as id_bodega')->toArray();
    
            foreach ($bodegas as $value) {
                $id_bodega = $value->id ?? null;
                $nombre = $value->descripcion ?? null;
    
                if (!$id_bodega || !$nombre) {
                    Log::warning('Bodega con datos incompletos: ' . json_encode($value));
                    continue;
                }
    
                if (!in_array($id_bodega, $idsExistentes)) {
                    $bodega = new Bodega();
                    $bodega->id = $id_bodega;
                    $bodega->nombre = $nombre;
                    $bodega->save();
                    Log::info("Bodega creada: $id_bodega - $nombre");
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error en carga_bodega: ' . $e->getMessage());
        }
    } 
    
    
    public function lista_categoria_aux(){
        return [
            (object)['id'=>1,  'nombre'=>'Bebidas',          'url'=>'https://placehold.co/150?text=Bebidas'],
            (object)['id'=>2,  'nombre'=>'Snacks',           'url'=>'https://placehold.co/150?text=Snacks'],
            (object)['id'=>3,  'nombre'=>'Lácteos',          'url'=>'https://placehold.co/150?text=Lácteos'],
            (object)['id'=>4,  'nombre'=>'Panadería',        'url'=>'https://placehold.co/150?text=Panadería'],
            (object)['id'=>5,  'nombre'=>'Congelados',       'url'=>'https://placehold.co/150?text=Congelados'],
            (object)['id'=>6,  'nombre'=>'Aseo Hogar',       'url'=>'https://placehold.co/150?text=Aseo+Hogar'],
            (object)['id'=>7,  'nombre'=>'Aseo Personal',    'url'=>'https://placehold.co/150?text=Aseo+Personal'],
            (object)['id'=>8,  'nombre'=>'Frutas',           'url'=>'https://placehold.co/150?text=Frutas'],
            (object)['id'=>9,  'nombre'=>'Verduras',         'url'=>'https://placehold.co/150?text=Verduras'],
            (object)['id'=>10, 'nombre'=>'Carnes',           'url'=>'https://placehold.co/150?text=Carnes'],
            (object)['id'=>11, 'nombre'=>'Pescados',         'url'=>'https://placehold.co/150?text=Pescados'],
            (object)['id'=>12, 'nombre'=>'Electrodomésticos','url'=>'https://placehold.co/150?text=Electrodomésticos'],
            (object)['id'=>13, 'nombre'=>'Mascotas',         'url'=>'https://placehold.co/150?text=Mascotas'],
            (object)['id'=>14, 'nombre'=>'Librería',         'url'=>'https://placehold.co/150?text=Librería'],
            (object)['id'=>15, 'nombre'=>'Tecnología',       'url'=>'https://placehold.co/150?text=Tecnología'],
        ];

    }
}
