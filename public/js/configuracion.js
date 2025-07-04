window.onload=()=>{

    console.log('Iniciando Modulos......')

}


const modal_configuracion = (_id_bodega) => {

    $("#ModalConfiguracion").modal('show');

    document.getElementById('btn_envio_data_bodega_token').setAttribute('onclick',`enviar_data_token(${_id_bodega})`)

}


const enviar_data_token = (_id_bodega) =>{
    const username = document.getElementById('id_correo_configuracion').value
    const password = document.getElementById('id_contrasena_configuracion').value


    const obj = {
        _token:TOKEN_SESSION,
        username : username,
        password : password,
        id_bodega : _id_bodega
    }

    const options = { method: "POST", headers: { "Content-Type": "application/json" } , body:JSON.stringify(obj)};

    fetch(URL_TOKEN_BODEGA, options)
    .then(response=>response.json())
    .then(response =>{
        console.log(response)

        if(response.status){
            window.location.reload()
        }else{
            alert("Este Usuario no Concuerda con la Bodega!")
        }

    })


}




