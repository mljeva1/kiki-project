@extends('layouts.app')

@section('title', 'Edit product')

@section('content')
@if (Auth::user()->role == 'admin')
<div class="w-100" style="min-height:1000px; padding: 20px;">
    <h1>Prostor za uređivanja proizvoda <i class="bi bi-brilliance"></i></h1>
    <br>

    <div class="table-responsive w-100">
        <table class="table table-striped-columns align-middle">
            <thead>
                <tr>
                    <th>Broj artikla</th>
                    <th>Naziv</th>
                    <th>Opis</th>
                    <th>Cijena</th>
                    <th>Stara cijena</th>
                    <th>Status</th>
                    <th>Količina</th>
                    <th>Slike</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                    <tr>
                        <td>{{ $p->article_number }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->description }}</td>
                        <td>{{ $p->price }}</td>
                        <td>{{ $p->old_price == $p->price ? 'N/A' : $p->old_price }}</td>
                        <td>{{ $p->is_active == 0 ? 'Aktivan' : 'Neaktivan' }}</td>
                        <td>{{ $p->quantity }}</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#imagesModal{{ $p->id }}">
                                Slike ({{ $p->images->count() }})
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $p->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('product.softDelete', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Jeste li sigurni?')">
                                    <i class="bi bi-trash-fill"></i>
                            </form>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL ZA UREĐIVANJE PROIZVODA -->
<!-- ============================================ -->
@foreach ($products as $p)
<div class="modal fade" id="editProductModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uredi proizvod</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('product.update', $p->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Naziv proizvoda</label>
                        <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opis</label>
                        <textarea name="description" rows="3" class="form-control" required>{{ $p->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cijena</label>
                        <input type="number" name="price" step="0.01" min="0.01" class="form-control" value="{{ $p->price }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Količina</label>
                        <input type="number" name="quantity" class="form-control" value="{{ $p->quantity }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                    <button type="submit" class="btn btn-primary">Spremi promjene</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL ZA UREĐIVANJE SLIKA -->
<!-- ============================================ -->
<div class="modal fade" id="imagesModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uredi slike - {{ $p->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('product.updateImages', $p->id) }}" method="POST" id="imagesForm{{ $p->id }}">
                @csrf
                <div class="modal-body">
                    <!-- Drag & Drop kontejner za slike -->
                    <div id="imagesSortable{{ $p->id }}" class="images-sortable" style="background: #f8f9fa; padding: 20px; border-radius: 5px; min-height: 200px; border: 2px dashed #ccc;">
                        @forelse ($p->images->sortBy('sort_order') as $image)
                            <div class="image-item" data-image-id="{{ $image->id }}" data-sort-order="{{ $image->sort_order }}" 
                                 style="display: inline-block; margin: 10px; padding: 10px; background: white; border-radius: 5px; cursor: grab; text-align: center; position: relative;">
                                <img src="{{ asset($image->location) }}" style="max-width: 100px; max-height: 100px; border-radius: 3px; display: block; margin-bottom: 8px;">
                                <small style="display: block; margin-bottom: 8px;">Red: <strong>{{ $image->sort_order }}</strong></small>
                                <button type="button" class="btn btn-sm btn-danger delete-image" data-image-id="{{ $image->id }}">
                                    <i class="bi bi-trash"></i> Obriši
                                </button>
                                <input type="hidden" name="images[{{ $image->id }}][sort_order]" class="sort-order-input" value="{{ $image->sort_order }}">
                            </div>
                        @empty
                            <p class="text-muted text-center">Nema slika</p>
                        @endforelse
                    </div>
                    <small class="text-muted mt-2">💡 Povuci slike lijevo/desno da promijeniš redoslijed</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                    <button type="submit" class="btn btn-primary">Spremi promjene slika</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
// Inicijalizuj Sortable za sve modalne
document.querySelectorAll('[id^="imagesSortable"]').forEach(container => {
    Sortable.create(container, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function() {
            updateSortOrders(container);
        }
    });
});

// Ažurira sort_order nakon drag&drop
function updateSortOrders(container) {
    const items = container.querySelectorAll('.image-item');
    items.forEach((item, index) => {
        const newSort = index + 1;
        item.querySelector('.sort-order-input').value = newSort;
        item.querySelector('small strong').textContent = newSort;
    });
}

// Brisanje slike
document.querySelectorAll('.delete-image').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Obrisati ovu sliku?')) {
            const imageId = this.dataset.imageId;
            const item = this.closest('.image-item');
            
            // Kreiraj hidden input za brisanje
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = `images[${imageId}][deleted]`;
            deleteInput.value = '1';
            item.closest('form').appendChild(deleteInput);
            
            // Ukloni vizualno
            item.style.opacity = '0.5';
            this.disabled = true;
            this.textContent = '✓ Obrisana';
        }
    });
});
</script>

@else
    <div style="display:flex;">4004 - Zalutao si</div>
@endif
@endsection
