<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransOrder extends Model
{
    use HasFactory;

    protected $table = 'trans_orders';

    protected $fillable = [
        'id_customer',
        'customer_name_non_member',
        'customer_phone_non_member',
        'voucher_id',
        'discount',
        'id_service',
        'order_code',
        'order_date',
        'order_end_date',
        'order_status',
        'order_qty',
        'order_pay',
        'order_change',
        'payment_status',
        'tax',
        'total'
    ];

    /**
     * Relasi ke tabel Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    /**
     * Relasi ke tabel TypeOfService
     * Ini yang tadi bikin error karena belum dibuat
     */
    public function service()
    {
        // Pastikan di database nama kolomnya adalah id_service
        return $this->belongsTo(TypeOfService::class, 'id_service');
    }

    /**
     * Relasi ke tabel TransOrderDetail
     */
    public function details()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order');
    }

    /**
     * Relasi ke tabel Voucher
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
}