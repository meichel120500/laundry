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
        'id_service',
        'order_code',
        'order_date',
        'order_end_date',
        'order_status',
        'order_qty',
        'order_pay',
        'order_change',
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
}