<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $filename = 'logo.' . $extension;

        $archivoController = new ArchivosController();
        $ruta = $archivoController->modificar_archivo($archivo, $filename, 'img/logo');
        
        return back()->with('success', 'Logo subido correctamente.');
    }
        
    
    
        
        

}
