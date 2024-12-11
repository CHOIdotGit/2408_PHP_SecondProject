<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyCode extends Model {
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'code_id';

    protected $fillable = [
        'family_code',
    ];
}
