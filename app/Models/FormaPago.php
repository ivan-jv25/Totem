<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    use HasFactory;

    protected $table = 'forma_pago';

    protected $fillable = [
        'id_forma_pago',
        'nombre',
        'sii',
        'estado',
        'efectivo',
    ];

}
