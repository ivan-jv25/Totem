<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCachedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina las imágenes cacheadas en public/cached-image';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){

        $directory = public_path('cached-image');
        if (!is_dir($directory)) {
            $this->info("El directorio no existe: $directory");
            return;
        }

        $files = glob($directory . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        $this->info('Imágenes en caché eliminadas correctamente.');
    }

}
