@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')

@if (Auth::user()->role == 'admin')
<div style="min-height:1000px;">
    <div class="container">

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

        <div class="row align-items-center">
            <div class="col text-center">
                <h1><i class="bi bi-person-gear"></i> Admin dashboard</h1>
            </div>
        </div>
        <div class="row align-items-center">
            text    
        </div>
        <div class="row align-items-center">
            <div class="col p-0 rounded"
            style="
                background-image: url('{{ asset('image/product.webp') }}');
            ">
                <div style="background-color: rgba(32, 60, 86, 0.5); color:#ffecd6; " 
                class="p-2 rounded">
                    <h4>Proizvod</h4><br>
                    <p>Ovdje se može dodati, izmjeniti i obrisati proizvod.</p><br>
                    
                    <div class="row align-items-center">
                        <div class="col">
                            <a href={{ route('product.index') }}>
                            <button type="button" class="btn w-100 btn-dark">
                                <i class="bi bi-plus-circle"></i>
                            </button></a>
                        </div>
                        <div class="col">
                            <a href={{ route('product.editForm') }}>
                            <button type="button" class="btn w-100 btn-dark">
                                <i class="bi bi-pencil-square"></i>
                            </button></a>
                        </div>
                    </div>
                </div>
            </div>

            



            <div class="col">
                text
            </div>
            <div class="col">
                text
            </div>
        </div>
    </div>
</div>
@else

<div style="z-index: 99;
background-color: rgba(0, 0, 0, 0.64);
position: fixed;
top:0;left:0;
width:100%; height:100%
">
    <div class="d-flex p-2">I'm a flexbox container!</div>
</div>

@endif
@endsection