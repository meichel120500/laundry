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
     * Relasi ke tabel TransOrderDetail
     */
    public function details()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order');
    }
}