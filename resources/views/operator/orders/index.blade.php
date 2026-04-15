@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Order Laundry</h5>
            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">+ Transaksi Baru</a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat (Kg)</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $item)
                    <tr>
                        <td>TRX-{{ $item->id }}</td>
                        <td>{{ $item->customer->customer_name }}</td>
                        <td>{{ $item->service->service_name ?? 'Layanan tidak ditemukan' }}</td>
                        <td>{{ $item->order_qty ?? '-' }} Kg</td>
                        <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $item->order_status == 1 ? 'bg-warning' : 'bg-success' }}">
                                {{ $item->order_status == 1 ? 'Proses' : 'Selesai' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection