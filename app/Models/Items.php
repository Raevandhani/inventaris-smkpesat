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
        "borrowed",
        "maintenance",
        "others",
        "total_stock",
        "status",
    ];

    public function getAvailableAttribute()
    {
        return $this->total_stock - $this->borrowed - $this->maintenance - $this->others;
    }

    public function getUnavailableAttribute()
    {
        return $this->borrowed + $this->maintenance + $this->others;
    }

    protected static function booted()
    {
        static::saving(function ($item) {
            $available = $item->total_stock - $item->borrowed - $item->maintenance - $item->others;

            if ($available <= 0) {
                $item->status = false;
            } else {
                $item->status = true;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'item_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'item_id');
    }
}
