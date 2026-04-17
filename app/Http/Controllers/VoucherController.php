<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code',
            'discount_percent' => 'required|numeric|min:1|max:100',
        ]);

        Voucher::create([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount_percent' => 'required|numeric|min:1|max:100',
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }

    public function checkAjax(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'customer_id' => 'required' 
        ]);

        $voucher = Voucher::where('code', $request->code)->where('is_active', true)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan atau tidak aktif.']);
        }

        // Cek apakah customer_id ini sudah pernah mendapatkan diskon dari voucher INI khusus
        $used = \App\Models\TransOrder::where('id_customer', $request->customer_id)
                    ->where('voucher_id', $voucher->id)
                    ->exists();

        if ($used) {
            return response()->json(['success' => false, 'message' => 'Anda sudah pernah menggunakan kode voucher ini. Voucher hanya bisa digunakan 1x per member.']);
        }

        return response()->json([
            'success' => true, 
            'voucher' => $voucher,
            'message' => 'Voucher valid! Diskon ' . $voucher->discount_percent . '% diterapkan.'
        ]);
    }
}
