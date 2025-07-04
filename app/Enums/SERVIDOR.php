<?php
namespace App\Enums;

class SERVIDOR
{
    public const PRODUCTIVO = 'http://onedte.cl/api/';
    public const DEV ="http://localhost/api/";
    

    public static function values(): array
    {
        return [
            self::PRODUCTIVO,
            self::DEV,
            
        ];
    }
}
