<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrophyList extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'trophy_list_id';

    protected $fillable = [
        'trophy_id'
        ,'quest_item'
        ,'daily_quest_item'
    ];

    // public function child() {
    //     return $this->belongsTo(Child::class, 'child_id');
    // }

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
