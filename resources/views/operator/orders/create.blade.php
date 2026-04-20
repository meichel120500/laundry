@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><h5>Input Transaksi Baru</h5></div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST" id="formTransaksi">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="fw-bold">Tipe Pelanggan</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tipe-pelanggan" type="radio" name="customer_type" id="tipeMember" value="member" checked>
                                <label class="form-check-label" for="tipeMember">Pelanggan Member</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tipe-pelanggan" type="radio" name="customer_type" id="tipeNonMember" value="non_member">
                                <label class="form-check-label" for="tipeNonMember">Bukan Member</label>
                            </div>
                        </div>
                    </div>

                    <!-- Area Member -->
                    <div id="areaMember">
                        <div class="mb-3">
                            <label>Pilih Pelanggan <span class="badge bg-info d-none" id="badgeMemberBaru">Member Baru! (Diskon 5%)</span></label>
                            <div class="input-group">
                                <select name="customer_id" id="customer_id" class="form-control">
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

                        <div class="mb-3">
                            <label>Kode Voucher (Opsional)</label>
                            <div class="input-group">
                                <input type="text" id="voucher_code" name="voucher_code" class="form-control" placeholder="Masukkan kode voucher...">
                                <button type="button" class="btn btn-outline-primary" id="btnCekVoucher">Cek Voucher</button>
                            </div>
                            <small id="voucher_message" class="text-muted"></small>
                            <input type="hidden" id="voucher_id_applied" name="voucher_id">
                            <input type="hidden" id="voucher_discount_percent" value="0">
                        </div>
                    </div>

                    <!-- Area Non Member -->
                    <div id="areaNonMember" class="d-none">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Pelanggan / Nomor Identitas</label>
                                <input type="text" name="customer_name_non_member" id="customer_name_non_member" class="form-control" placeholder="Nama Pelanggan...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>No. Telp</label>
                                <input type="text" name="customer_phone_non_member" id="customer_phone_non_member" class="form-control" placeholder="08xxxx">
                            </div>
                        </div>
                        <div class="alert alert-secondary">
                            <i class="bi bi-info-circle"></i> Non-Member tidak dapat menggunakan fasilitas Voucher dan Diskon.
                        </div>
                    </div>

                    <!-- Indicator -->
                    <input type="hidden" id="is_new_member" name="is_new_member" value="0">

                    <div class="mb-4">
                        <label class="fw-bold"><i class="bi bi-calendar-check"></i> Perkiraan Selesai</label>
                        <input type="date" name="order_end_date" id="order_end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+2 days')) }}" required>
                        <small class="text-muted">Default: 2 hari dari sekarang.</small>
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
                                <tr>
                                    <td colspan="3" class="text-end text-muted">Pajak Tambahan (10%)</td>
                                    <td id="tax_display" class="text-end text-danger">Rp 0</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-muted">Diskon Tambahan</td>
                                    <td class="text-end text-success"><span id="discount_display">Rp 0</span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5">TOTAL TAGIHAN</td>
                                    <td id="total_display" class="text-end fw-bold text-success fs-5">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <input type="hidden" name="calculated_discount" id="calculated_discount" value="0">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"><i class="bi bi-cloud-arrow-up"></i> Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Cepat -->
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
    let keranjang = [];
    let newlyCreatedMemberId = null; // Simpan ID member baru yang baru saja didaftarkan

    // Fungsi untuk cek ke backend apakah customer belum pernah transaksi
    function checkAndApplyFirstTimeMember(customerId) {
        if (!customerId) {
            // Tidak ada customer dipilih, reset status
            setFirstTimeMemberStatus(false);
            return;
        }

        // Jika customer ini adalah yang baru saja dibuat via modal, langsung aktifkan tanpa AJAX
        if (newlyCreatedMemberId && customerId == newlyCreatedMemberId) {
            setFirstTimeMemberStatus(true);
            return;
        }

        // Cek ke backend untuk customer yang dipilih dari dropdown existing
        $.get("{{ url('/operator/check-customer-first-time') }}/" + customerId, function(res) {
            setFirstTimeMemberStatus(res.is_first_time);
        });
    }

    function setFirstTimeMemberStatus(isFirstTime) {
        if (isFirstTime) {
            document.getElementById('is_new_member').value = '1';
            document.getElementById('badgeMemberBaru').classList.remove('d-none');
        } else {
            document.getElementById('is_new_member').value = '0';
            document.getElementById('badgeMemberBaru').classList.add('d-none');
        }
        renderKeranjang();
    }

    // Event: Saat dropdown customer berubah
    document.getElementById('customer_id').addEventListener('change', function() {
        checkAndApplyFirstTimeMember(this.value);
        // Reset voucher jika ganti customer
        document.getElementById('voucher_id_applied').value = '';
        document.getElementById('voucher_discount_percent').value = '0';
        document.getElementById('voucher_message').innerText = '';
        renderKeranjang();
    });

    // Tipe Pelanggan Toggle
    document.querySelectorAll('.tipe-pelanggan').forEach(radio => {
        radio.addEventListener('change', function() {
            if(this.value === 'member') {
                document.getElementById('areaMember').classList.remove('d-none');
                document.getElementById('areaNonMember').classList.add('d-none');

                // Cek status member yang sedang dipilih di dropdown
                const currentCustomerId = document.getElementById('customer_id').value;
                checkAndApplyFirstTimeMember(currentCustomerId);
            } else {
                document.getElementById('areaMember').classList.add('d-none');
                document.getElementById('areaNonMember').classList.remove('d-none');
                // Reset voucher dan diskon saat beralih ke non-member
                document.getElementById('voucher_id_applied').value = '';
                document.getElementById('voucher_discount_percent').value = '0';
                document.getElementById('voucher_message').innerText = '';
                document.getElementById('is_new_member').value = '0';
                document.getElementById('badgeMemberBaru').classList.add('d-none');
                renderKeranjang();
            }
        });
    });

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

        keranjang.push({ id_service, name, qty, price, notes, subtotal });
        
        serviceSelect.value = '';
        qtyInput.value = '';
        notesInput.value = '';

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

        // Kalkulasi Berdasarkan Aturan:
        // Step 1: Harga Kotor (totalSubtotal)
        // Step 2: Pajak (10% dari Harga Kotor)
        const tax = totalSubtotal * 0.1;

        // Step 3: Diskon %
        let isNewMember = document.getElementById('is_new_member').value === '1';
        let tipePelanggan = document.querySelector('input[name="customer_type"]:checked').value;
        
        let discountPercent = 0;
        if(tipePelanggan === 'member') {
            if(isNewMember) discountPercent += 5;
            let voucherPercent = parseFloat(document.getElementById('voucher_discount_percent').value) || 0;
            discountPercent += voucherPercent;
        }

        const nominalDiskon = totalSubtotal * (discountPercent / 100);

        // Step 4: Total Akhir = (Harga Kotor + Pajak) - Nominal Diskon
        const totalAkhir = (totalSubtotal + tax) - nominalDiskon;

        document.getElementById('tax_display').innerText = 'Rp ' + tax.toLocaleString('id-ID');
        document.getElementById('discount_display').innerText = '- Rp ' + nominalDiskon.toLocaleString('id-ID') + ` (${discountPercent}%)`;
        document.getElementById('total_display').innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID');
        
        document.getElementById('calculated_discount').value = nominalDiskon;
    }

    window.hapusItem = function(index) {
        keranjang.splice(index, 1);
        renderKeranjang();
    }

    document.getElementById('formTransaksi').addEventListener('submit', function(e) {
        if(keranjang.length === 0) {
            e.preventDefault();
            alert('Gagal! Anda belum memasukkan pesanan layanan apa pun ke dalam keranjang. Silakan tambah layanan terlebih dahulu.');
            return;
        }
        
        let tipePelanggan = document.querySelector('input[name="customer_type"]:checked').value;
        if(tipePelanggan === 'member') {
            if(!document.getElementById('customer_id').value) {
                e.preventDefault();
                alert('Pilih pelanggan member terlebih dahulu!');
                return;
            }
        } else {
            if(!document.getElementById('customer_name_non_member').value || !document.getElementById('customer_phone_non_member').value) {
                e.preventDefault();
                alert('Nama dan Nomor Telepon pelanggan non-member wajib diisi!');
                return;
            }
        }
    });

    // Cek Voucher AJAX
    document.getElementById('btnCekVoucher').addEventListener('click', function() {
        let code = document.getElementById('voucher_code').value;
        let customer_id = document.getElementById('customer_id').value;

        if(!customer_id) {
            alert('Silakan pilih Pelanggan terlebih dahulu sebelum memasukkan voucher!');
            return;
        }

        if(!code) {
            alert('Masukkan kode voucher!');
            return;
        }

        $.ajax({
            url: "{{ route('operator.validateVoucher') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                code: code,
                customer_id: customer_id
            },
            success: function(res) {
                if(res.success) {
                    document.getElementById('voucher_message').innerHTML = `<span class="text-success fw-bold">${res.message}</span>`;
                    document.getElementById('voucher_id_applied').value = res.voucher.id;
                    document.getElementById('voucher_discount_percent').value = res.voucher.discount_percent;
                    renderKeranjang();
                } else {
                    document.getElementById('voucher_message').innerHTML = `<span class="text-danger fw-bold">${res.message}</span>`;
                    document.getElementById('voucher_id_applied').value = '';
                    document.getElementById('voucher_discount_percent').value = '0';
                    renderKeranjang();
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat memeriksa voucher.');
            }
        });
    });

    // Simpan Customer Cepat
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
                    
                    // Tandai sebagai member baru — simpan ID-nya agar persistensi diskon terjaga
                    newlyCreatedMemberId = customer.id;
                    document.getElementById('is_new_member').value = '1';
                    document.getElementById('badgeMemberBaru').classList.remove('d-none');
                    renderKeranjang();

                    alert('Pelanggan Baru Berhasil Ditambahkan. Diskon 5% sebagai member baru otomatis diaktifkan.');
                }
            },
            error: function(err) {
                alert('Terjadi kesalahan saat menyimpan data pelanggan.');
            }
        });
    });

    renderKeranjang();
</script>
@endsection