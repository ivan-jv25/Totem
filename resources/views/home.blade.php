@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row ">


        @foreach ($bodegas as $b)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">Tienda : <span class="text-uppercase fw-bold">{{ $b->nombre }}</span></div>

                <div class="card-body">

                    

                    <form method="GET" action="{{ route('dashboard') }}" >
                        
                        <div class="d-grid gap-2">
                            <input type="text" name="tienda" value="{{$b->id_bodega}}" hidden>
                            <button class="btn btn-primary" type="submit">Ir a Tienda</button>
                            
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
        @endforeach


       

    </div>
</div>
@endsection
