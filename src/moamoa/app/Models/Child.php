<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder; 

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
        ,'email'
        ,'tel'
        ,'profile'
        ,'app_state'
        ,'login_at'
    ];
    
    protected $hidden = [
        'password'
    ];

    public function missions() {
        return $this->hasMany(Mission::class, 'child_id');
    }

    public function points() {
        return $this->hasMany(Point::class, 'child_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'child_id', 'child_id');
    }

    public function saving_sign_ups() {
        return $this->hasMany(SavingSignUp::class, 'child_id', 'child_id');
    }

    public function parent() {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }
    
    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    protected static function booted(){
        static::addGlobalScope('app_state', function (Builder $builder) {
            $builder->where('app_state', 1);
        });
    }
    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->toDateString(); // 'YYYY-MM-DD' 형식으로 변환
    }
}
