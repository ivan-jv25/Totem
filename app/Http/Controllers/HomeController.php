<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Bodega;
use App\Models\FormaPago;
use App\Models\Producto;
use App\Models\DatoVenta;

use Validator;
use DB;

use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $bodegas = Bodega::select('id_bodega', 'nombre')->get();


        return view('home')->with('bodegas',$bodegas);
    }

    



}
