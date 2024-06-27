<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Animación de Puerta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('totem/styles.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css " rel="stylesheet">
    <script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js "></script>


  </head>
  <body>
    
    <div class="container-fluid">
      <div class="row">
        <div class="col text-center btn-puerta">
          <img src="{{asset('totem/logo.png')}}" id="img_logo" class="img">
          <div class="input-group input-group-sm mb-3">
            <input type="text" id="input_code_cliente" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
          </div>
        </div>
      </div>
    </div>
        

    <div class="door-container h-100">
      <div class="door">
        <div class="door-left"></div>
        <div class="door-right"></div>
      </div>
    </div>

    
    <div id="contenido" class="container-fluid p-2 bgt"  style="display: none;">
      
      <div class="banner">
        <div id="carouselExampleIndicators" class="carousel slide  h-100" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item">
              <img class="d-block w-100" src="assets/images/big/img1.jpg" alt="Third slide">
            </div>
            <div class="carousel-item active">
              <img class="d-block w-100" src="assets/images/big/img2.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="assets/images/big/img3.jpg" alt="Second slide">
            </div>
          </div>
        </div>
      </div>
      
      <div class="listado bgt">
        <div class="col-12 d-flex align-items-stretch">
          <div class="card card-listado w-100 bgt">
            <div class="card-body mt-5">
              <div class="row">
                <h1 class="card-title text-center w-100">Bienvenido ! <span id="sp_nombre_cliente"></span></h1>
              </div>
              <div class="table-responsive">
                <table class="table table-striped  no-wrap v-middle">
                  <thead>
                    <tr class="border-0 text-center">
                      <th class="border-0 fs-2">ITEM</th>
                      <th colspan="2" class="border-0 fs-2">PRODUCTO</th>
                      <th class="border-0 fs-2">CANTIDAD</th>
                      <th class="border-0 fs-2">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody id="tbody_carro"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="pedido">
        <div class="col-12 d-flex ">
          <div class="card card-pedido w-100">
            <div class="card-body button-group d-flex ">
              <div class="mt-4">
                <p>Total : <span id="sp_total">$0.-</span></p>
              </div>
              <div class="ms-auto">
                <button type="button" id="btn_genera_pago" class="btn  btn-lg btn-rounded btn-secondary btn-pago my-1">Pagar $</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <input type="text" id="txt_buscador_producto">
    </div>


    <script>
      const TOKEN_SESSION = '{{csrf_token()}}';
      const URL_CONSULTA_CLIENTE = "{{route('api.giftcard.codigobarra')}}"
      const URL_CONSULTA_CODIGO = "{{route('api.producto.especifico')}}"
      const IMAGEN_DEFECTO = "{{asset('assets/images/sinimagen.png')}}"

      const ID_BODEGA = "{{$id_bodega}}"
    </script>
    <script src="{{asset('totem/script.js')}}"></script>
        
  </body>

</html>