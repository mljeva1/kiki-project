@extends('layouts.app')

@section('title', 'Create product')

@section('content')
<div style="min-height:stretch;">
    <div class="container mt-5" >
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <h1 class="mb-4">Novi proizvod</h1>
        
        <form action="{{ route('product.create') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <!-- Osnovni podaci u jednoj row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="product_name" class="form-label">Naziv proizvoda</label>
                    <input type="text" name="name" id="product_name" class="form-control" placeholder="Unesi naziv proizvoda" required>
                </div>
                <div class="col-md-3">
                    <label for="product_desc" class="form-label">Opis proizvoda</label>
                    <textarea name="description" id="product_desc" rows="1" class="form-control" placeholder="Unesi opis proizvoda" required></textarea>
                </div>
                <div class="col-md-3">
                    <label for="product_price" class="form-label">Cijena proizvoda</label>
                    <input type="number" name="price" id="product_price" step="0.01" min="0.01" class="form-control" placeholder="Unesi cijenu proizvoda" required>
                </div>
                <div class="col-md-3">
                    <label for="product_quan" class="form-label">Količina na stanju</label>
                    <input type="number" name="quantity" id="product_quan" class="form-control" placeholder="Unesi količinu proizvoda" required>
                </div>
            </div>

            <!-- Status provjera -->
            <div class="alert alert-info" id="statusAlert">
                Učitane slike: <strong id="uploadedCount">0</strong>/5 | 
                Crop-ovane slike: <strong id="croppedCount">0</strong>/5
            </div>
            
            <!-- Drag & Drop kontejner -->
            <div class="row mb-4">
                <div class="col-12">
                    <label class="form-label"><strong>Slike (drag & drop za redoslijed)</strong></label>
                    <div id="imagesSortable" style="background: #f8f9fa; padding: 15px; border-radius: 5px; border: 2px dashed #ccc; min-height: 150px;">
                        <!-- Slike će se dinamički dodavati ovdje -->
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <div class="row mt-4">
                <div class="col-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn" disabled>Spremi proizvod</button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Modal za Crop -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down" style="max-width: 95% !important;">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header">
                <h5 class="modal-title">Crop slika (Premještaj do željene pozicije)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" style="padding: 20px; overflow: auto;">
                <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                    <img id="cropImage" src="" style="max-width: 100%; max-height: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
                <button type="button" id="saveCropBtn" class="btn btn-primary">Spremi crop</button>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js -->
<link rel="stylesheet" href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css">
<script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>

<!-- SortableJS za drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
let cropper = null;
let currentImageIndex = null;
let croppedImages = {};
let uploadedImages = {};

// INPUT HIDDEN ZA SORTIRANJE
const form = document.getElementById('productForm');

// Kreiraj 5 defaultnih input slota
for (let i = 0; i < 5; i++) {
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]';
    input.style.display = 'none';
    input.className = 'image-input';
    input.dataset.index = i;
    input.accept = 'image/*';
    form.appendChild(input);
}

// Event listener za file inpute
document.querySelectorAll('.image-input').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = this.files[0];
        const index = this.dataset.index;

        if (file) {
            if (file.size > 4 * 1024 * 1024) {
                alert('Slika je prevelika! Maksimalno 4MB.');
                this.value = '';
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('Molimo odaberi sliku!');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                uploadedImages[index] = {
                    base64: e.target.result,
                    file: file
                };
                renderImages();
                updateStatus();
            };
            reader.readAsDataURL(file);
        } else {
            delete uploadedImages[index];
            renderImages();
            updateStatus();
        }
    });
});

// Renderira slike u sortable kontejner
function renderImages() {
    const container = document.getElementById('imagesSortable');
    container.innerHTML = '';

    Object.entries(uploadedImages).forEach(([index, imageData]) => {
        const div = document.createElement('div');
        div.className = 'image-item';
        div.dataset.index = index;
        div.style.cssText = `
            display: inline-block;
            margin: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border: 2px solid #ddd;
            cursor: grab;
            text-align: center;
        `;

        const isCropped = croppedImages[index] ? true : false;
        const badgeColor = isCropped ? 'success' : 'warning';
        const badgeText = isCropped ? 'Crop-ovana ✓' : 'Učitana';

        div.innerHTML = `
            <div style="position: relative;">
                <img src="${isCropped ? croppedImages[index] : imageData.base64}" 
                     style="max-width: 120px; max-height: 120px; cursor: pointer; border-radius: 3px;"
                     class="img-preview-sortable"
                     data-index="${index}">
                <span class="badge bg-${badgeColor}" style="position: absolute; top: 5px; right: 5px;">
                    ${badgeText}
                </span>
            </div>
        `;

        container.appendChild(div);
    });

    // Inicijalizuj Sortable nakon renderiranja
    if (Object.keys(uploadedImages).length > 0) {
        Sortable.create(container, {
            animation: 150,
            ghostClass: 'sortable-ghost',
        });
    }
}

// Click na sliku za crop
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('img-preview-sortable')) {
        currentImageIndex = e.target.dataset.index;
        const cropImg = document.getElementById('cropImage');
        cropImg.src = uploadedImages[currentImageIndex].base64;

        const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
        cropModal.show();
    }
});

// Inicijalizuj Cropper
document.getElementById('cropModal').addEventListener('shown.bs.modal', function() {
    const cropImg = document.getElementById('cropImage');
    
    if (cropper) {
        cropper.destroy();
    }

    cropper = new Cropper(cropImg, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true,
        guides: true,
        center: true,
        highlight: true,
        cropBoxMovable: true,
        cropBoxResizable: false,
        dragMode: 'move',
        wheelZoomRatio: 0.1,
        zoomable: true,
    });
});

document.getElementById('cropModal').addEventListener('hidden.bs.modal', function() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

// Spremi crop
document.getElementById('saveCropBtn').addEventListener('click', function() {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({
        maxWidth: 800,
        maxHeight: 800,
        fillColor: '#fff',
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });

    croppedImages[currentImageIndex] = canvas.toDataURL('image/png');
    renderImages();
    updateStatus();

    bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
});

// Ažurira status brojačeve
function updateStatus() {
    const uploadedCount = Object.keys(uploadedImages).length;
    const croppedCount = Object.keys(croppedImages).length;
    
    document.getElementById('uploadedCount').textContent = uploadedCount;
    document.getElementById('croppedCount').textContent = croppedCount;
    
    // Omogući submit samo ako su sve slike crop-ovane
    const submitBtn = document.getElementById('submitBtn');
    if (uploadedCount > 0 && uploadedCount === croppedCount) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Prije submit-a
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Ukloni stare hidden inpute
    document.querySelectorAll('input[name="cropped_images[]"]').forEach(el => el.remove());
    document.querySelectorAll('input[name="sort_order[]"]').forEach(el => el.remove());

    // Dobij sortirani redoslijed iz DOM-a
    const imageItems = document.querySelectorAll('.image-item');
    let sortOrder = 1;

    imageItems.forEach(item => {
        const index = item.dataset.index;
        const base64 = croppedImages[index];

        if (base64) {
            // Dodaj crop-ovanu sliku
            const imgInput = document.createElement('input');
            imgInput.type = 'hidden';
            imgInput.name = 'cropped_images[]';
            imgInput.value = base64;
            this.appendChild(imgInput);

            // Dodaj redoslijed
            const sortInput = document.createElement('input');
            sortInput.type = 'hidden';
            sortInput.name = 'sort_order[]';
            sortInput.value = sortOrder;
            this.appendChild(sortInput);

            sortOrder++;
        }
    });

    // Submiti formu
    this.submit();
});

// File input za učitavanje
document.addEventListener('click', function(e) {
    if (e.target.tagName === 'DIV' && e.target.id === 'imagesSortable') {
        // Pronađi prvi prazan slot
        for (let i = 0; i < 5; i++) {
            if (!uploadedImages[i]) {
                document.querySelector(`input[data-index="${i}"]`).click();
                break;
            }
        }
    }
});

// Inicijalni opis
document.getElementById('imagesSortable').innerHTML = '<div style="text-align: center; color: #999; padding: 30px;"><strong>Klikni ovdje ili drag & drop slike</strong></div>';
</script>

@endsection
