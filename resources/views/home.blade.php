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
                        @if($b->get_token != null)
                        <div class="d-grid gap-2 mb-3">
                            <input type="text" name="tienda" value="{{$b->id_bodega}}" hidden>
                            <button class="btn btn-primary" type="submit">Ir a Tienda</button>
                        </div>
                        @else
                        <div class="d-grid gap-2">
                            <a class="btn btn-secondary" onclick="modal_configuracion({{$b->id_bodega}})" >Configuracion</a>
                        </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

<div class="modal fade" id="ModalConfiguracion" tabindex="-1" aria-labelledby="ModalConfiguracionLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content ">

            <div class="modal-header">
                <h5 class="modal-title" id="ModalConfiguracionLabel">Configuracion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div style="text-align: center" class="modal-body payment-container">
                <p>Por favor, Ingrese sus credenciales</p>
                <div class="d-grid gap-2">

                    <div class="d-grid gap-2">
                        <input type="text" placeholder="Username"  id="id_correo_configuracion" autocomplete="off">
                    </div>

                    <div class="d-grid gap-2">
                        <input type="password" placeholder="Contraseña..." id="id_contrasena_configuracion">
                    </div>

                    <div class="d-grid gap-2">
                        <a id="btn_envio_data_bodega_token" class="btn btn-primary" >Enviar</a>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>


<script src="{{asset('totem/configuracion.js')}}"></script>

<script>
    const TOKEN_SESSION = '{{csrf_token()}}';
    const URL_TOKEN_BODEGA = "{{route('api.token.bodega')}}"
</script>

@endsection
