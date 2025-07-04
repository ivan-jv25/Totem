<?php
namespace App\Enums;

class SERVIDOR
{
    public const PRODUCTIVO = 'http://onedte.cl/api/';
    public const DEV ="http://127.0.0.1:8000/api/";
    

    public static function values(): array
    {
        return [
            self::PRODUCTIVO,
            self::DEV,
            
        ];
    }
}
