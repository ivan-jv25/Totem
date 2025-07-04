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
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    
    
    <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('totem/assets/css/styles.css') }}">

</head>
<body>
    
    
    
    <section class="py-5 text-center ">
       
        <div class="container container-logo">
            <img src="{{ asset('assets\img\logo\logo_principal.png') }}" alt="logo" class="img-fluid logo" >
        </div>

        <div class="container container-boton">
            <button class="btn-shop-start" >  <i class="fa-solid fa-mug-hot"></i></i> Empezar </button>
        </div>
    </section>



    <div class="modal fade" id="QrRegistro" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title w-100 text-center fs-2" id="paymentModalLabel">
                        <i class="fa-solid fa-user-plus me-2"></i> Registro de Clientes
                    </h5>
                </div>
                <div class="modal-body py-4 px-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-qrcode fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold mb-3 fs-3">Escanea el código QR para registrarte</h5>
                        <div id="qr-container-registro" class="d-flex justify-content-center mb-3"></div>
                        <p class="mb-0 text-secondary fs-5">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Si ya completaste tu registro, presiona el botón de abajo para continuar.
                        </p>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button onclick="location.reload()" class="btn btn-lg btn-success fs-4 py-3">
                            <i class="fa-solid fa-arrow-rotate-left me-2"></i> Ya me registré, volver a empezar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

   


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('QR/qrcode.js')}}"></script>
    <script src="{{asset('js/limpia_codigo.js')}}"></script>
    
    <script>

        const URL_CONSULTA_CLIENTE = "{{route('api.giftcard.codigobarra')}}"
        const URL_QR = "{{route('registro.cliente')}}"
        const ID_BODEGA = "{{$id_bodega}}"

        const URL_SHOP = "{{route('totem.shop')}}"

        const TOKEN_SESSION = '{{csrf_token()}}';
        
        window.onload = () => {
            const btnShopStart = document.querySelector('.btn-shop-start');
            btnShopStart.addEventListener('click', shop_start);
        }

        const shop_start = () => {
            Swal.fire({
                html: `
                    <div class="mb-4">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <span class="bg-light rounded-circle p-4 shadow-sm">
                                <i class="fa-solid fa-qrcode fa-4x text-primary"></i>
                            </span>
                        </div>
                        <h2 class="fw-bold mb-4" style="font-size:2.3rem; color:#007bff;"> ¡Escanea tu credencial para continuar! </h2>
                        <img src="assets/imagenes/scanner_qr.gif" alt="Escanear QR"  style="max-width: 340px; width: 100%; border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(0,0,0,0.12); margin-bottom: 1.5rem;">
                        <p class="fs-5 text-secondary mt-3 mb-0">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Si tienes problemas, solicita ayuda a un encargado.
                        </p>
                    </div>`,
                allowOutsideClick: false,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonColor: "#d33",
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'p-4 rounded-4',
                    cancelButton: 'swal-cancel-button fs-5 py-2 px-4'
                }
            })
        }
                

        const consulta_codigo = async (_codigo)=>{
            _codigo = limpiar_codigo(_codigo)
            
            const modalElement = document.getElementById('QrRegistro');
            const isOpen = modalElement.classList.contains('show');
            const element_qr = document.getElementById("qr-container-registro")
            const {status , ...data} = await existe_codigo(`${_codigo}`)

            if(!status){
                Swal.close()

                let _url = new URL(URL_QR)
                _url.searchParams.append('codigo', `${_codigo}`)
                element_qr.innerHTML = ''
                qrcode = new QRCode(element_qr, { width : 300, height : 300 });
                
                qrcode.makeCode(`${_url}`);
                
                if (!isOpen) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } 
                return
            }

            //enviar a la vista shop
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = URL_SHOP;

            // Por seguridad, si usas Laravel, puede que necesites incluir el token CSRF
            
            const inputToken = document.createElement('input');
            inputToken.type = 'hidden';
            inputToken.name = '_token';
            inputToken.value = TOKEN_SESSION;
            form.appendChild(inputToken);

            data.id_bodega = ID_BODEGA

           
            // Agregar los datos al formulario
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = data[key];
                    form.appendChild(input);
                }
            }

            document.body.appendChild(form);
            form.submit();
        }

        
        const existe_codigo = (_codigo) => {
            return new Promise((resolve, reject) => {
                const obj = { _token: TOKEN_SESSION, codigo: _codigo };
                const options = {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(obj)
                };
        
                fetch(URL_CONSULTA_CLIENTE, options)
                .then(response => response.json())
                .then(response => {
                    if (response.status) {
                        resolve({ status: true, ...response.data });
                    } else {
                        resolve({ status: false });
                    }
                })
                .catch(error => {
                    // Manejo de error de red o fetch
                    console.error("Error en existe_codigo:", error);
                    resolve({ status: false, error: error.message });
                });
            });
        }






        
            




                

    </script>

</body>
</html>
