<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'transaction_id';
    
    protected $fillable = [
        'parent_id'
        ,'child_id'
        ,'category'
        ,'transaction_code'
        ,'amount'
        ,'transaction_date'
        ,'memo'
    ];
}