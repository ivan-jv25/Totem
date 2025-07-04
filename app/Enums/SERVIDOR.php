<?php
namespace App\Enums;

class SERVIDOR
{
    public const PRODUCTIVO = 'http://onedte.cl/api/';
    // public const DEV = 'http://192.168.100.5/api/';
    public const DEV ="http://192.168.1.7/api/";
    

    public static function values(): array
    {
        return [
            self::PRODUCTIVO,
            self::DEV,
            
        ];
    }
}
