@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><h5>Input Transaksi Baru</h5></div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Pilih Pelanggan</label>
                        <select name="customer_id" class="form-control" required>
                            <option value="">-- Cari Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
            <label>Pilih Layanan</label>
                <select name="id_service" id="service_id" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                 @foreach($services as $s)
                  <option value="{{ $s->id }}" data-price="{{ $s->price }}">
                {{ $s->service_name }} (Rp {{ $s->price }}/kg)
                    </option>
                @endforeach
            </select>
                </div>
                    <div class="mb-3">
                        <label>Berat (Kg)</label>
                        <input type="number" name="qty" id="qty" class="form-control" step="0.1" required>
                    </div>
                    <div class="alert alert-info">
                        <strong>Estimasi Total: </strong> Rp <span id="total_display">0</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const serviceSelect = document.getElementById('service_id');
    const qtyInput = document.getElementById('qty');
    const totalDisplay = document.getElementById('total_display');

    function hitungTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;
        const qty = qtyInput.value || 0;
        const total = price * qty;
        totalDisplay.innerText = total.toLocaleString('id-ID');
    }

    serviceSelect.addEventListener('change', hitungTotal);
    qtyInput.addEventListener('input', hitungTotal);
</script>
@endsection