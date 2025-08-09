<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = "items";
    protected $fillable = [
        "name",
        "category",
        "condition",
        "available",
        "unavailable",
        "total_stock",
        "status",
    ];
}
