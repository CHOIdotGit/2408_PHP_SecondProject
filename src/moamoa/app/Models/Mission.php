<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mission extends Model {
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'mission_id';

    protected $fillable = [
        'parent_id'
        ,'child_id'
        ,'category'
        ,'title'
        ,'content'
        ,'amount'
        ,'status'
        ,'start_at'
        ,'end_at'
    ];
}