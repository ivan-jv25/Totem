<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Totem</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('totem/styles.css?ver=1.1.4')}}">
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
            <!-- <input type="text" id="input_code_cliente" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"> -->
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
                <h1 class="card-title text-center w-100">¡Bienvenido! <span id="sp_nombre_cliente"></span></h1>
              </div>
              <div class="table-responsive">
                <table class="table table-striped  no-wrap v-middle">
                  <thead>
                    <tr class="border-0 text-center">
                      <th class="border-0 fs-4">ITEM</th>
                      <th colspan="2" class="border-0 fs-4" style="padding-left: 4.5rem;">PRODUCTO</th>
                      <th class="border-0 fs-4">CANTIDAD</th>
                      <th class="border-0 fs-4">TOTAL</th>
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
            <div class="card-body button-group d-flex px-0">
              <div class="mt-2">
                <p style="padding-left: 7px;">Total : <span id="sp_total">$0.-</span></p>
              </div>
              <div class="ms-auto">
                <button type="button" id="" class="btn btn-lg btn-rounded btn-danger btn-pago my-1" onclick="limpiar_carro()">Limpiar Carro</button>
                <button type="button" id="btn_genera_pago" class="btn  btn-lg btn-rounded btn-secondary btn-pago my-1">Pagar $</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- <input type="text" id="txt_buscador_producto"> -->
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Seleccione Método de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body payment-container">
                    <p>Por favor, seleccione su método de pago preferido:</p>
                    <div class="d-grid gap-2">
                        {{-- <button type="button" class="btn btn-light payment-option" onclick="generar_pago(2)">
                            <img src="{{asset('assets/images/mercadopago-logo.png')}}" alt="Mercado Pago">
                        </button> --}}
                        <button type="button" class="btn btn-light payment-option" onclick="generar_pago(1)">
                            <img src="{{asset('assets/images/flow-logo.jpg')}}" alt="Flow">
                        </button>
                    </div>
                    <!-- Aquí puedes agregar el QR que se generará después de la selección -->
                    <div id="qr-container" class="mt-4"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="QrRegistro" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center" id="paymentModalLabel">Registro de Clientes</h5>
                </div>
                <div class="modal-body payment-container">
                    <div id="qr-container-registro" class="mx-5"></div>
                </div>
                <div class="modal-body payment-container">
                  <p>Registrese </p>
                </div>

                <div class="modal-body payment-container">
                  <div class="d-grid gap-2 mt-5">
                    <button onclick="location.reload()" class="btn btn-lg  btn-primary">Ya te registraste?</button>
                  </div>
                </div>


            </div>
        </div>
    </div>






    <script>
      const TOKEN_SESSION = '{{csrf_token()}}';
      const URL_CONSULTA_CLIENTE = "{{route('api.giftcard.codigobarra')}}"
      const URL_CONSULTA_CODIGO = "{{route('api.producto.especifico')}}"
      const IMAGEN_DEFECTO = "{{asset('assets/images/sinimagen.png')}}"
      const URL_GENERAR_VENTA ="{{route('api.generar.venta')}}"

      const ID_BODEGA = "{{$id_bodega}}"

      const URL_QR = "{{route('registro.cliente')}}"


    </script>

    <script src="{{asset('QR/qrcode.js')}}"></script>
    <script src="{{asset('totem/script.js?ver=1.1.6.15')}}"></script>

  </body>

</html>
