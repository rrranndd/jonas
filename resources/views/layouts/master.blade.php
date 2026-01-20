<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/dashboard.css'])
</head>

<body>

    <div class="sidebar">
        <h4><i class="bi bi-camera-fill"></i> Jonas Photo</h4>

        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="/pelanggan" class="{{ request()->is('pelanggan*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Pelanggan
        </a>

        <a href="/paket" class="{{ request()->is('paket*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Paket
        </a>

        <a href="/order" class="{{ request()->is('order*') ? 'active' : '' }}">
            <i class="bi bi-cart-check-fill"></i> Order
        </a>

        <a href="/invoice" class="{{ request()->is('invoice*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> Invoice
        </a>

        <a href="/laporan" class="{{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Laporan
        </a>

        <a href="{{ route('logout') }}" class="btn btn-danger btn-sm float-end">Logout</a>

    </div>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
        </div>
    </nav>

    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')

</body>
</html>
