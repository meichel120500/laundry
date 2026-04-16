@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph text-primary"></i> Laporan Penjualan</h5>
            <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="bi bi-printer"></i> Cetak</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode Transaksi</th>
                            <th>Customer</th>
                            <th>Jenis Layanan</th>
                            <th>Status Data</th>
                            <th>Berat (kg)</th>
                            <th class="text-end">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $i => $order)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</td>
                            <td>{{ $order->order_code ?? ('TRX-'.$order->id) }}</td>
                            <td>{{ $order->customer->customer_name ?? '-' }}</td>
                            <td>{{ $order->service->service_name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $order->order_status == 0 ? 'bg-warning' : 'bg-success' }}">
                                    {{ $order->order_status == 0 ? 'Baru' : 'Sudah Diambil' }}
                                </span>
                            </td>
                            <td>{{ $order->order_qty ?? '0' }}</td>
                            <td class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data penjualan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="7" class="text-end">TOTAL KESELURUHAN PENJUALAN:</th>
                            <th class="text-end">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>

@media print {
    .sidebar, .navbar, .btn {
        display: none !important;
    }
    .content {
        margin-left: 0 !important;
        width: 100% !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
