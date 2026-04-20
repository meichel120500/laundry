<?php

namespace App\Http\Controllers;

use App\Models\TransOrder;
use Illuminate\Http\Request;

class TransLaundryPickupController extends Controller
{
    /**
     * Menampilkan daftar cucian yang siap diambil
     */
    public function index()
    {
        // Ambil order yang statusnya masih 0 (Baru)
        $orders = TransOrder::with(['customer', 'service', 'details.service'])->where('order_status', 0)->get();
        return view('operator.pickups.index', compact('orders'));
    }

    /**
     * Proses pembayaran: simpan uang bayar, hitung kembalian, optionally ubah status jadi Selesai
     */
    public function bayar(Request $request)
    {
        $request->validate([
            'order_id'  => 'required|exists:trans_orders,id',
            'order_pay' => 'required|numeric|min:0',
        ]);

        $order = TransOrder::findOrFail($request->order_id);

        // Pastikan uang bayar cukup
        if ($request->order_pay < $order->total) {
            return back()->with('error', 'Uang bayar kurang dari total tagihan!');
        }

        // Simpan pembayaran dan hitung kembalian
        $order->order_pay    = $request->order_pay;
        $order->order_change = $request->order_pay - $order->total;
        $order->payment_status = 1; // 1 = Sudah Bayar

        // Jika checkbox 'ambil_barang' ada, maka status order jadi Selesai (1)
        $isPickedUp = $request->has('ambil_barang');
        if ($isPickedUp) {
            $order->order_status = 1; // 1 = Selesai/Diambil
        }
        $order->save();

        // Rekam pengambilan (jika langsung diambil)
        if ($isPickedUp) {
            \App\Models\TransLaundryPickup::create([
                'id_order'    => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => now(),
                'notes'       => 'Selesai: Rp ' . number_format($request->order_pay, 0, ',', '.')
            ]);
        }

        $pesan = $isPickedUp ? 'Pembayaran berhasil & barang telah diambil!' : 'Pembayaran berhasil! Barang masih di toko.';

        return redirect()->route('pickups.print', $order->id)
                         ->with('success', $pesan . ' Kembalian: Rp ' . number_format($order->order_change, 0, ',', '.'));
    }

    /**
     * Fungsi untuk serahkan pakaian (Ambil Saja)
     */
    public function updateStatus($id)
    {
        $order = TransOrder::find($id);

        if ($order) {
            // Pastikan sudah bayar sebelum diambil lewat fungsi ini
            if ($order->payment_status == 0) {
                return back()->with('error', 'Gagal! Pakaian harus dibayar terlebih dahulu sebelum diambil.');
            }

            $order->order_status = 1; // 1 = Sudah Diambil
            $order->save();

            // Rekam pengambilan
            \App\Models\TransLaundryPickup::create([
                'id_order'    => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => now(),
                'notes'       => 'Pengambilan Susulan (Sudah Bayar)'
            ]);

            return back()->with('success', 'Pakaian telah berhasil diambil oleh pelanggan!');
        }

        return back()->with('error', 'Data transaksi tidak ditemukan.');
    }

    /**
     * Tampilkan dan cetak struk
     */
    public function print_receipt($id)
    {
        $order = TransOrder::with(['customer', 'service', 'details.service'])->findOrFail($id);
        return view('operator.pickups.print', compact('order'));
    }
}