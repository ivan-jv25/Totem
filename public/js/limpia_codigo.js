const limpiar_codigo = (_codigo) => {
    let limite = 100;
    let recorrido = 0;
    while (/[\x00-\x1F\x7F]/.test(_codigo) && recorrido < limite) {
        _codigo = _codigo.replace(/[\x00-\x1F\x7F]/g, '').trim();
        recorrido++;
    }
    return _codigo
}
