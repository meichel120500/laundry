@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h5>Daftar Pengambilan Pakaian</h5></div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> {{ session('error') }}</div>
            @endif
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Layanan / Detail Pesanan</th>
                        <th>Total Bayar</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $item)
                    <tr>
                        <td>TRX-{{ $item->id }}</td>
                        <td>{{ $item->customer ? $item->customer->customer_name : $item->customer_name_non_member . ' (Bukan Member)' }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @if($item->details->isEmpty())
                                    <li>{{ $item->service->service_name ?? '-' }} ({{ $item->order_qty ?? '-' }} Kg)</li>
                                @else
                                    @foreach($item->details as $detail)
                                        <li>{{ $detail->service->service_name ?? 'Layanan' }} ({{ $detail->qty }} Kg)</li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>
                        <td><strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($item->payment_status == 0)
                                <span class="badge bg-danger">Belum Bayar</span>
                            @else
                                <span class="badge bg-success">Sudah Bayar</span>
                            @endif
                        </td>
                        <td>
                            @if($item->payment_status == 0)
                                <button class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBayar"
                                    data-id="{{ $item->id }}"
                                    data-total="{{ $item->total }}"
                                    data-nama="{{ $item->customer ? $item->customer->customer_name : $item->customer_name_non_member . ' (Bukan Member)' }}">
                                    <i class="bi bi-cash-coin"></i> Bayar
                                </button>
                            @else
                                <a href="{{ route('pickups.updateStatus', $item->id) }}" class="btn btn-primary btn-sm" onclick="return confirm('Apakah barang benar-benar sudah diambil oleh pelanggan?')">
                                    <i class="bi bi-box-seam"></i> Ambil Barang
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalBayarLabel"><i class="bi bi-wallet2"></i> Proses Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('pickups.bayar') }}">
                @csrf
                <input type="hidden" name="order_id" id="modal_order_id">
                <div class="modal-body">
                    <p class="mb-2">Pelanggan: <strong id="modal_nama"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Tagihan</label>
                        <input type="text" id="modal_total_display" class="form-control" readonly>
                        <input type="hidden" name="total" id="modal_total">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Uang Bayar (Rp)</label>
                        <input type="number" name="order_pay" id="modal_bayar"
                               class="form-control" placeholder="Masukkan jumlah uang" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kembalian</label>
                        <input type="text" id="modal_kembalian" class="form-control fw-bold text-success" readonly>
                    </div>
                    <div class="form-check form-switch mb-3">
                      <input class="form-check-input" type="checkbox" name="ambil_barang" id="ambil_barang" checked>
                      <label class="form-check-label fw-bold" for="ambil_barang">Langsung ambil barang?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i> Proses Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    
    const modalBayar = document.getElementById('modalBayar');
    modalBayar.addEventListener('show.bs.modal', function (event) {
        const btn     = event.relatedTarget;
        const id      = btn.getAttribute('data-id');
        const total   = btn.getAttribute('data-total');
        const nama    = btn.getAttribute('data-nama');
      

        document.getElementById('modal_order_id').value = id;
        document.getElementById('modal_total').value    = total;
        document.getElementById('modal_nama').innerText = nama;
        document.getElementById('modal_total_display').value = 'Rp ' + Number(total).toLocaleString('id-ID');
        document.getElementById('modal_bayar').value    = '';
        document.getElementById('modal_kembalian').value = '';
    });

   
    document.getElementById('modal_bayar').addEventListener('input', function () {
        const total   = parseInt(document.getElementById('modal_total').value) || 0;
        const bayar   = parseInt(this.value) || 0;
        const kembalian = bayar - total;

        const elKembalian = document.getElementById('modal_kembalian');
        if (kembalian >= 0) {
            elKembalian.value = 'Rp ' + kembalian.toLocaleString('id-ID');
            elKembalian.classList.remove('text-danger');
            elKembalian.classList.add('text-success');
        } else {
            elKembalian.value = 'Uang kurang!';
            elKembalian.classList.remove('text-success');
            elKembalian.classList.add('text-danger');
        }
    });
</script>
@endsection
