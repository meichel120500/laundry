@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><h5>Tambah Voucher</h5></div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('vouchers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Kode Voucher</label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: PROMO10" required>
                    </div>
                    <div class="mb-3">
                        <label>Diskon (%)</label>
                        <input type="number" name="discount_percent" class="form-control" value="10" min="1" max="100" required>
                        <small class="text-muted">Isi angka 1 s.d 100.</small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Status Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Voucher</button>
                    <a href="{{ route('vouchers.index') }}" class="btn btn-secondary w-100 mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
