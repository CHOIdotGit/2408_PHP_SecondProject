<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable {
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'code_id'
        ,'account'
        ,'password'
        ,'auth'
        ,'name'
        ,'nick_name'
        ,'gender'
        ,'email'
        ,'tel'
        ,'profile'
    ];
    
    protected $hidden = [
        'password',
        'refresh_token',
    ];
}
