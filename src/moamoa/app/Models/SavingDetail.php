<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'saving_detail_id';

    protected $fillable = [
        'saving_sign_up_id'
        ,'deposit_at'
        ,'category'
        ,'principal'
    ];

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}