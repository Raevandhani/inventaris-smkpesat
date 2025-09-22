<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = "maintenances";
    protected $fillable = [
        'item_id','quantity','notes',
        'start_at','finish_at','status'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'finish_at' => 'datetime',
    ];

    public function getStartAtFormattedAttribute()
    {
        return $this->start_at ? $this->start_at->format('d-m-Y H:i') : null;
    }

    public function getFinishAtFormattedAttribute()
    {
        return $this->finsih_at ? $this->finsih_at->format('d-m-Y H:i') : null;
    }

    public function item()
    {
        return $this->belongsTo(Items::class);
    }
}
