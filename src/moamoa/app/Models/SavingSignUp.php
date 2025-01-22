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
        ,'saving_id'
        ,'start_at'
        ,'end_at'
    ];

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
