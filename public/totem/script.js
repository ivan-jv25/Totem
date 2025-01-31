var listado_producto = []
const observador = { valor: false };
let cliente = null
var qrcode = null


Object.defineProperty(observador, 'valor', {
    get() {
      return this._valor;
    },
    set(nuevoValor) {
      const valorAnterior = this._valor;
      this._valor = nuevoValor;
      observadorCallback(nuevoValor);
    }
});


window.onload=()=>{

    try { AndroidInterface.isGiftCard(JSON.stringify({ status : true })); } catch (error) { console.log(error) }

    set_focus_code()
    document.querySelector("body").addEventListener("click", set_focus_code);
    // document.getElementById("input_code_cliente").addEventListener("keydown", handleKeyDown);

    // document.getElementById('txt_buscador_producto').addEventListener("keydown", handleKeyDown_producto);

    document.getElementById("btn_genera_pago").addEventListener("click", seleccion_pago);
    // document.getElementById("btn_genera_pago").addEventListener("click", generar_venta_aux);

}


function handleKeyDown(event) {
    if (event.key === "Enter" || event.key === "Tab") {
        const _codigo = event.target.value
        consulta_codigo(_codigo)
    }
}

function handleKeyDown_producto(event) {
    if (event.key === "Enter") {
        const _codigo = event.target.value
        buscar_producto(_codigo)
    }
}

const consulta_codigo = async (_codigo) => {

    const existe = await existe_codigo(`${_codigo}`)

    if(existe.status){
        iniciarInactividad();
        document.querySelector("body").removeEventListener("click", set_focus_code);
        // document.getElementById("input_code_cliente").removeEventListener("keydown", handleKeyDown);

        // mensaje_bienvenida(existe.razon_social)
        try { AndroidInterface.isGiftCard(JSON.stringify({ status : false })); } catch (error) { console.log(error) }
        open_doors()
    }else{
        let _url = new URL(URL_QR)
        _url.searchParams.append('codigo', `${_codigo}`)

        qrcode = new QRCode(document.getElementById("qr-container-registro"), { width : 300, height : 300 });

        qrcode.makeCode(`${_url}`);

        setTimeout(function() {
            document.querySelector("#qr-container-registro img").style.display = 'initial'

        }, 10); // Redirige después de 2 segundos

        $("#QrRegistro").modal();
    }

}


const existe_codigo = (_codigo) =>{

    return new Promise((resolve, reject)=>{

        const obj = { _token:TOKEN_SESSION, codigo: _codigo, }
        const options = { method: "POST", headers: { "Content-Type": "application/json" } , body:JSON.stringify(obj)};

        fetch(URL_CONSULTA_CLIENTE, options)
        .then(response=>response.json())
        .then(response=>{

            if(response.status){
                cliente = response.data
                document.getElementById('sp_nombre_cliente').innerHTML = cliente.razon_social
                resolve({ status:response.status, razon_social:cliente.razon_social })

            }else{
                resolve({ status:false})
            }
        })

    })

}

const mensaje_bienvenida=(_nombre)=>{

    const _mensaje = `${_nombre}`
    const utterance = new SpeechSynthesisUtterance(_mensaje)

    var voces = speechSynthesis.getVoices();
    var vozDeseada = voces.find(function(voice) { return voice.lang === "es-MX" && voice.name == 'Microsoft Sabina - Spanish (Mexico)'; });

    utterance.voice = vozDeseada;
    window.speechSynthesis.speak(utterance)
}



const set_focus_code = () =>{

    // document.getElementById('input_code_cliente').value = ''
    // document.getElementById('input_code_cliente').focus()
}

const set_focus_buscador_producto = () =>{

    // document.getElementById('txt_buscador_producto').value = ''
    // document.getElementById('txt_buscador_producto').focus()

}

const open_doors = ()=>{
    var door = document.querySelector(".door-container");
    door.classList.add("door-open");
    document.getElementById('img_logo').classList.add('img-logo-disappear')
    setTimeout(function() {
        door.classList.add("door-disappear");
        document.getElementById("contenido").style.display = "block";

        setTimeout(function() {
           door.remove()
           //
           document.getElementById("contenido").classList.add('show_conteiner')

           document.querySelector("body").removeEventListener("click", set_focus_code);
           document.querySelector("body").addEventListener("click", set_focus_buscador_producto);

           set_focus_buscador_producto()
        }, 1000);

    }, 1000); // Cambia este valor al tiempo que desees antes de que las puertas desaparezcan
}


const buscar_producto = (_codigo) =>{

    const obj = {
        _token:TOKEN_SESSION,
        codigo_barra: _codigo,
        id_bodega: ID_BODEGA
    }

    const options = { method: "POST", headers: { "Content-Type": "application/json" } , body:JSON.stringify(obj)};

        fetch(URL_CONSULTA_CODIGO, options)
        .then(response => response.json())
        .then(data =>{
            if(!data.status){
                throw "producto no ecnontrado"
            }
            agregar_producto(data.producto)
        })
        .catch(error =>{
            console.log(error)
        });
}

const agregar_producto = (_producto) => {

    const existe = listado_producto.find(p=>p.id == _producto.id ) != undefined

    if(existe){
        listado_producto.find(p=>p.id == _producto.id ).cantidad++
    }else{

        _producto.imagen  = _producto.imagen == 'Sin Imagen' ? IMAGEN_DEFECTO : _producto.imagen

        let obj_producto = {
            id : _producto.id,
            cantidad : 1,
            nombre : _producto.nombre,
            codigo : _producto.codigo,
            codigo_barra : _producto.codigo_barra,
            imagen : _producto.imagen,
            precio_venta :_producto.precio_venta,
            descuento :0
        }

        listado_producto.push(obj_producto)

    }

    lista_carro()
    set_focus_buscador_producto()

}


const lista_carro = ()=>{

    let _lista = ``
    let count = 0

    let descuento = 0;
    let total_listado_producto = 0

    listado_producto.forEach(element => { total_listado_producto += element.precio_venta * element.cantidad; });
    if (total_listado_producto >= 2000) { descuento = cliente.porcentaje || 0; }

    listado_producto.forEach(element => {
        count++
        element.total = element.precio_venta * element.cantidad

        let cantidad_descuento = element.total * (descuento / 100);
        let total_descuento = element.total - cantidad_descuento;

        const _total = total_descuento.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });


        _lista += `
        <tr class="border-2">
            <td style="width: 5%; font-size: 2.5rem;" align="center" >${count}</td>
            <td style="width: 10%;" align="center">
                <img src="${element.imagen}" class="rounded-circle" width="100">
            </td>
            <td style="width: 55%;" align="center">
                <p class="mb-0 w-100 text-producto">${element.nombre}</p>
            </td>

            <td style="width: 25%;" align="center">

                <div class="input-group my-2">

                    <button type="button" class="btn btn-dark" hidden><i class="fa fa-minus"></i></button>

                    <div class="">
                        <input type="text" class="form-control text-center" id="floatingInputGroup1" readonly value="${element.cantidad}">
                    </div>

                    <button type="button" class="btn btn-dark" hidden><i class="fa fa-plus"></i></button>
                </div>

            </td>
            <td style="width: 5%;" align="center" class="blue-grey-text  text-darken-4 text-precio">${_total}</td>
        </tr>

    `});

    const total = listado_producto.reduce((detalle, actual) =>{ return detalle = detalle + actual.total },0);

    let monto_descuento = total * (descuento / 100);
    let total_con_descuentos = total - monto_descuento;

    document.getElementById('tbody_carro').innerHTML = _lista
    document.getElementById('sp_total').innerHTML = `${total_con_descuentos.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' })}.-`
}

const seleccion_pago = () => {

    const total = listado_producto.reduce((detalle, actual) =>{ return detalle = detalle + actual.total },0);

    if(total == 0){
        Swal.fire({
            title: "Sin Productos en Carro",
            icon: "info",
            text: "Por favor ingrese al menos un producto",

            showCancelButton: true,
            focusConfirm: false,

          });

          return
    }


    $("#paymentModal").modal();
}

const generar_pago = (_metodo_pago) =>{

    $("#paymentModal").modal("hide");


    Swal.fire({
        title: `<strong class="txt_titulo_pago">Por favor, complete su pago para continuar</strong>`,
        html: `
            <img src="/assets/images/cargando.gif" style="width: 230px;">

             <div class="row">
                <div class="col-sm-3 p-3  text-white">.col</div>
                <div class="col-sm-6 p-3 "><div id="qrcode" ></div></div>
                <div class="col-sm-3 p-3  text-white">.col</div>

            </div>

            <p class="txt_sub_titulo_pago">Escanea el QR para genrar el Pago.</p>
            <p class="txt_sub_titulo_pago">Estamos esperando su confirmación de pago para procesar su solicitud.
            Gracias por su paciencia.</p>
        `,
        allowOutsideClick: false,
        showConfirmButton:false,
        showCancelButton: true,
        cancelButtonColor: "#d33",
    })
    .then((result) => {
        if (!result.isConfirmed) {
            try { AndroidInterface.stopPayment(JSON.stringify({ status : true })); } catch (error) { console.log(error) }
        }
      })
    ;

    const total = listado_producto.reduce((detalle, actual) =>{ return detalle = detalle + actual.total },0);
    const obj = { correo :cliente.correo, total : total , tipo : _metodo_pago }


    qrcode = new QRCode(document.getElementById("qrcode"), {
        width : 300,
        height : 300
    });

    document.querySelector("#qrcode img").style.display = 'initial'



    try { AndroidInterface.makePayment(JSON.stringify(obj)); } catch (error) { console.log(error) }

}

const observadorCallback = (nuevoValor) =>{

    console.log("se genero un cambio en el valor")
    console.log(nuevoValor)
    let timerInterval;

    if(nuevoValor){

        generar_venta()


    }else{


        Swal.fire({
            title: "Pago Rechazado",
            text: "Lamentamos informarte que el pago ha sido rechazado. Por favor, intenta nuevamente o selecciona otro método de pago. Gracias por tu comprensión.",
            icon: "error",
            allowOutsideClick: false,
            showConfirmButton:false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: () => {
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        })
        .then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log("I was closed by the timer");
            }
        });



    }

    listado_producto = []
    lista_carro()

}

const procesador_pago = (_algun_parametro)=>{

    const estado_pago = _algun_parametro.status

    observador.valor = estado_pago

    // if(estado_pago){
    //     observador.valor = true

    // }else{
    //     observador.valor = false
    // }

}

function RespuestaTransaccion(_obj_response){

    response = JSON.parse(_obj_response)

    procesador_pago(response)

}

const generar_qr_pago = (_URL_QR) =>{
    qrcode.makeCode(_URL_QR);

    document.querySelector("#qrcode img").style.display = 'initial'



}



const generar_venta = () =>{

    let descuento = 0;

    let total = listado_producto.reduce((detalle, actual) =>{ return detalle = detalle + actual.total },0);

    if (total >= 2000) { descuento = cliente.porcentaje || 0; }

    let monto_descuento = total * (descuento / 100);
    let total_con_descuentos = total - monto_descuento;

    let neto = parseInt(total_con_descuentos /1.19)
    let iva = total_con_descuentos - neto

    const montos = {
        total : total,
        neto : neto,
        iva : iva,
        monto_descuento : monto_descuento,
        total_con_descuentos: total_con_descuentos
    };


    let obj = {
        _token: TOKEN_SESSION,
        cliente : cliente,
        detalle : listado_producto,
        id_bodega : ID_BODEGA,
        montos : montos
    }


    const detalle_final = listado_producto.reduce((detalle, actual) =>{

        const _obj = {
            total:actual.total,
            cantidad:actual.cantidad,
            descripcion:actual.nombre,
            descuento:actual.descuento,
        }

        detalle.push(_obj)


        return detalle

    },[]);

    const options = { method: "POST", headers: { "Content-Type": "application/json" } , body:JSON.stringify(obj)};
    fetch(URL_GENERAR_VENTA, options)
    .then(response => response.json())
    .then(response =>{
        console.log(response)

        if(!response.status){
            throw "Error!!! "
        }

        const _dte =response.dte

        const _json = {
            razon_social:'razon_social',
            rut_empresa:'razon_social',
            sii_1:'razon_social',
            giro:'razon_social',
            direccion:'razon_social',
            fecha:response.fecha,
            hora:response.hora,
            tipo_doc:39,
            folio:_dte.numVenta,
            cliente:'razon_social',
            sii_2:'razon_social',
            descuento: montos.monto_descuento,
            neto:montos.neto,
            iva:montos.iva,
            forma_pago:'razon_social',
            total:montos.total_con_descuentos,
            pdf417:_dte.pdf417,
            detalle:detalle_final,
        }


        console.log(JSON.stringify(_json))

        try { AndroidInterface.imprimirDocumento(JSON.stringify(_json)); } catch (error) { console.log(error) }

    })
    .then(()=>{
        Swal.fire({
            title: "¡Pago Exitoso!",
            text: "¡Gracias por elegirnos! Esperamos que disfrutes tu compra y que tengas un día maravilloso.",
            icon: "success",
            allowOutsideClick: false,
            showConfirmButton:false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: () => {
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        })
        .then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log("I was closed by the timer");
                window.location.reload()
            }
        });
    })
    .catch((error)=>{
        alert("eRoRr!!!")
    })

}

const limpiar_carro = () => {

    if(listado_producto.length == 0){
        window.location.reload()
    }

    listado_producto = []

    lista_carro()

}

// Función para iniciar el contador de inactividad
const  iniciarInactividad = (duracionInactividad = 5 * 60 * 1000) =>{
    let temporizador;

    // Función que se llama al detectar inactividad
    const manejarInactividad = () => {
        console.log("La página ha estado inactiva durante 5 minutos.");
        // Aquí puedes añadir cualquier acción adicional
        window.location.reload()

    };

    // Función para reiniciar el temporizador
    const reiniciarTemporizador = () => {
        clearTimeout(temporizador);
        temporizador = setTimeout(manejarInactividad, duracionInactividad);
    };

    // Escuchar eventos que indican actividad del usuario
    const eventos = ['mousemove', 'keydown', 'scroll', 'click'];
    eventos.forEach(evento => {
        document.addEventListener(evento, reiniciarTemporizador);
    });

    // Iniciar el temporizador por primera vez
    reiniciarTemporizador();

    // Devolver una función para detener el monitoreo
    return () => {
        clearTimeout(temporizador);
        eventos.forEach(evento => {
            document.removeEventListener(evento, reiniciarTemporizador);
        });
        console.log("Monitoreo de inactividad detenido.");
    };
}

