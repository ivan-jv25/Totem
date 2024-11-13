<!DOCTYPE html>
<html lang="es">
<head>
    <title>Totem</title>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('totem/registro.css')}}">
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

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Registro de Clientes</h2>
        <form id="registroForm">
            <!-- Campo RUT -->
            <div class="mb-3">
                <label for="rut" class="form-label">RUT</label>
                <input type="text" class="form-control" id="rut" placeholder="12.345.678-9" required>
                <div class="invalid-feedback">Ingrese un RUT válido.</div>
            </div>

            <!-- Campo Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" placeholder="Nombre Completo" required>
                <div class="invalid-feedback">El nombre es obligatorio.</div>
            </div>

            <!-- Campo Teléfono -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="telefono" placeholder="+56912345678" required>
                <div class="invalid-feedback">Ingrese un número de teléfono válido.</div>
            </div>

            <!-- Campo Correo -->
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" placeholder="correo@ejemplo.com" required>
                <div class="invalid-feedback">Ingrese un correo electrónico válido.</div>
            </div>

            <!-- Campo Dirección -->
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" placeholder="Dirección completa" required>
                <div class="invalid-feedback">La dirección es obligatoria.</div>
            </div>

            <!-- Botón de Enviar -->
            <div class="d-grid gap-2 mt-5">
                <button type="submit" class="btn btn-lg  btn-primary">Registrar</button>
            </div>

        </form>
        
    </div>
  

    <script>
        const URL_STORE = "{{route('registro.cliente.store')}}"
        const CODIGO_REGISTRO = "{{$codigo}}"

        // Función de validación de RUT
        function validarRut(rut) {
            // Ejemplo básico de validación de RUT
            const rutRegex = /^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$/;
            return rutRegex.test(rut);
        }

        // Validación y envío del formulario
        document.getElementById('registroForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío para validar primero

            let esValido = true;

            // Obtener los valores de los campos
            const rut = document.getElementById('rut');
            const nombre = document.getElementById('nombre');
            const telefono = document.getElementById('telefono');
            const correo = document.getElementById('correo');
            const direccion = document.getElementById('direccion');

            // Validar RUT
            if (rut.value.trim()==='') {
                rut.classList.add('is-invalid');
                esValido = false;
            } else {
                rut.classList.remove('is-invalid');
            }
    
            // Validar Nombre (no vacío)
            if (nombre.value.trim() === '') {
                nombre.classList.add('is-invalid');
                esValido = false;
            } else {
                nombre.classList.remove('is-invalid');
            }

            // Validar Teléfono (básico)
            const telefonoRegex = /^\+?\d{9,15}$/;
            if (!telefonoRegex.test(telefono.value)) {
                telefono.classList.add('is-invalid');
                esValido = false;
            } else {
                telefono.classList.remove('is-invalid');
            }
    
            // Validar Correo
            const correoRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if (!correoRegex.test(correo.value)) {
                correo.classList.add('is-invalid');
                esValido = false;
            } else {
                correo.classList.remove('is-invalid');
            }
    
            // Validar Dirección (no vacía)
            if (direccion.value.trim() === '') {
                direccion.classList.add('is-invalid');
                esValido = false;
            } else {
                direccion.classList.remove('is-invalid');
            }

            // Si todos los campos son válidos, enviar los datos
            if (esValido) {
                const datos = {
                    rut: rut.value,
                    nombre: nombre.value,
                    telefono: telefono.value,
                    correo: correo.value,
                    direccion: direccion.value,
                    codigo : CODIGO_REGISTRO
                };

                // Aquí enviarías los datos al servidor
                console.log("Datos a enviar:", datos);
                store_clinete(datos)
            }
        });

            
        const store_clinete = (_datos)=>{

            fetch(URL_STORE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(_datos)
            })
            .then(response => response.json())
            .then(response =>{

                Swal.fire({
                    title: "Registro Exitoso!",
                    text: "Preciona el boton de  'Ya estas Registrado' , para comprar",
                    icon: "success"
                });
                setTimeout(function() {
                    window.location.href = "https://lindasdulzuras.com/";
                }, 4000); // Redirige después de 2 segundos
            })
        }

        
    </script>
</body>
</html>