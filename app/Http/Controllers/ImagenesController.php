<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class ImagenesController extends Controller
{
    public function index(){
        return view('admin.imagenes');
    }

    public function subir_logo(Request $request){
        // Validar que se haya subido un archivo
        $request->validate([
            'logo' => 'required|image|max:2048'
        ]);
        
        $archivo = $request->file('logo');
        $extension = $archivo->getClientOriginalExtension(); // Obtiene la extensión original
        $filename = 'logo_redondo.' . $extension;

        $archivoController = new ArchivosController();
        $ruta = $archivoController->modificar_archivo($archivo, $filename, 'img/logo');
        
        return back()->with('success', 'Logo subido correctamente.');
    }

    public function subir_logo_principal(Request $request){
        // Validar que se haya subido un archivo
        $request->validate([
            'logo_principal' => 'required|image|max:2048'
        ]);
        
        $archivo = $request->file('logo_principal');
        $extension = $archivo->getClientOriginalExtension(); // Obtiene la extensión original
        $filename = 'logo_principal.' . $extension;

        $archivoController = new ArchivosController();
        $ruta = $archivoController->modificar_archivo($archivo, $filename, 'img/logo');

        
        
        return back()->with('success', 'Logo subido correctamente.');
    }

    public function subir_banner(Request $request){

        $request->validate([
            'banner' => 'required|image|max:2048'
        ]);
        
        $archivo = $request->file('banner');
        $extension = $archivo->getClientOriginalExtension();
        
        // Obtiene el nombre original, lo limpia y le agrega la extensión
        $originalName = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName) . '.' . $extension;
        
        $archivoController = new ArchivosController();
        $ruta = $archivoController->subir_archivo($archivo, $filename, 'img/banner');

        if($ruta->status){
            
            $banner = new Banner();
            $banner->url = $ruta->url;
            $banner->save();
        }
        return back()->with('success', 'Banner subido correctamente.');
    }

    public function eliminar_banner(Request $request){

        $id_banner = $request->id_banner;
        $banner = Banner::find($id_banner);

        if($banner == null){ return response()->json(['status'=>false, 'mansaje'=>'Id del banner no encontrado']); }
        
        $path = parse_url($banner->url, PHP_URL_PATH);
        $relativePath = ltrim(str_replace('/assets/', '', $path), '/');
        $filename = pathinfo($relativePath, PATHINFO_BASENAME);
        $folder = pathinfo($relativePath, PATHINFO_DIRNAME);
        
        $archivoController = new ArchivosController();
        $status = $archivoController->eliminar_archivo($filename, $folder);

        if($status){
            $banner->delete();
        }

        return response()->json(['status'=>true, 'mansaje'=>'banner eliminado con Exito!']);
        
    }

    public function lista_banner(){

        $banners = Banner::select('id', 'url')->get();

        return response()->json([
            'status' => true,
            'data' => $banners
        ]);
    }
        
    
    
    
        

}
