<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model {
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'income_id';

    protected $fillable = [
        'parent_id',
        'child_id',
        'in_id',
        'income_code',
        'amount',
        'transaction_date',
        'memo',
    ];
}
