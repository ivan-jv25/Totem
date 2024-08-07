<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;

    protected $table = 'bodega';

    protected $fillable = [
        'id_bodega',
        'nombre',
    ];


    public function get_token(){
        return $this->hasOne('App\Models\Token','id_bodega','id_bodega');
    }

}
