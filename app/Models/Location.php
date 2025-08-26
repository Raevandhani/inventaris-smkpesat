<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = "locations";
    
    protected $fillable = [
        'name'
    ];

    public function borrow()
    {
        return $this->hasMany(Borrow::class, 'location_id');
    }
}
