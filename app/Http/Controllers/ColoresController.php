<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ColoresController extends Controller
{
    public function index(){
    $path = public_path('assets/css/variables.css');
    $colores = [
        'espresso' => '#3D281c',
        'body'     => '#bf8969',
        'crema'    => '#DFBB96',
        'foam'     => '#f4ece6',
    ];

    if (file_exists($path)) {
        $css = file_get_contents($path);
        foreach ($colores as $nombre => $default) {
            if (preg_match('/--color-' . preg_quote($nombre) . '\s*:\s*(#[A-Fa-f0-9]{6})\s*;/', $css, $matches)) {
                $colores[$nombre] = $matches[1];
            }
        }
    }

    return view('admin.colores', compact('colores'));
}


    public function update(Request $request){
        
        // Recibe los nuevos valores desde el formulario o request
        $espresso = $request->input('espresso', '#3D281c');
        $body     = $request->input('body', '#bf8969');
        $crema    = $request->input('crema', '#DFBB96');
        $foam     = $request->input('foam', '#f4ece6');
        
        // Construye el contenido del archivo CSS
        $css = <<<CSS
        :root {
            --color-espresso: {$espresso};
            --color-body    : {$body};
            --color-crema   : {$crema};
            --color-foam    : {$foam};
        }
        CSS;
        
        // Ruta al archivo variables.css
        $path = public_path('assets/css/variables.css');

        // Sobrescribe el archivo
        file_put_contents($path, $css);
        return back()->with('success', 'Colores actualizados correctamente.');
    }


}
