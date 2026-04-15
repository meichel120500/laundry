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
        // Ambil order yang statusnya masih 1 (Proses)
        $orders = TransOrder::with('customer', 'service')->where('order_status', 1)->get();
        return view('operator.pickups.index', compact('orders'));
    }

    /**
     * Proses pembayaran: simpan uang bayar, hitung kembalian, ubah status jadi Selesai
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
        $order->order_status = 2; // 2 = Selesai / Sudah Diambil
        $order->save();

        return redirect()->route('pickups.index')
                         ->with('success', 'Pembayaran berhasil! Kembalian: Rp ' . number_format($order->order_change, 0, ',', '.'));
    }

    /**
     * Fungsi lama untuk update status saja (tanpa bayar)
     */
    public function updateStatus($id)
    {
        $order = TransOrder::find($id);

        if ($order) {
            $order->order_status = 2;
            $order->save();
            return back()->with('success', 'Pakaian telah berhasil diambil!');
        }

        return back()->with('error', 'Data tidak ditemukan.');
    }
}