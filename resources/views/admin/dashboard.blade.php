@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')

@if (Auth::user()->role == 'admin')
<div style="min-height: 1000px; padding: 20px;">
    <div class="container-fluid">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col text-center">
                <h1><i class="bi bi-person-gear"></i> Admin dashboard</h1>
            </div>
        </div>

        <!-- Glavni red s tri kartice -->
        <div class="row g-3" style="min-height: 280px;">
            <!-- Proizvodi -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 rounded" style="
                    background-image: url('{{ asset('image/product.webp') }}');
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;
                    min-height: 250px;
                ">
                    <div style="background-color: rgba(32, 60, 86, 0.55); color: #ffecd6;" 
                    class="card-body p-3 rounded h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title" style='color: #ffecd6;'><i class="bi bi-box"></i> Proizvodi</h4>
                            <p class="card-text">Ovdje se može dodati, izmjeniti i obrisati proizvod.</p>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('product.create.form') }}" class="w-100">
                                    <button type="button" class="btn btn-dark btn-sm w-100">
                                        <i class="bi bi-plus-circle"></i> Dodaj
                                    </button>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('product.edit.form') }}" class="w-100">
                                    <button type="button" class="btn btn-dark btn-sm w-100">
                                        <i class="bi bi-pencil-square"></i> Uredi
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kategorije -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 rounded" style="
                    background-image: url('{{ asset('image/category.webp') }}');
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;
                    min-height: 250px;
                ">
                    <div style="background-color: rgba(32, 60, 86, 0.55); color: #ffecd6;" 
                    class="card-body p-3 rounded h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title" style='color: #ffecd6;'><i class="bi bi-tags"></i> Kategorije</h4>
                            <p class="card-text">Ovdje se može dodati, izmjeniti i obrisati kategorija.</p>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('category.create.form') }}" class="w-100">
                                    <button type="button" class="btn btn-dark btn-sm w-100">
                                        <i class="bi bi-plus-circle"></i> Dodaj
                                    </button>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('category.edit.form') }}" class="w-100">
                                    <button type="button" class="btn btn-dark btn-sm w-100">
                                        <i class="bi bi-pencil-square"></i> Uredi
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ostalo -->
            <div class="col-lg-4 col-md-12">
                <div class="card h-100 rounded" style="background-color: #f5f5f5; min-height: 250px;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-muted">
                            <i class="bi bi-stars" style="font-size: 2rem;"></i>
                            <p class="mt-2">Više opcija dolazi...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@else

<div style="z-index: 99;
background-color: rgba(0, 0, 0, 0.8);
position: fixed;
top: 0; left: 0;
width: 100%; height: 100%;
display: flex;
align-items: center;
justify-content: center;
">
    <div class="alert alert-danger text-center" style="max-width: 400px;">
        <h4>🚫 Pristup odbijen</h4>
        <p>Nemaš dozvolu da pristupiš ovoj stranici. Trebaj biti admin.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Natrag na Dashboard</a>
    </div>
</div>

@endif

@endsection