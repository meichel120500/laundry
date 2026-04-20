<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            // Tambahkan kolom status pembayaran: 0 = Belum Bayar, 1 = Sudah Bayar
            $table->tinyInteger('payment_status')->default(0)->after('order_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
