@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row ">
        @if(count($bodegas) == 0)
            <div class="col-12">
                <div class="alert alert-warning text-center my-5">
                    <strong>No hay bodegas cargadas.</strong> Por favor, realiza la carga de bodegas para continuar.
                </div>
            </div>
        @endif

       @foreach ($bodegas as $b)
       <div class="col-md-4 mb-3">
           <div class="card">
               <div class="card-header d-flex align-items-center justify-content-between">
                   <span>
                       Tienda : <span class="text-uppercase fw-bold">{{ $b->nombre }}</span>
                    </span>
                    @if($b->token != null)
                    <span class="estado-circulo bg-success" title="Token configurado"></span>
                    @else
                    <span class="estado-circulo bg-danger" title="Sin token"></span>
                    @endif
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('totem.index') }}" >
                        @if($b->token != null)
                        <div class="d-grid gap-2 mb-3">
                            <input type="text" name="tienda" value="{{$b->id}}" hidden>
                            <button class="btn btn-primary" type="submit">Ir a Tienda</button>
                        </div>
                        @else
                        <div class="d-grid gap-2">
                            <a class="btn btn-secondary" onclick="modal_configuracion({{$b->id}})" >Configuracion</a>
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
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="ModalConfiguracionLabel">
                    <i class="fa-solid fa-gear me-2"></i> Configuración de Bodega
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-4 px-4">
                <div class="text-center mb-3">
                    <i class="fa-solid fa-user-lock fa-2x text-primary mb-2"></i>
                    <h6 class="mb-2">Ingrese sus credenciales para configurar la bodega</h6>
                </div>
                <form autocomplete="off">
                    <div class="mb-3">
                        <label for="id_correo_configuracion" class="form-label fw-semibold">
                            <i class="fa-solid fa-user"></i> Usuario
                        </label>
                        <input type="text" class="form-control form-control-lg" placeholder="Usuario" id="id_correo_configuracion" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="id_contrasena_configuracion" class="form-label fw-semibold">
                            <i class="fa-solid fa-lock"></i> Contraseña
                        </label>
                        <input type="password" class="form-control form-control-lg" placeholder="Contraseña" id="id_contrasena_configuracion">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" id="btn_envio_data_bodega_token" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-paper-plane"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i> Cerrar
                </button>
            </div>

        </div>
    </div>
</div>


<script src="{{asset('js/configuracion.js')}}"></script>

<script>
    const TOKEN_SESSION = '{{csrf_token()}}';
    const URL_TOKEN_BODEGA = "{{route('api.token.bodega')}}"
    
</script>

@endsection
