<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ParentModel extends Model {
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'parent_id';
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
}
