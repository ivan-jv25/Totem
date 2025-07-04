<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TOTEM</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>

    
    <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('totem/assets/css/styles_inicio.css') }}">
    

  </head>
  
  <body>
    
    <div class="container-fluid mt-0 px-0 ">
      <div class="banner position-relative text-white rounded-4 p-1">

        <!-- Banner de bienvenida -->
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner rounded-3 overflow-hidden">
            @foreach ($lista_banner as $banner)

            <div class="carousel-item rounded-3 {{ $loop->first ? 'active' : '' }}">
              <img src="{{$banner->url}}" class="d-block w-100 rounded-3" alt="Slide 3">
            </div>

            @endforeach


           
            <!-- <div class="carousel-item rounded-3">
              <img src="https://placehold.co/1080x400/f39c12/ffffff" class="d-block w-100 rounded-3" alt="Slide 3">
            </div> -->
          </div>
        </div>

        <!-- Logo circular -->
        <div class="logo-wrapper position-absolute top-100 start-50 translate-middle" onclick="togglePantallaCompleta()">
          <div class="logo-circle d-flex align-items-center justify-content-center">
            <img src="{{asset('assets\img\logo\logo_redondo.png')}}" alt="Logo" class="img-fluid logo-img">
          </div>
        </div>

      </div>
    </div>
      

    <div class="container-fluid mt-0" style="height: 70vh;">
      <div class="row">

        <!-- Sidebar de Categorías -->
        <div class="col-2 mb-3  p-3 siderbar-categoria" >
          <h5 class="mb-4 text-center">Categorías</h5>

          <ul class="list-unstyled categorias-lista">

            <!-- <li class="mb-3 d-flex align-items-center">
              <img src="/temp/categoria/categoria_1.png" alt="Cat 1" class="img-fluid circulo-categoria me-2">
            </li> -->

            @foreach ($lista_categoria as $categoria)
            <li class="mb-3 d-flex align-items-center">
              <img src="{{$categoria->url}}" alt="{{$categoria->nombre}}" class="img-fluid circulo-categoria me-2">
            </li>
            @endforeach
           

          </ul>

        </div>
          
        <!-- Contenido Principal -->
        <div class="col-10 mb-3  p-3 container-producto" >
        
          <div class="row">
            <div class="col-12">
              <h5 class="card-title mt-5 mb-3 w-100"><strong>Categoria</strong> | <span>123 Productos</span></h5>
            </div>

            <div class="container row" id="contenedor_productos">
              <!-- <div class="col-4 my-2">
                <div class="card h-100">
                  <img src="https://placehold.co/300x300?text=Producto+1" class="card-img-top card-producto" alt="Producto 1">
                  <div class="card-body p-1">
                    <h5 class="card-title">Producto 1</h5>
                    <small class="card-text opacity-75">Descripción breve del producto 1.</small> 
                    <p class="mb-0">$2.500</p>
                    <button class="btn btn-dark rounded-circle position-absolute bottom-0 end-0 p-0" ><i class="fa-solid fa-plus" style="font-size: 3rem !important;" ></i></button>
                  </div>
                </div>
              </div> -->
            </div>
          
          </div>
        
        </div>
      </div>
    
    </div>

    <div class="fixed-bottom  text-center py-3 shadow-lg" style="height: 9vh;">
      <div class="d-flex justify-content-around align-items-center h-100">

        <!-- Botón 1: Casa -->
        <button class="btn btn-outline-light  fw-bold fs-1" onclick="volver_inicio()">
          <i class="fas fa-home"></i>
        </button>

        <!-- Botón 2: Carro con precio -->
        <button class="btn btn-outline-light  fw-bold fs-1" onclick="openNav()">
          <i class="fas fa-shopping-cart"></i> $18.300
        </button>

        <!-- Botón 3: Continuar -->
        <button class="btn btn-continuar fw-bold fs-1" onclick="confirma_pago()">
          Continuar
        </button>

      </div>
    </div>




    <div id="my_carrito_compra" class="carrito-compra">
      <a  class="closebtn" onclick="closeNav()">&times;</a>

      <div class="container detalle py-2" id="detalle_carrito_modal2">
        <div class="row g-2" id="detalle_carrito_modal">
  
          <!-- <div class="col-12 detalle-carrito">
            <div class="card shadow-sm">
              <div class="row g-0 align-items-center">
                <div class="col-auto">
                  <img src="/temp/caffe/caffe_1.png" class="img-fluid rounded-start" alt="Producto" >
                </div>
                <div class="col">
                  <div class="card-body py-2 px-3">
                    <h6 class="card-title mb-1">Espresso Clásico</h6>
                    <p class="card-text mb-0 text-muted">$1.800</p>
                  </div>
                </div>
                <div class="col-auto pe-3">
                  <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-secondary">−</button>
                    <input type="text" value="1" class="form-control text-center" style="width: 40px;">
                    <button class="btn btn-outline-secondary">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div> -->

        </div>
    
        
      </div>

      <div class="container">
        <div class="mt-4">

          <div class="d-flex justify-content-between py-2 border-bottom" >
            <span><strong>Cantidad Prodcutos:</strong></span>
            <span>21</span>
          </div>

          <div class="d-flex justify-content-between py-2 border-bottom" >
            <span><strong>Neto:</strong></span>
            <span>$15.378</span>
          </div>

          <div class="d-flex justify-content-between py-2 border-bottom" >
            <span><strong>IVA (19%):</strong></span>
            <span>$2.922</span>
          </div>

          <div class="d-flex justify-content-between py-2 mb-3 border-bottom" >
            <span><strong>Total:</strong></span>
            <span>$18.300</span>
          </div>

          <hr style="border-top: 3px dashed #000000;">

          <button class="btn btn-danger  w-100 fw-bold" onclick="confirma_pago()" style="margin-left: -16px;width: 505px !important;">Confirmar Compra</button>
        </div>

      </div>
      
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('totem/assets/js/shop.js') }}"></script>
    <script src="{{ asset('totem/assets/js/producto.js') }}"></script>
 
    <script>
      
      const ID_BODEGA = "{{$id_bodega}}"
      const URL_LISTA_PRODUCTOS = "{{route('lista.productos')}}"

      window.onload=()=>{
        
         buscar_productos()
      }

      
    </script>
</body>
</html>

