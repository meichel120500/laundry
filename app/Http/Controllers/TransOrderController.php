<?php

namespace App\Http\Controllers;

use App\Models\TransOrder;
use App\Models\Customer;
use App\Models\TypeOfService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransOrderController extends Controller
{
    // Tampil Daftar Transaksi
    public function index() {
        // Eager loading customer dan service agar performa cepat
        $orders = TransOrder::with(['customer', 'service'])->latest()->get();
        return view('operator.orders.index', compact('orders'));
    }

    // Form Tambah Transaksi
    public function create() {
        $customers = Customer::all();
        $services = TypeOfService::all();
        return view('operator.orders.create', compact('customers', 'services'));
    }

    // Simpan Transaksi
    public function store(Request $request)
    {
        // Validasi input sangat penting untuk Ujikom agar data tidak corrupt
        $request->validate([
            'customer_id' => 'required',
            'id_service'  => 'required',
            'qty'         => 'required|numeric|min:1',
        ]);

        // Cari data layanan untuk mengambil harganya
        $service = TypeOfService::findOrFail($request->id_service);
        $total = $service->price * $request->qty;

        TransOrder::create([
            'id_customer'    => $request->customer_id,
            'id_service'     => $request->id_service,
            'order_code'     => 'TRX-' . time(),
            'order_date'     => now(),
            'order_end_date' => now()->addDays(2),
            'order_status'   => 1,
            'order_qty'      => $request->qty,  // Simpan berat (kg)
            'order_pay'      => 0,
            'order_change'   => 0,
            'total'          => $total,
        ]);

        return redirect()->route('orders.index')->with('success', 'Transaksi Berhasil Disimpan!');
    }
}