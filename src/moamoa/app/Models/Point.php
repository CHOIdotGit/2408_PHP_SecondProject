<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'point_id';

    protected $fillable = [
        'child_id'
        ,'point'
        ,'point_code'
        ,'payment_at'
        ,'point_flg'
    ];

    public function child() {
        return $this->belongsTo(Child::class, 'child_id');
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
