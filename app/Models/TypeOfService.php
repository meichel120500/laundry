<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfService extends Model
{
    protected $table = 'type_of_services'; 
protected $fillable = ['service_name', 'price', 'description'];
}
