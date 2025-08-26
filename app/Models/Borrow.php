<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = [
        'user_id', 'item_id', 'location_id', 'quantity',
        'borrow_date', 'return_date', 'status'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
