<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdepartment extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
        'department_id',
        'created_by',
    ];

    public function branch(){
        return $this->hasOne('App\Models\Branch','id','branch_id');
    }

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }
}

