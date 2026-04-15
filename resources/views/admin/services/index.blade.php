@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Jenis Layanan (Services)</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Layanan
            </button>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Harga (Rp)</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->service_name }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('services.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus layanan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Layanan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" name="service_name" class="form-control" placeholder="Contoh: Cuci Kering" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga per Kg/Satuan</label>
                        <input type="number" name="price" class="form-control" placeholder="5000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Layanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($services as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('services.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" name="service_name" class="form-control" value="{{ $item->service_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="price" class="form-control" value="{{ $item->price }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="description" class="form-control" rows="3">{{ $item->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection