<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pengambilan - {{ $order->order_code }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 14px; margin: 0; padding: 20px; }
        .receipt-container { width: 100%; max-width: 400px; margin: 0 auto; border: 1px dashed #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0 0 5px 0; }
        .header p { margin: 0; }
        .divider { border-top: 1px dashed #333; margin: 10px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .bold { font-weight: bold; }
        .text-center { text-align: center; }
        @media print {
            body { padding: 0; }
            .receipt-container { border: none; padding: 0; }
            .no-print { display: none; }
        }
        .btn-print { display: block; width: 100%; max-width: 400px; margin: 20px auto; padding: 10px; text-align: center; background: #0d6efd; color: white; text-decoration: none; border: none; font-size: 16px; cursor: pointer; border-radius: 5px; }
        .btn-back { display: block; width: 100%; max-width: 400px; margin: 10px auto; text-align: center; padding: 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h3>SISTEM INFORMASI LAUNDRY</h3>
            <p>Struk Pembayaran & Pengambilan</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="row">
            <span>No. TRX</span>
            <span>{{ $order->order_code }}</span>
        </div>
        <div class="row">
            <span>Tanggal Order</span>
            <span>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</span>
        </div>
        <div class="row">
            <span>Pelanggan</span>
            <span>{{ $order->customer ? $order->customer->customer_name : $order->customer_name_non_member }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="bold" style="margin-bottom: 5px;">Rincian Layanan:</div>
        @if($order->details->isEmpty())
            <div class="row">
                <span>{{ $order->service->service_name ?? 'Layanan' }} ({{ $order->order_qty }} Kg)</span>
            </div>
        @else
            @foreach($order->details as $detail)
            <div class="row">
                <span>{{ $detail->service->service_name ?? 'Layanan' }} ({{ $detail->qty }} Kg)</span>
                <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        @endif
        
        <div class="divider"></div>
        
        <div class="row">
            <span>Pajak (10%)</span>
            <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
        </div>
        @if($order->discount > 0)
        <div class="row">
            <span>Diskon Voucher</span>
            <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="row bold mt-2">
            <span>Total Tagihan</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="row">
            <span>Uang Bayar</span>
            <span>Rp {{ number_format($order->order_pay, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span>Kembalian</span>
            <span>Rp {{ number_format($order->order_change, 0, ',', '.') }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="text-center" style="margin-top: 15px;">
            <p><strong>LUNAS & SUDAH DIAMBIL</strong></p>
            <p style="font-size: 12px;">Terima kasih telah mempercayakan pakaian Anda di Laundry Kami.</p>
            <p style="font-size: 12px; margin-top: 10px;">Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
    
    <button onclick="window.print()" class="btn-print no-print">Cetak Struk</button>
    <a href="{{ route('pickups.index') }}" class="btn-back no-print">Kembali ke Daftar Pengambilan</a>

    <script>
        // Otomatis muncul dialog print saat halaman di load
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
