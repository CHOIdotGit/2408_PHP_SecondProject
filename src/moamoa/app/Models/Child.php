<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Child extends Authenticatable {
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'child_id';
    protected $guards = 'children';
    protected $table = 'children';

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

    public function missions() {
        return $this->hasMany(Mission::class, 'child_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'child_id');
    }
    
    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
