<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'discount_percent',
        'is_active',
    ];

    public function orders()
    {
        return $this->hasMany(TransOrder::class, 'voucher_id');
    }
}
