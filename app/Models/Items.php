<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = "items";

    protected $fillable = [
        "name",
        "category_id",
        "condition",
        "available",
        "unavailable",
        "total_stock",
        "status",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'item_id');
    }
}
