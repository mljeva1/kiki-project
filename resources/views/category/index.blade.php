@extends('layouts.app')

@section('title', 'Create category')

@section('content')

<div style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <h1 class="mb-4 text-center">Nova kategorija</h1>

                <form action="{{ route('category.create') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Naziv kategorije</label>
                        <input type="text" name="name" id="category_name" class="form-control" placeholder="Unesi naziv kategorije" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_desc" class="form-label">Opis kategorije</label>
                        <textarea name="description" id="category_desc" rows="4" class="form-control" placeholder="Unesi opis kategorije" required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Spremi kategoriju</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection