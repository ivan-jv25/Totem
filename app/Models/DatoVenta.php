<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoVenta extends Model
{
    use HasFactory;

    protected $table = 'datos_venta';

    protected $fillable = [
        'estado',
        'numVenta',
    ];

}
