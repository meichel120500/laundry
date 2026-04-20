<?php

namespace App\Http\Controllers;

use App\Models\TransOrder;
use App\Models\Customer;
use App\Models\TypeOfService;
use App\Models\TransOrderDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransOrderController extends Controller
{
    // Tampil Daftar Transaksi
    public function index() {
        // Eager loading customer dan service agar performa cepat
        $orders = TransOrder::with(['customer', /* 'service', */ 'details'])->latest()->get();
        $services = TypeOfService::all();
        return view('operator.orders.index', compact('orders', 'services'));
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
        $request->validate([
            'customer_id' => 'required',
            'items'       => 'required|array|min:1',
            'items.*.id_service' => 'required',
            'items.*.qty' => 'required|numeric|min:0.1',
        ]);

        $totalSubtotal = 0;
        $totalQty = 0;
        $orderDetails = [];

        // Hitung total dari semua items yang dimasukkan di keranjang
        foreach($request->items as $item) {
            $service = TypeOfService::findOrFail($item['id_service']);
            $price = $service->price;
            $qty = $item['qty'];
            $sub = $price * $qty;

            $totalSubtotal += $sub;
            $totalQty += $qty;

            $orderDetails[] = [
                'id_service' => $item['id_service'],
                'qty' => $qty,
                'subtotal' => $sub,
                'notes' => $item['notes'] ?? null,
            ];
        }

        // $pajak = $totalSubtotal * 0.1;
        $totalAkhir = $totalSubtotal;

        // Ambil services pertama untuk diisi ke `id_service` parent table
        // agar tidak null (demi kompatibilitas yang lama)
        $firstService = $request->items[0]['id_service'];

        $order = TransOrder::create([
            'id_customer'    => $request->customer_id,
            // 'id_service'     => $firstService,
            'order_code'     => 'TRX-' . time(),
            'order_date'     => now(),
            'order_end_date' => now()->addDays(2),
            'order_status'   => 0, // 0 = Baru
            'order_qty'      => $totalQty,  // Simpan total berat (kg)
            'order_pay'      => 0,
            'order_change'   => 0,
            // 'tax'            => $pajak,
            'total'          => $totalAkhir,
        ]);

        // Simpan setiap detail ke databse
        foreach($orderDetails as $detail) {
            TransOrderDetail::create([
                'id_order'   => $order->id,
                'id_service' => $detail['id_service'],
                'qty'        => $detail['qty'],
                'subtotal'   => $detail['subtotal'],
                'notes'      => $detail['notes'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Transaksi Berhasil Disimpan!');
    }

    // Tambah Layanan Order yang Ada
    public function addDetail(Request $request, $id)
    {
        $request->validate([
            'id_service'  => 'required',
            'qty'         => 'required|numeric|min:0.1',
            'notes'       => 'nullable|string',
        ]);

        $order = TransOrder::findOrFail($id);
        $service = TypeOfService::findOrFail($request->id_service);
        
        $price = $service->price; 
        $qty = $request->qty;
        $subtotal = $price * $qty;
        // $pajak = $subtotal * 0.1;
        $totalAkhir = $subtotal; // + $pajak;

        TransOrderDetail::create([
            'id_order'   => $order->id,
            'id_service' => $request->id_service,
            'qty'        => $qty,
            'subtotal'   => $subtotal,
            'notes'      => $request->notes,
        ]);

        // Update TransOrder total, qty, dan tax
        $order->total += $totalAkhir;
        // $order->tax += $pajak;
        $order->order_qty += $qty;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Layanan berhasil ditambahkan ke Transaksi TRX-' . $order->id);
    }

    // Laporan Penjualan untuk Pimpinan
    public function report()
    {
        $orders = TransOrder::with(['customer', /* 'service' */])->latest()->get();
        // Menghitung total penjualan
        $totalPenjualan = $orders->sum('total');
        
        return view('pimpinan.laporan', compact('orders', 'totalPenjualan'));
    }
}