
@extends('layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">


        <!-- Card para subir logo -->
        <div class="col-12 col-md-5 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Subir Logo</h5>
                </div>
                <div class="card-body">

                    <!-- Puedes colocar esto dentro de una fila Bootstrap -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
                        <!-- Logo actual -->
                        <div class="mb-3 text-center flex-fill">
                            <label class="form-label">Logo actual:</label><br>
                            <img id="logo-actual" src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo actual" style="max-width: 180px; max-height: 120px; border:1px solid #ccc; border-radius:8px;">
                        </div>
                   
                        <!-- Nuevo logo seleccionado -->
                        <div class="mb-3 text-center flex-fill" id="preview-container">
                            <label class="form-label">Nuevo logo seleccionado:</label><br>
                            <div id="popup_preview_container" class="my-3" style="max-width: 200px; height: 200px; margin: 0 auto; overflow: hidden; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.25rem;"></div>
                            <div class="mt-2 text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" id="popup_crop_btn">Recortar</button>
                                    <button type="button" class="btn btn-secondary" id="popup_cancel_btn">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('imagenes.logo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="logo" class="form-label">Selecciona el logo</label>
                            <input type="file" class="form-control" id="input_file_logo" name="logo" accept="image/png" required>
                        </div>
                        <button type="submit" class="btn btn-success">Subir Logo</button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Card para subir lista de imágenes -->
        <div class="col-12 col-md-7 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Subir Banner</h5>
                </div>
                <div class="card-body">
                    <!-- Nuevo logo seleccionado -->
                    <div class="mb-3 text-center ">
                        <label class="form-label">Banner seleccionado:</label><br>
                        <div id="banner_preview_container" class="my-3" style="width: 540px; height: 200px; margin: 0 auto; overflow: hidden; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                            <img src="https://placehold.co/1080x400" >
                        </div>
                        <div class="mt-2 text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" id="banner_crop_btn">Recortar</button>
                                <button type="button" class="btn btn-secondary" id="banner_cancel_btn">Cancelar</button>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('imagenes.banner') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="imagenes" class="form-label">Selecciona una o más imágenes</label>
                            <input type="file" class="form-control" id="input_file_banner" name="banner" accept="image/*" multiple required>
                        </div>
                        <button type="submit" class="btn btn-success">Subir Imágenes</button>
                    </form>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Imagen</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_banner">
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</div>



<script src="{{ asset('js/image-cropper.js') }}"></script>

<script>

    const URL_LISTA_BANNER = "{{route('imagenes.lista')}}"
    const URL_ELIMINAR_BANNER = "{{route('imagenes.eliminar')}}"

    window.onload= async()=>{
        _init_cropper_logo()
        _init_cropper_banner()

        await lista_banner()
    }

    

    const _init_cropper_logo = () => {

        const popupCropper = initImageCropper({
            inputFileId: 'input_file_logo',
            previewContainerId: 'popup_preview_container',
            cropButtonId: 'popup_crop_btn',
            cancelButtonId: 'popup_cancel_btn',
            width: 200,
            height: 200,
            onCropSuccess: (croppedFile, input_file) => { 
    
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                // Asignar el archivo recortado al input file
                input_file.files = dataTransfer.files;
            },
            modalId : null
        });
            
        window.currentPopupCropper = popupCropper;
    };

    const _init_cropper_banner = () => {

        const popupCropper = initImageCropper({
            inputFileId: 'input_file_banner',
            previewContainerId: 'banner_preview_container',
            cropButtonId: 'banner_crop_btn',
            cancelButtonId: 'banner_cancel_btn',
            width: 1080,
            height: 400,
            onCropSuccess: (croppedFile, input_file) => { 
    
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                // Asignar el archivo recortado al input file
                input_file.files = dataTransfer.files;
            },
            modalId : null
        });
            
        window.currentPopupCropper = popupCropper;
    };
    
    const lista_banner = async () => {
        await fetch(URL_LISTA_BANNER)
        .then(response => response.json())
        .then(response => {
            let lista_banner = ``;
            if (response.status && response.data.length > 0) {
                response.data.forEach((element, idx) => {
                    lista_banner += `<tr class="text-center">
                        <td>${idx + 1}</td>
                        <td>
                            <img src="${element.url}"  alt="Banner" class="img_table">
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="eliminar_banner(${element.id})">Eliminar</button>
                        </td>
                    </tr>`;
                });
            } else {
                lista_banner = `<tr><td colspan="3" class="text-center">No hay banners</td></tr>`;
            }
            document.getElementById('tbody_banner').innerHTML = lista_banner;
        });
    }
        
    const eliminar_banner = (_id_banner) => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará el banner de forma permanente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let _url = new URL(URL_ELIMINAR_BANNER);
                _url.searchParams.append('id_banner', _id_banner);
    
                fetch(_url)
                    .then(response => response.json())
                    .then(response => {
                        if (response.status) {
                            Swal.fire('Eliminado', 'El banner ha sido eliminado.', 'success');
                            lista_banner(); // Actualiza la tabla
                        } else {
                            Swal.fire('Error', response.mensaje || 'No se pudo eliminar el banner.', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Ocurrió un error al eliminar el banner.', 'error');
                    });
            }
        });
    }
        


              
            

    
</script>
@endsection