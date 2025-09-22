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
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function getBorrowDateFormattedAttribute()
    {
        return $this->borrow_date ? $this->borrow_date->format('d-m-Y H:i') : null;
    }

    public function getReturnDateFormattedAttribute()
    {
        return $this->return_date ? $this->return_date->format('d-m-Y H:i') : null;
    }

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
