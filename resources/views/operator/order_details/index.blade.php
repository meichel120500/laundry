@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Rincian Order (Detail Pesanan)</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat/Qty</th>
                        <th>Subtotal</th>
                        <th>Catatan</th>
                        <th>Tanggal Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orderDetails as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-secondary">TRX-{{ $detail->id_order }}</span>
                            </td>
                            <td>{{ $detail->order->customer->customer_name ?? 'Pelanggan Dihapus' }}</td>
                            <td>{{ $detail->service->service_name ?? 'Layanan Dihapus' }}</td>
                            <td>{{ $detail->qty }} Kg</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td>{{ $detail->notes ?? '-' }}</td>
                            <td>{{ $detail->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada rincian pesanan yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
