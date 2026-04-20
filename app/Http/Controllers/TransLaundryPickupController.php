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
        $orders = TransOrder::with(['customer', /* 'service', */ 'details.service'])->where('order_status', 0)->get();
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
        if ($request->has('ambil_barang')) {
            $order->order_status = 1; // 1 = Sudah Diambil
            $pesan = 'Pembayaran berhasil & barang telah diambil!';
        } else {
            $pesan = 'Pembayaran berhasil! Barang masih di toko.';
        }

        $order->save();

        return redirect()->route('pickups.index')
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
            return back()->with('success', 'Pakaian telah berhasil diambil oleh pelanggan!');
        }

        return back()->with('error', 'Data transaksi tidak ditemukan.');
    }
}