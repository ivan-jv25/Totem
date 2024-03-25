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

    //document.getElementById('txt_buscador_producto').addEventListener("keydown", handleKeyDown_producto);
    
    //consulta_codigo('1')

    lista_carro()
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
  
        //mensaje_bienvenida(existe.razon_social)
        open_doors()
    }

}


const existe_codigo = (_codigo) =>{

    return new Promise((resolve, reject)=>{
        resolve({
            status:true,
            razon_social:_codigo
        })
    })

}

const mensaje_bienvenida=(_nombre)=>{

    const _mensaje = `¡Bienvenido! ${_nombre}`
    const utterance = new SpeechSynthesisUtterance(_mensaje)

    var voces = speechSynthesis.getVoices();

    voces.forEach(element => {
        console.log(element)
    });

    

// Iterar sobre las voces para encontrar la que deseas utilizar
var vozDeseada = voces.find(function(voice) {
    return voice.lang === "es-MX" && voice.name == 'Microsoft Sabina - Spanish (Mexico)';
});

// Asignar la voz deseada al objeto SpeechSynthesisUtterance
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
           //set_focus_buscador_producto()      
        }, 1000);
        
    }, 1000); // Cambia este valor al tiempo que desees antes de que las puertas desaparezcan
}


const buscar_producto = (_codigo) =>{

   
}

const agregar_producto = (_producto) => {

    const existe = listado_producto.find(p=>p.id == _producto.id ) != undefined

    if(existe){
        listado_producto.find(p=>p.id == _producto.id ).cantidad++
    }else{

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

}


const lista_carro = ()=>{

    let _lista = ``
    let count = 0
    lista_productos.forEach(element => {
        count++
        element.total = element.precio
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
                    <input type="text" class="form-control text-center" id="floatingInputGroup1" value="1">
                </div>
                <button type="button" class="btn btn-dark"><i class="fa fa-plus"></i></button>
            </div>
        </td>
        <td align="center" class="blue-grey-text  text-darken-4 font-medium">$${element.total}</td>
    </tr>

        `
    });

    document.getElementById('tbody_carro').innerHTML = _lista
}


