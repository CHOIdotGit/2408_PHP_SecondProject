<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingSignUp extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'saving_sign_up_id';

    protected $fillable = [
        'child_id'
        ,'saving_product_id'
        ,'saving_sign_up_deposit_at'
        ,'saving_sign_up_start_at'
        ,'saving_sign_up_end_at'
        ,'saving_sign_up_status'
    ];

    public function children() {
        return $this->belongsTo(Child::class, 'child_id', 'child_id');
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    public function saving_details() {
        return $this->hasMany(SavingDetail::class, 'saving_detail_id', 'saving_detail_id');
    }

    public function saving_products() {
        return $this->hasMany(SavingProduct::class, 'saving_product_id', 'saving_product_id');
    }
}
