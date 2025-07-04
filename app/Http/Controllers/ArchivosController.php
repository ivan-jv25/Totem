<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ArchivosController extends Controller
{

    /**
     * Sube un archivo al disco público en la carpeta especificada
     *
     * Este método permite subir archivos al almacenamiento público del servidor,
     * organizándolos en carpetas específicas dentro del directorio assets.
     *
     * @param \Illuminate\Http\UploadedFile $file Archivo a subir (desde un Request)
     * @param string $filename Nombre del archivo (con extensión)
     * @param string $folder Carpeta donde se guardará (sin slashes iniciales ni finales)
     * @return object
     *
     * @example
     * // Subir un comprobante de pago
     * $resultado = ArchivosController::subir_archivo(
     *     $request->file('comprobante'),
     *     "comprobante_pago_{$id_pedido}.pdf",
     *     'comprobantes'
     * );
     *
     * // Subir una imagen de producto
     * $resultado = ArchivosController::subir_archivo(
     *     $request->file('imagen'),
     *     "producto_{$id_producto}.jpg",
     *     'productos/imagenes'
     * );
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si el archivo no es válido
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException Si hay problemas al leer el archivo
     */
    public static function subir_archivo($file, string $filename, string $folder) {    
        try {
            // Asegurar que el nombre de la carpeta no tenga slashes al inicio o final
            $folder = trim($folder, '/');

            // Construir la ruta relativa
            $path = "assets/{$folder}/{$filename}";

            // Guardar el archivo usando Storage
            $is_saved = Storage::disk('public')->put($path, File::get($file));

            if (!$is_saved) {
                Log::error("Error al guardar el archivo: {$filename} en {$folder}");
                return (object)['status' => false, 'url' => ''];
            }

            Log::info("Archivo subido exitosamente: {$path}");

            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
            $full_url = $protocol . $host . '/' . $path;

            return (object)['status' => true, 'url' => $full_url];

        } catch (\Throwable $th) {
            Log::error("Error al subir archivo {$filename}: " . $th->getMessage());
            return (object)['status' => false, 'url' => ''];
        }
    }

    /**
     * Modifica un archivo existente en el disco público
     *
     * Este método permite actualizar un archivo existente manteniendo su ubicación
     * original. Si el archivo no existe, retorna false.
     *
     * @param \Illuminate\Http\UploadedFile $file Nuevo archivo a subir
     * @param string $filename Nombre del archivo existente (con extensión)
     * @param string $folder Carpeta donde está guardado (sin slashes iniciales ni finales)
     * @return object
     *
     * @example
     * // Actualizar un comprobante de pago
     * $resultado = ArchivosController::modificar_archivo(
     *     $request->file('nuevo_comprobante'),
     *     "comprobante_pago_123.pdf",
     *     'comprobantes'
     * );
     *
     * // Actualizar una imagen de producto
     * $resultado = ArchivosController::modificar_archivo(
     *     $request->file('nueva_imagen'),
     *     "producto_456.jpg",
     *     'productos/imagenes'
     * );
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si el archivo no es válido
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException Si hay problemas al leer el archivo
     */
    public static function modificar_archivo($file, string $filename, string $folder) {
        try {
            $folder = trim($folder, '/');
            $path = "assets/{$folder}/{$filename}";

            // Verificar si el archivo existe
            if (!Storage::disk('public')->exists($path)) {
                Log::error("El archivo no existe: {$path}");
                (object)['status' => false, 'url' => ''];
            }

            // Reemplazar el archivo
            $is_updated = Storage::disk('public')->put($path, File::get($file));

            if (!$is_updated) {
                Log::error("Error al modificar el archivo: {$filename} en {$folder}");
                (object)['status' => false, 'url' => ''];
            }

            Log::info("Archivo modificado exitosamente: {$path}");

            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
            $full_url = $protocol . $host . '/' . $path;

            return (object)['status' => true, 'url' => $full_url];

        } catch (\Throwable $th) {
            Log::error("Error al modificar archivo {$filename}: " . $th->getMessage());
            return (object)['status' => false, 'url' => ''];
        }
    }

    /**
     * Elimina un archivo del disco público
     *
     * Este método permite eliminar un archivo existente del almacenamiento público.
     * Si el archivo no existe, retorna false.
     *
     * @param string $filename Nombre del archivo a eliminar (con extensión)
     * @param string $folder Carpeta donde está guardado (sin slashes iniciales ni finales)
     * @return bool true si el archivo se eliminó correctamente, false en caso contrario
     *
     * @example
     * // Eliminar un comprobante de pago
     * $resultado = ArchivosController::eliminar_archivo(
     *     "comprobante_pago_123.pdf",
     *     'comprobantes'
     * );
     *
     * // Eliminar una imagen de producto
     * $resultado = ArchivosController::eliminar_archivo(
     *     "producto_456.jpg",
     *     'productos/imagenes'
     * );
     */
    public static function eliminar_archivo(string $filename, string $folder): bool {
        try {
            $folder = trim($folder, '/');
            $path = "assets/{$folder}/{$filename}";

            // Verificar si el archivo existe
            if (!Storage::disk('public')->exists($path)) {
                Log::error("El archivo no existe: {$path}");
                return false;
            }

            // Eliminar el archivo
            $is_deleted = Storage::disk('public')->delete($path);

            if (!$is_deleted) {
                Log::error("Error al eliminar el archivo: {$filename} de {$folder}");
                return false;
            }

            Log::info("Archivo eliminado exitosamente: {$path}");
            return true;

        } catch (\Throwable $th) {
            Log::error("Error al eliminar archivo {$filename}: " . $th->getMessage());
            return false;
        }
    }

    public static function cacheImage($url){
        
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return asset('assets/img/sinimagen.png');
        }
    
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = md5($url) . '.' . $extension;
    
        // Verificar si ya está en caché
        if (\Cache::has("img_cache_{$filename}")) {
            return url("/cached-image/{$filename}");
        }
        
        $response = \Http::timeout(5)->get($url);
        
        if ($response->successful()) {
            
            $response = Storage::disk('public')->put("cached-image/{$filename}", $response->body());
            \Cache::put("img_cache_{$filename}", true, now()->addMinutes(60));
            return url("/cached-image/{$filename}");
        }
    
        return asset('assets/img/sinimagen.png');
    }
}
