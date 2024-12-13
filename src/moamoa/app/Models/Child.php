<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Child extends Model {
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'child_id';

    protected $fillable = [
        'parent_id'
        ,'account'
        ,'password'
        ,'name'
        ,'nick_name'
        ,'email'
        ,'tel'
        ,'profile'
    ];
    
    protected $hidden = [
        'password'
    ];

    public function missionList() {
        return $this->hasMany(Mission::class, 'mission_id')->select('title');
    }
}
