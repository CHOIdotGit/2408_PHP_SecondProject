<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'saving_product_id';

    protected $fillable = [
        'saving_product_name'
        ,'saving_product_period'
        ,'saving_product_amount'
        ,'saving_product_interest_rate'
        ,'saving_product_type'
    ];

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    protected function SavingSignUp() {
        return $this->belongsTo(SavingProduct::class, 'saving_product_id');
    }
}
