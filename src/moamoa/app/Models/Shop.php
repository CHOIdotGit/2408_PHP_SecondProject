<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'shop_id';

    protected $fillable = [
        'price'
        ,'medal'
        ,'theme'
    ];

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
