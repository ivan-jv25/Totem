var listado_producto = []
const lista_productos = [
    {
      "codigo": "PROD001",
      "nombre": "Papas",
      "precio": 15000,
      "imagen": "assets/images/productos/imagen_papas.jpg"
    },
    {
      "codigo": "PROD002",
      "nombre": "Agua",
      "precio": 5000,
      "imagen": "assets/images/productos/imagen_agua.jpg"
    },
    {
      "codigo": "PROD003",
      "nombre": "Yogurt",
      "precio": 20000,
      "imagen": "assets/images/productos/imagen_yogurt.jpg"
    },
    {
      "codigo": "PROD004",
      "nombre": "Sandwich",
      "precio": 35000,
      "imagen": "assets/images/productos/imagen_sandwich.jpg"
    }
  ]
  
window.onload=()=>{
    
    set_focus_code()
    document.querySelector("body").addEventListener("click", set_focus_code);
    document.getElementById("input_code_cliente").addEventListener("keydown", handleKeyDown);

    document.getElementById('txt_buscador_producto').addEventListener("keydown", handleKeyDown_producto);
    
    
    //consulta_codigo('1')

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

    console.log(_codigo)

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

    console.log(_codigo)

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

    console.log(listado_producto)

    lista_carro()
    set_focus_buscador_producto()

}


const lista_carro = ()=>{

    let _lista = ``
    let count = 0
    listado_producto.forEach(element => {
        count++
        element.total = element.precio_venta * element.cantidad
        _lista += `
        <tr class="border-2">
        <td >${count}</td>
        <td align="center">
            <img src="${element.imagen}" class="rounded-circle" width="100">
        </td>
        <td align="center">
            <p class="mb-0   w-100">${element.nombre}</p>
        </td>
            
        <td align="center">
        <div class="input-group my-2">
                <button type="button" class="btn btn-dark"><i class="fa fa-minus"></i></button>
                <div class="form-floating">
                    <input type="text" class="form-control text-center" id="floatingInputGroup1" value="${element.cantidad}">
                </div>
                <button type="button" class="btn btn-dark"><i class="fa fa-plus"></i></button>
            </div>
        </td>
        <td align="center" class="blue-grey-text  text-darken-4 font-medium">$${element.total}</td>
    </tr>

        `
    });

    const total = listado_producto.reduce((detalle, actual) =>{ return detalle = detalle + actual.total },0)
    

    
    document.getElementById('tbody_carro').innerHTML = _lista
    document.getElementById('sp_total').innerHTML = `$${total}.-`
}


