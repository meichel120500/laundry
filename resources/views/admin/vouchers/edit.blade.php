@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-warning"><h5>Edit Voucher</h5></div>
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
                <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Kode Voucher</label>
                        <input type="text" name="code" class="form-control" value="{{ $voucher->code }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Diskon (%)</label>
                        <input type="number" name="discount_percent" class="form-control" value="{{ $voucher->discount_percent }}" min="1" max="100" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ $voucher->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Status Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Update Voucher</button>
                    <a href="{{ route('vouchers.index') }}" class="btn btn-secondary w-100 mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
