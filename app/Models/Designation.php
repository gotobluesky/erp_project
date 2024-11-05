<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'branch_id','department_id', 'subdepartment_id','name','created_by'
    ];

    public function branch(){
        return $this->hasOne('App\Models\Branch','id','branch_id');
    }

    public function departments()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }
     public function subdepartments()
    {
        return $this->hasOne('App\Models\Subdepartment', 'id', 'subdepartment_id');
    }
}
