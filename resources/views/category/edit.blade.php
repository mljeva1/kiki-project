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
                    <th>ID kategorije</th>
                    <th>Naziv</th>
                    <th>Opis</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category as $c)
                    <tr>
                        <td style="width:40vh">{{ $c->id }}</td>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->description }}</td>
                        <td style="width:20vh">
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $c->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('category.destroy', $c->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Jeste li sigurni?')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



<!-- MODAL ZA UREĐIVANJE KATEGORIJA -->
@foreach ($category as $c)
<div class="modal fade" id="editCategoryModal{{ $c->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uredi kategoriju</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('category.update', $c->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Naziv kategorije</label>
                        <input type="text" name="name" class="form-control" value="{{ $c->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opis</label>
                        <textarea name="description" rows="3" class="form-control" required>{{ $c->description }}</textarea>
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
@endforeach

@else
    <div style="display:flex;">4004 - Zalutao si</div>
@endif
@endsection