<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ParentModel extends Authenticatable {
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'parent_id';
    protected $guards = 'parents';
    protected $table = 'parents';
    
    protected $fillable = [
        'account'
        ,'password'
        ,'name'
        ,'nick_name'
        ,'email'
        ,'tel'
        ,'profile'
        ,'family_code'
    ];
    
    protected $hidden = [
        'password'
    ];

    public function missions() {
        return $this->hasMany(Mission::class, 'parent_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'parent_id');
    }
    
    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
