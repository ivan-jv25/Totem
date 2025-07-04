let _paguina = 1

const buscar_productos=()=>{

    let _url = new URL(URL_LISTA_PRODUCTOS);
    _url.searchParams.append('pagina', _paguina);
    _url.searchParams.append('id_bodega', ID_BODEGA);

    fetch(_url)
    .then(response => response.json())
    .then(response => {

        let html = '';
        // lista_producto = lista_producto.concat(data);

        response.data.forEach(item => {
            html+=contenedor_producto(item)
        });
        document.getElementById('contenedor_productos').innerHTML += html;

        return response.data;
    })
    .then(data=>{
        // if(data.length == 0){
        //     observador.unobserve(document.querySelector('#list_productos li:last-child'));
        // }
        // if(data.length > 0){
        //     const lastItem = document.querySelector('#list_productos li:last-child');
        //     observador.observe(lastItem);
        // }
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
                        <a class=" rounded-circle position-absolute bottom-0 end-0 btn-agregar" ><i class="fa-solid fa-plus"></i></a>
                    </div>
                </div>
            </div>`
}