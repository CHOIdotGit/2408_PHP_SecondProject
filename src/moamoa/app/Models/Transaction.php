<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'transaction_id';
    
    protected $fillable = [
        'parent_id'
        ,'child_id'
        ,'title'
        ,'category'
        ,'transaction_code'
        ,'amount'
        ,'transaction_date'
        ,'memo'
    ];

    public function child() {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function parent() {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }
    
    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
