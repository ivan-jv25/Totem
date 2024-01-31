<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'id_producto',
        'nombre',
        'precio_venta',
        'precio_venta_neto',
        'codigo',
        'codigo_barra',
        'id_familia',
        'tipo',
        'tipo2',
        'exento',
        'imagen',
        'stock',
    ];

}
