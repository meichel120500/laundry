<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'trans_order_details';

    protected $fillable = [
        'id_order',
        'id_service',
        'qty',
        'subtotal',
        'notes',
    ];

    public function service()
    {
        // Pastikan di database nama kolomnya adalah id_service
        return $this->belongsTo(TypeOfService::class, 'id_service');
    }


    public function order()
    {
        return $this->belongsTo(TransOrder::class, 'id_order');
    }
}
