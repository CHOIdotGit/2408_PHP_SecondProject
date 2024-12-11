<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MissionCategory extends Model {
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'ms_id';

    protected $fillable = [
        'ms_type',
        'ms_name',
        'ms_img',
    ];
}
