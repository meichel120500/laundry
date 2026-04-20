@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><h5>Input Transaksi Baru</h5></div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST" id="formTransaksi">
                    @csrf
                    <div class="mb-3">
                        <label>Pilih Pelanggan</label>
                        <div class="input-group">
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">-- Cari Pelanggan --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalTambahCepat">
                                + Baru
                            </button>
                        </div>
                    </div>

                    <!-- Area Tambah Layanan Sementara -->
                    <div class="card bg-light mb-4 border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-cart-plus"></i> Keranjang Layanan</h6>
                            <div class="row gx-2 align-items-center">
                                <div class="col-md-4 mb-2">
                                    <select id="temp_service" class="form-select">
                                        <option value="">-- Pilih Jenis Layanan --</option>
                                        @foreach($services as $s)
                                        <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-name="{{ $s->service_name }}">
                                            {{ $s->service_name }} (Rp {{ number_format($s->price, 0, ',', '.') }}/kg)
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="input-group">
                                        <input type="number" id="temp_qty" class="form-control" step="0.1" placeholder="Berat">
                                        <span class="input-group-text bg-white">Kg</span>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <input type="text" id="temp_notes" class="form-control" placeholder="Catatan...">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <button type="button" class="btn btn-success w-100 fw-bold shadow-sm" id="btnTambahItem">
                                        <i class="bi bi-plus-lg"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Item Keranjang -->
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm" id="tabelKeranjang">
                            <thead class="table-light">
                                <tr>
                                    <th>Layanan</th>
                                    <th>Berat (Kg)</th>
                                    <th>Catatan</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Item dimasukkan lewat Javascript -->
                            </tbody>
                            <tfoot>
                                <!-- <tr>
                                    <td colspan="3" class="text-end text-muted">Pajak Tambahan (10%)</td>
                                    <td id="tax_display" class="text-end text-danger">Rp 0</td>
                                    <td></td>
                                </tr> -->
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5">TOTAL TAGIHAN</td>
                                    <td id="total_display" class="text-end fw-bold text-success fs-5">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"><i class="bi bi-cloud-arrow-up"></i> Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let keranjang = [];

    document.getElementById('btnTambahItem').addEventListener('click', function() {
        const serviceSelect = document.getElementById('temp_service');
        const qtyInput = document.getElementById('temp_qty');
        const notesInput = document.getElementById('temp_notes');

        if(!serviceSelect.value || !qtyInput.value) {
            alert('Jenis Layanan dan Berat (Kg) wajib diisi untuk memasukkan item!');
            return;
        }

        const id_service = serviceSelect.value;
        const price = parseFloat(serviceSelect.options[serviceSelect.selectedIndex].getAttribute('data-price'));
        const name = serviceSelect.options[serviceSelect.selectedIndex].getAttribute('data-name');
        const qty = parseFloat(qtyInput.value);
        const notes = notesInput.value;
        const subtotal = price * qty;

        // Tambah ke memori array JS
        keranjang.push({ id_service, name, qty, price, notes, subtotal });
        
        // Bersihkan input sementara
        serviceSelect.value = '';
        qtyInput.value = '';
        notesInput.value = '';

        // Render ulang tampilan tabel
        renderKeranjang();
    });

    function renderKeranjang() {
        const tbody = document.querySelector('#tabelKeranjang tbody');
        tbody.innerHTML = '';
        let totalSubtotal = 0;

        keranjang.forEach((item, index) => {
            totalSubtotal += item.subtotal;
            tbody.innerHTML += `
                <tr>
                    <td>
                        ${item.name}
                        <input type="hidden" name="items[${index}][id_service]" value="${item.id_service}">
                        <input type="hidden" name="items[${index}][notes]" value="${item.notes}">
                    </td>
                    <td>
                        ${item.qty} Kg
                        <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                    </td>
                    <td>${item.notes || '-'}</td>
                    <td class="text-end">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(${index})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
        });

        if (keranjang.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada pesanan layanan yang dimasukkan.</td></tr>';
        }

        // const tax = totalSubtotal * 0.1;
        const totalAkhir = totalSubtotal;

        // document.getElementById('tax_display').innerText = 'Rp ' + tax.toLocaleString('id-ID');
        document.getElementById('total_display').innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID');
    }

    window.hapusItem = function(index) {
        keranjang.splice(index, 1);
        renderKeranjang();
    }

    // Aksi submit dicegat untuk divalidasi tidak boleh kosong
    document.getElementById('formTransaksi').addEventListener('submit', function(e) {
        if(keranjang.length === 0) {
            e.preventDefault();
            alert('Gagal! Anda belum memasukkan pesanan layanan apa pun ke dalam keranjang. Silakan tambah layanan terlebih dahulu.');
        }
    });

    // Inisialisasi awal UI
    renderKeranjang();
</script>


<div class="modal fade" id="modalTambahCepat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Tambah Customer Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Customer</label>
                    <input type="text" id="ajax_customer_name" class="form-control" placeholder="Masukkan nama..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telp</label>
                    <input type="text" id="ajax_phone" class="form-control" placeholder="08xxxx" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea id="ajax_customer_address" class="form-control" rows="3" placeholder="Alamat lengkap..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanCustomer" class="btn btn-primary">Simpan Customer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('btnSimpanCustomer').addEventListener('click', function() {
        let name = document.getElementById('ajax_customer_name').value;
        let phone = document.getElementById('ajax_phone').value;
        let address = document.getElementById('ajax_customer_address').value;

        if(!name || !phone || !address) {
            alert('Semua data wajib diisi!');
            return;
        }

        $.ajax({
            url: "{{ route('operator.customers.storeAjax') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                customer_name: name,
                phone: phone,
                customer_address: address
            },
            success: function(response) {
                if(response.success) {
                    let customer = response.customer;
                  
                    let newOption = new Option(customer.customer_name, customer.id, true, true);
                    $('#customer_id').append(newOption).trigger('change');

                  
                    $('#modalTambahCepat').modal('hide');
                    $('#ajax_customer_name').val('');
                    $('#ajax_phone').val('');
                    $('#ajax_customer_address').val('');
                    alert('Data Pelanggan Baru Berhasil Ditambahkan!');
                }
            },
            error: function(err) {
                alert('Terjadi kesalahan saat menyimpan data pelanggan.');
            }
        });
    });
</script>
@endsection