@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row ">

       

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
    
</script>

@endsection
