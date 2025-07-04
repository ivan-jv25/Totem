let _paguina = 1
let detalle_carro = []
let lista_producto = []

const observador = new IntersectionObserver ((inicio, observa) => {
    inicio.forEach(inicio => {
        if (inicio.intersectionRatio === 1) {
            _paguina++;
            buscar_productos();
        }
    });
}, { threshold: 1});
            

const buscar_productos=()=>{

    let _url = new URL(URL_LISTA_PRODUCTOS);
    _url.searchParams.append('pagina', _paguina);
    _url.searchParams.append('id_bodega', ID_BODEGA);

    fetch(_url)
    .then(response => response.json())
    .then(response => {

        let html = '';
        lista_producto = lista_producto.concat(response.data);

        response.data.forEach(item => {
            html+=contenedor_producto(item)
        });
        document.getElementById('contenedor_productos').innerHTML += html;

        return response.data;
    })
    .then(data=>{
        if(data.length == 0){
            observador.unobserve(document.querySelector('#contenedor_productos div:last-child'));
        }
        if(data.length > 0){
            const lastItem = document.querySelector('#contenedor_productos div:last-child');
            observador.observe(lastItem);
        }
    })

}

const contenedor_producto =(producto)=>{

    return `<div class="col-4 my-2">
                <div class="card h-100 border-top-radius">
                    <img src="${producto.url_local}" class="card-img-top card-producto border-top-radius" alt="Producto 1">
                    <div class="card-body p-1">
                        <h5 class="card-title">${producto.nombre}</h5>
                        <p class="card-text text-muted descripcion-producto" >${producto.descripcion}</p> 
                        <p class="mb-0">${formato_moneda(producto.precio_venta)}</p>
                        <a class=" rounded-circle position-absolute bottom-0 end-0 btn-agregar" onclick="agregar_carro(${producto.id})"><i class="fa-solid fa-plus"></i></a>
                    </div>
                </div>
            </div>`
}

const agregar_carro = (id_producto) => {

    const _producto = lista_producto.find(e => e.id == id_producto);

    const _obj_producto = {
        id : _producto.id,
        nombre : _producto.nombre,
        codigo : _producto.codigo,
        descripcion : _producto.descripcion,
        url_local : _producto.url_local,
        cantidad : 1,
        stock : _producto.stock,
        precio : parseInt(_producto.precio_venta),
        descuento : 0,
        total : parseInt(_producto.precio_venta),
        id_subfamilia : _producto.id_subfamilia,
    }

    if(detalle_carro.length >= 60){
        alert("Excedió el máximo permitido!!")
        return
    }

    let detalle = detalle_carro.find(d => d.id == id_producto);

    if(detalle){
        detalle.cantidad++;
        detalle.total = detalle.cantidad * detalle.precio
    }else{
        detalle_carro.push(_obj_producto)
    }

    const _total = detalle_carro.reduce(( detalle, actual)=>{return detalle+parseInt(actual.total)},0)

    document.getElementById('sp_total_a_pagar').innerHTML = formato_moneda(_total)

    animarCarroCompra()

    showBootstrapToast('Producto Agregado con Exito!', 1700);
}


const open_carro =()=> {

    cargar_detalle_carrito()
    document.getElementById("my_carrito_compra").style.width = "500px";
}

function close_carro() {
    document.getElementById("my_carrito_compra").style.width = "0";
}

const cargar_detalle_carrito = () => {

   let element = document.getElementById("detalle_carrito_modal");
    element.innerHTML = ""; // Limpiar el contenedor antes de agregar nuevos productos

    let listado = ``
    detalle_carro.forEach(element => {

      listado += `<div class="col-12 my-2">
            <div class="card shadow-sm">
              <div class="row g-0 align-items-center">
                <div class="col-auto">
                  <img src="${element.url_local}" class="img-fluid rounded-start img_carrito" alt="Producto" >
                </div>
                <div class="col">
                  <div class="card-body py-2 px-3">
                    <h6 class="card-title mb-1">${element.nombre}</h6>
                    <p class="card-text mb-0 text-muted">${formato_moneda(element.total)}</p>
                  </div>
                </div>
                <div class="col-auto pe-3">
                  <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-secondary">−</button>
                    <input type="text" value="${element.cantidad}" class="form-control text-center" style="width: 40px;">
                    <button class="btn btn-outline-secondary">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>`
    });


    const _cantidad = detalle_carro.reduce(( detalle, actual)=>{return detalle+parseInt(actual.cantidad)},0)

    
    const _total = detalle_carro.reduce(( detalle, actual)=>{return detalle+parseInt(actual.total)},0)
    const neto = Math.round(_total / (1 + 19 / 100));
    const iva = _total - neto;

    document.getElementById('sp_cantidad_productos').innerHTML = _cantidad
    document.getElementById('sp_neto_compra').innerHTML = formato_moneda(neto)
    document.getElementById('sp_iva_compra').innerHTML = formato_moneda(iva)
    document.getElementById('sp_total_compra').innerHTML = formato_moneda(_total)
    element.innerHTML = listado; // Asignar el listado generado al contenedor
}

const showBootstrapToast = (mensaje, duracion = 1500) =>{
    const toastEl = document.getElementById('liveToast');
    toastEl.querySelector('.toast-body').innerText = mensaje;
    const toast = new bootstrap.Toast(toastEl, { delay: duracion });
    // const toast = new bootstrap.Toast(toastEl, { autohide: false });
    toast.show();
}
    

let animando = false;

function animarCarroCompra() {
    if (animando) return;
    animando = true;

    const icono = document.getElementById('icono_carro');
    const gif = document.getElementById('gif_carro');
    if (!icono || !gif) return;

    icono.classList.remove('visible');
    icono.classList.add('oculto');
    gif.classList.remove('oculto');
    gif.classList.add('visible');

    setTimeout(() => {
        gif.classList.remove('visible');
        gif.classList.add('oculto');
        icono.classList.remove('oculto');
        icono.classList.add('visible');
        animando = false;
    }, 3000);
}