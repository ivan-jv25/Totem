const data_producto = [
    {
        id:1,
        nombre :'Coca-Cola Zero 350cc',
        precio : 1000,
        src:'asset/img/02.jpg'
    },
    {
        id:2,
        nombre :'Media Luna Bañada',
        precio : 1000,
        src:'asset/img/05.jpg'
    },
    {
        id:3,
        nombre :'Galleta Oreo',
        precio : 1000,
        src:'asset/img/06.jpg'
    },
]

var lista_producto = []
var carrito = []

window.onload = async () =>{
    numero_carro()
    lista_producto = await cargar_productos()
    mostrar_lista_producto()


    // agregar_carro(1)
    // agregar_carro(1)
    // agregar_carro(2)
    // agregar_carro(2)
    // agregar_carro(3)


    // modal_carro()

    
}



const cargar_productos = () =>{
    return new Promise((resolve, reject)=>{
        resolve(data_producto)
    })
}


const mostrar_lista_producto = () =>{
    let lista = ``
    lista_producto.forEach(element => {
        lista += `
            <div class="col-md-4 px-1">
                <div class="producto box-shadow py-3">
                    <img src="${element.src}" >
                    <h4 style="font-size: 1rem;">${element.nombre}</h4>
                    <p>Precio: $${element.precio}</p>
                    <div class="mx-4">
                        <a onclick="agregar_carro(${element.id})" class="btn btn-block  btn-color ">Agregar al Carro!</a>
                    </div>
                    <i class="fas fa-search lupa" onclick="mostrarDetalle(${element.id})"></i>
                </div>
            </div>
        `
    });
    
    document.getElementById('div_lista_productos').innerHTML = lista
}

const mostrarDetalle = (_id) =>{

    const producto = lista_producto.find(d=>d.id === _id)
    document.getElementById('label_modal_nombre').innerHTML = producto.nombre
    document.getElementById('img_modal_producto').src = producto.src
    document.getElementById('p_modal_precio').innerHTML = `Precio: $${producto.precio}`

    document.getElementById('btn_modal_agregar_producto').setAttribute('onclick',`agregar_carro(${producto.id})`)

    $('#modalDetalles').modal('show');

}

const agregar_carro = (_id) =>{

    const existe = carrito.find(d=>d.id === _id)
    if(existe){ carrito.find(d=>d.id === _id).cantidad++ }
    else{
        const producto = lista_producto.find(d=>d.id === _id)
        let obj = { id:producto.id, nombre:producto.nombre, precio : producto.precio ,cantidad:1, src : producto.src }
        carrito.push(obj)
    }

    contairner_message('Producto Agregado al Carro')
    numero_carro()   
}

const numero_carro = () =>{
    const cantidad = carrito.reduce((actual,detalle)=>{ return actual + parseInt(detalle.cantidad) }, 0)
    document.getElementById('counter-circle').innerHTML = cantidad
}


const contairner_message = (_contenido) =>{

    const messageContainer = document.createElement('div');
    
    messageContainer.style.backgroundColor = 'white';
    messageContainer.style.color = 'black';
    messageContainer.style.padding = '10px';
    messageContainer.style.borderRadius = '5px'; // Ajusta los bordes
    messageContainer.style.position = 'fixed';
    messageContainer.style.top = '10px';
    messageContainer.style.left = '50%';
    messageContainer.style.zIndex = '1000'; // Ajusta el valor según sea necesario
    messageContainer.style.transform = 'translateX(-50%)';
    messageContainer.textContent = _contenido;

    document.body.appendChild(messageContainer);

    // Desaparece después de 1 segundo
    setTimeout(function () { document.body.removeChild(messageContainer); }, 1000);

}


const modal_carro = () => {

    let lista = ``
    carrito.forEach(element => {
        lista += `
            <tr>
                                    <td><img class="mt-1" src="${element.src}" alt="iMac" width="80"></td>
                                    <td class="py-1" >
                                        <h5 class="font-500 mt-4">${element.nombre} ($${element.precio})</h5>
                                    </td>
                                    
                                    <td>
                                        <div class="row mt-4">

                                            <div class="counter">
                                                <div class="col-md-4">
                                                    <button id="decrease">-</button>

                                                </div>
                                                <div class="col-md-4">
                                                    
                                                    <span id="number">${element.cantidad}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    
                                                    <button id="increase">+</button>
                                                </div>
                                              </div>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="row mt-4 justify-content-center align-items-center">
                                            <strong class="">$${(element.cantidad * element.precio)}</strong>
                                        </div>
                                    </td>
                                    <td align="center"> 
                                        <div class="row mt-4 justify-content-center align-items-center">
                                            <button class=""><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
        `
    });

    const total = carrito. reduce((actual, detalle)=>{return actual + (detalle.cantidad * detalle.precio)},0)


    document.getElementById('monto_total_modal').innerHTML = `$${total}`
    document.getElementById('tbody_modal_carrito').innerHTML = lista
    $('#carritoModal').modal('show');
}