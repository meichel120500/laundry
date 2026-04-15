<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            // Tambah kolom berat (kg) setelah id_service
            $table->decimal('order_qty', 8, 2)->default(0)->after('id_service');
        });
    }

    public function down(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            $table->dropColumn('order_qty');
        });
    }
};

