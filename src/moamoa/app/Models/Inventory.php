<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'inventory_id';

    protected $fillable = [
        'child_id'
        ,'shop_id'
        ,'wear'
    ];

    public function child() {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'child_id');
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
