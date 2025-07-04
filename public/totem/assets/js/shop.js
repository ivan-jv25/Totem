function openNav() {
    document.getElementById("my_carrito_compra").style.width = "500px";
}

function closeNav() {
    document.getElementById("my_carrito_compra").style.width = "0";
}

const volver_inicio = () => {
    const URL_INICIO = "{{ route('totem.index') }}";
    let _url = new URL(URL_INICIO);
    _url.searchParams.append("tienda", ID_BODEGA);
    window.location.href = _url; // Cambia por tu destino real
};

const confirma_pago = () => {
    window.location.href = "/pago/index.html"; // Cambia por tu destino real
};

const togglePantallaCompleta = () => {
    if (
        !document.fullscreenElement &&
        !document.mozFullScreenElement &&
        !document.webkitFullscreenElement &&
        !document.msFullscreenElement
    ) {
        activarPantallaCompleta();
    } else {
        salirPantallaCompleta();
    }
};

const activarPantallaCompleta = () => {
    const elemento = document.documentElement;
    if (elemento.requestFullscreen) {
        elemento.requestFullscreen();
    } else if (elemento.mozRequestFullScreen) {
        elemento.mozRequestFullScreen();
    } else if (elemento.webkitRequestFullscreen) {
        elemento.webkitRequestFullscreen();
    } else if (elemento.msRequestFullscreen) {
        elemento.msRequestFullscreen();
    }
};

const salirPantallaCompleta = () => {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
};


const formato_moneda = (numero) =>{ return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(numero); }