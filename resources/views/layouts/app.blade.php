<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SI Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="sidebar shadow">
        <h4 class="text-center">Sistem Informasi Laundry</h4>
        <hr>
        @if (Auth::user()->id_level != 2)
        <a href="{{ route('dashboard') }}">Dashboard</a>
        @endif

        @if (Auth::user()->id_level == 1)
        <small class="px-3 text-secondary">Master Data</small>
        <a href="{{ route('customers.index') }}">Data Customers</a>
        <a href="{{ route('users.index') }}">Data User</a>
        <a href="{{ route('services.index') }}">Jenis Service</a>
        @endif


        @if (Auth::user()->id_level == 2)
        <small class="px-3 text-secondary">Transaksi</small>
        <a href="{{ route('orders.index') }}">Orders Baru</a>
        <a href="{{ route('pickups.index') }}">Pengambalian</a>
        @endif

        @if(Auth::user()->id_level == 3)
        <small class="px-3 text-secondary">LAPORAN</small>
        <a href="{{ route('laporan.penjualan') }}">Laporan Penjualan</a> 
        @endif
    
        <hr>
        <form action="{{ route('logout') }}" method="POST" class="px-3">
            @csrf
            <button class="btn btn-danger w-100">Logout</button>
        </form>
    </div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand">Selamat Datang, {{ Auth::user()->name }}</span>
            </div>
        </nav>
        @yield('content')
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>