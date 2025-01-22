<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trophy extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'trophy_id';

    protected $fillable = [
        'child_id'
        ,'quest'
        ,'daily_quest'
    ];

    public function child() {
        return $this->belongsTo(Child::class, 'child_id');
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
