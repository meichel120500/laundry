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
            // Drop foreign key if needed (depend on driver, but usually safe to change if doctrine/dbal or laravel >= 10 native schema)
            $table->unsignedBigInteger('id_customer')->nullable()->change();
            
            $table->string('customer_name_non_member')->nullable();
            $table->string('customer_phone_non_member')->nullable();
            
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('set null');
            
            $table->integer('discount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['customer_name_non_member', 'customer_phone_non_member', 'voucher_id', 'discount']);
            $table->unsignedBigInteger('id_customer')->nullable(false)->change();
        });
    }
};
