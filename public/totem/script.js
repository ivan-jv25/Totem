var listado_producto = []
const observador = { valor: false };


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
    
    set_focus_code()
    document.querySelector("body").addEventListener("click", set_focus_code);
    document.getElementById("input_code_cliente").addEventListener("keydown", handleKeyDown);

    document.getElementById('txt_buscador_producto').addEventListener("keydown", handleKeyDown_producto);

    document.getElementById("btn_genera_pago").addEventListener("click", generar_pago);


    
    
    
    consulta_codigo('189279027')
    
    // Definir los intervalos y almacenar los identificadores devueltos por setInterval
    const intervalo1 = setInterval(() => { buscar_producto('PROD001'); }, 2000);
    const intervalo2 = setInterval(() => { buscar_producto('987654321'); }, 4000);
    const intervalo3 = setInterval(() => { buscar_producto('1007001001'); }, 6000);
    const intervalo4 = setInterval(() => { buscar_producto('PROD003'); }, 8000);
    const intervalo5 = setInterval(() => { buscar_producto('PROD004'); }, 10000);

    
    
    // Por ejemplo, cancelar el intervalo después de cierto tiempo (en este caso, 10 segundos)
    setTimeout(() => {
      clearInterval(intervalo1);
      clearInterval(intervalo2);
      clearInterval(intervalo3);
      clearInterval(intervalo4);
      clearInterval(intervalo5);
      console.log('Intervalos cancelados.');

    //   generar_pago()
      
    }, 10000);

    
    
    // lista_carro()
}


function handleKeyDown(event) {
    if (event.key === "Enter") {
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

    const existe = await existe_codigo(_codigo)

    if(existe.status){
        document.querySelector("body").removeEventListener("click", set_focus_code);
        document.getElementById("input_code_cliente").removeEventListener("keydown", handleKeyDown);
  
        mensaje_bienvenida(existe.razon_social)
        open_doors()
    }

}


const existe_codigo = (_codigo) =>{

    return new Promise((resolve, reject)=>{
        let _url = new URL(URL_CONSULTA_CLIENTE)
        _url.searchParams.append('codigo', _codigo)

        fetch(_url)
        .then(response=>response.json())
        .then(response=>{

            document.getElementById('sp_nombre_cliente').innerHTML = response.razon_social
            resolve({
                status:true,
                razon_social:response.razon_social
            })
        })

    })

}

const mensaje_bienvenida=(_nombre)=>{

    const _mensaje = `¡Bienvenido! ${_nombre}`
    const utterance = new SpeechSynthesisUtterance(_mensaje)

    var voces = speechSynthesis.getVoices();
    var vozDeseada = voces.find(function(voice) { return voice.lang === "es-MX" && voice.name == 'Microsoft Sabina - Spanish (Mexico)'; });

    utterance.voice = vozDeseada;
    window.speechSynthesis.speak(utterance)
}



const set_focus_code = () =>{

    document.getElementById('input_code_cliente').value = ''
    document.getElementById('input_code_cliente').focus()
}

const set_focus_buscador_producto = () =>{
    
    document.getElementById('txt_buscador_producto').value = ''
    document.getElementById('txt_buscador_producto').focus()

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
        codigo_barra: _codigo
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
            precio_venta :_producto.precio_venta
        }

        listado_producto.push(obj_producto)

    }

    lista_carro()
    set_focus_buscador_producto()

}


const lista_carro = ()=>{

    let _lista = ``
    let count = 0
    listado_producto.forEach(element => {
        count++
        element.total = element.precio_venta * element.cantidad

        const _total = element.total.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });


        _lista += `
        <tr class="border-2">
            <td style="width: 5%;" >${count}</td>
            <td style="width: 10%;" align="center">
                <img src="${element.imagen}" class="rounded-circle" width="100">
            </td>
            <td style="width: 55%;" align="center">
                <p class="mb-0 w-100 text-producto">${element.nombre}</p>
            </td>
            
            <td style="width: 25%;" align="center">
            
                <div class="input-group my-2">

                    <button type="button" class="btn btn-dark"><i class="fa fa-minus"></i></button>

                    <div class="form-floating">
                        <input type="text" class="form-control text-center" id="floatingInputGroup1" value="${element.cantidad}">
                    </div>

                    <button type="button" class="btn btn-dark"><i class="fa fa-plus"></i></button>
                </div>

            </td>
            <td style="width: 5%;" align="center" class="blue-grey-text  text-darken-4 text-precio">${_total}</td>
        </tr>

    `});

    const total = listado_producto.reduce((detalle, actual) =>{ return detalle = detalle + actual.total },0).toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });

    document.getElementById('tbody_carro').innerHTML = _lista
    document.getElementById('sp_total').innerHTML = `${total}.-`
}


const generar_pago = () =>{

    
    Swal.fire({
        title: `<strong class="txt_titulo_pago">Por favor, complete su pago para continuar</strong>`,
        html: `
            <img src="/assets/images/cargando.gif" style="width: auto;">
            <br><br>
            <p class="txt_sub_titulo_pago">Estamos esperando su confirmación de pago para procesar su solicitud.
            Gracias por su paciencia.</p>
        `,
        allowOutsideClick: false,
        showConfirmButton:false
    });

}

const observadorCallback = (nuevoValor) =>{

    console.log("se genero un cambio en el valor")
    console.log(nuevoValor)

    if(nuevoValor){

        Swal.fire({
            title: "¡Pago Exitoso!",
            text: "¡Gracias por elegirnos! Esperamos que disfrutes tu compra y que tengas un día maravilloso.",
            icon: "success",
            allowOutsideClick: false,
            showConfirmButton:false
          });

    }else{

        Swal.fire({
            title: "Pago Rechazado",
            text: "Lamentamos informarte que el pago ha sido rechazado. Por favor, intenta nuevamente o selecciona otro método de pago. Gracias por tu comprensión.",
            icon: "error",
            allowOutsideClick: false,
            showConfirmButton:false
          });

    }

}

const procesador_pago = (_algun_parametro)=>{

    const estado_pago = _algun_parametro.status

    if(estado_pago){
        observador.valor = true

    }else{
        observador.valor = false
    }

}