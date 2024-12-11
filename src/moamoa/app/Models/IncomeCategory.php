<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeCategory extends Model {
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'in_id';

    protected $fillable = [
        'in_type',
        'in_name',
        'in_img',
    ];
}
