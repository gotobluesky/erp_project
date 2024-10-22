<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdepartment extends Model
{
    protected $fillable = [
        'name',
        'department_id',
        'created_by',
    ];

    public function department(){
        return $this->belongsTo('App\Models\Department', 'department_id', 'id');
    }
}

