let cropper = null;

const initImageCropper = ({
    inputFileId,
    previewContainerId,
    cropButtonId,
    cancelButtonId,
    width,
    height,
    onCropSuccess,
    modalId = null
}) => {
    const input = document.getElementById(inputFileId);
    const container = document.getElementById(previewContainerId);
    const cropButton = document.getElementById(cropButtonId);
    const cancelButton = document.getElementById(cancelButtonId);
    const aspectRatio = width/height;

    // Create preview image if doesn't exist
    let previewImg = container.querySelector('img');
    if (!previewImg) {
        previewImg = document.createElement('img');
        previewImg.style.maxWidth = '100%';
        container.appendChild(previewImg);
    }

    const initCropper = (imgSrc) => {
        previewImg.src = imgSrc;
        container.style.display = 'block';

        // Show and update preview image
        previewImg.style.display = 'block';
        previewImg.src = imgSrc;

        if (cropper) {
            cropper.destroy();
        }

        try {
            
            cropper = new Cropper(previewImg, {
                aspectRatio: aspectRatio,
                viewMode: 2, // Changed to 2 to contain image within container
                dragMode: 'crop', // Changed to 'crop' to prevent image movement
                autoCropArea: 1, // Set to 1 to maximize crop area
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                responsive: true,
                background: true,
                modal: true, // Darken the area outside crop box
                movable: false, // Prevent image movement
                rotatable: false, // Prevent rotation
                scalable: false, // Prevent scaling
                zoomable: false, // Prevent zooming
                ready() {
                    // Set initial crop box size proportional to container
                    const containerData = this.cropper.getContainerData();
                    const cropBoxWidth = Math.min(containerData.width * 0.9, width);
                    const cropBoxHeight = cropBoxWidth / aspectRatio;
    
                    this.cropper.setCropBoxData({
                        width: cropBoxWidth,
                        height: cropBoxHeight
                    });
                }
            });
        } catch (error) {
            console.error(error)
        }

    };

    const cleanupCropper = () => {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        input.value = '';

        // Reset preview image
        if (previewImg) {
            previewImg.src = '';
            previewImg.style.display = 'none';
        }

        // Show the initial message again
        container.querySelector('.text-muted')?.classList.remove('d-none');
    };

    // File input handler
    input.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => initCropper(e.target.result);
        reader.readAsDataURL(file);
    });

    // --- SOLUCIÓN: Elimina listeners previos antes de agregar uno nuevo ---
    const newCropHandler = () => {
        if (!cropper) return;
        const file = input.files[0];
        if (!file) return;
        const canvas = cropper.getCroppedCanvas({ width, height });
        const originalType = file.type || 'image/jpeg';
        canvas.toBlob((blob) => {
            const croppedFile = new File([blob], file.name, {
                type: originalType,
                lastModified: new Date().getTime()
            });
            onCropSuccess(croppedFile, input);
            // cleanupCropper();
            if (modalId) {
                const modalElement = document.getElementById(modalId);
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
            }
        }, originalType, 1);
    };

    // Elimina cualquier listener anterior
    cropButton.replaceWith(cropButton.cloneNode(true));
    const freshCropButton = document.getElementById(cropButtonId);
    freshCropButton.addEventListener('click', newCropHandler);

    // Cancel button handler
    cancelButton.addEventListener('click', cleanupCropper);

    return {
        destroy: cleanupCropper,
        cleanup: cleanupCropper
    };
};
