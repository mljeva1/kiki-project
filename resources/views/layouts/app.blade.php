<!doctype html>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Webshop'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://unpkg.com/cropperjs"></script>
    <style>
        :root {
            --primary-darkest: #0d2b45;
            --primary-dark: #203c56;
            --primary: #544e68;
            --secondary: #8d697a;
            --accent-dark: #d08159;
            --accent: #ffaa5e;
            --accent-light: #ffd4a3;
            --background: #ffecd6;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('image/background.webp') }}');
            background-size: cover;
            background-position: 25% 70%;
            background-repeat: no-repeat;
            z-index: -1;
            will-change: transform;
            opacity: 0.4;
        }
        body::after {
            background-color: rgba(32, 60, 86, 0.3);
        }
        body {
            background: var(--background);
            color: var(--primary-darkest);
        }
        .navbar, .footer {
            background: var(--primary-darkest);
        }
        .navbar-brand, .nav-link, .footer-text {
            color: var(--accent-light) !important;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: var(--accent) !important;
            background: transparent !important;
        }

        .btn-main {
            background: var(--accent);
            color: var(--primary-darkest) !important;
            border: none;
        }
        .btn-main:hover {
            background: var(--accent-dark);
        }
        .card {
            background: var(--accent-light);
            border: none;
        }
        .card-header {
            background: var(--primary-dark);
            color: var(--background);
        }
        .card-title {
            color: var(--primary-darkest);
        }
        .bg-primary-darkest { background: var(--primary-darkest) !important; }
        .bg-accent { background: var(--accent) !important; }
        .text-accent { color: var(--accent) !important; }
        .backProduct {
            background: rgba(13, 43, 69, 0.3);
            background: -webkit-linear-gradient(0deg, rgba(13, 43, 69, 1) 0%, rgba(32, 60, 86, 1) 50%, rgba(84, 78, 104, 1) 100%);
            background: -moz-linear-gradient(0deg, rgba(13, 43, 69, 1) 0%, rgba(32, 60, 86, 1) 50%, rgba(84, 78, 104, 1) 100%);
            background: linear-gradient(0deg, rgba(13, 43, 69, 1) 0%, rgba(32, 60, 86, 1) 50%, rgba(84, 78, 104, 1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#0D2B45", endColorstr="#544E68", GradientType=0);
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 100%;
            min-height: 94vh;
            width: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div style="background-color: rgba(32, 60, 86, 0.3);">
    
    
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            @auth
                @if (Auth::user()->role == 'admin')
                    
                    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                        <img src="{{ asset('image/icon.webp') }}" alt="Webshop logo" style="height: 1.8rem; width: auto; display: inline-block;">
                        Webshop
                    </a>
                @else
                    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                        <img src="{{ asset('image/icon.webp') }}" alt="Webshop logo" style="height: 1.8rem; width: auto; display: inline-block;">
                        Webshop
                    </a>
                @endif
            @endauth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Proizvodi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Košarica</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-cart-dash"></i></a></li>
                    
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="#">{{ auth()->user()->first_name }}</a></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="text-decoration: none;">Odjava</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{  route('login') }}">Prijava</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Registracija</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="footer py-3 mt-auto">
        <div class="container text-center">
            <span class="footer-text">&copy; {{ date('Y') }} Webshop</span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')

    </div>
</body>
</html>
