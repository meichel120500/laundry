<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Pastikan nama kolom di sini SAMA PERSIS dengan di database
    protected $fillable = ['customer_name', 'phone', 'customer_address']; 
}