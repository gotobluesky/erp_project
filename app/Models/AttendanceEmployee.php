<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceEmployee extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'clock_in',
        'clock_out',
        'late',
        'early_leaving',
        'overtime',
        'total_rest',
        'created_by',
    ];

    public function employees()
    {
        return $this->hasOne('App\Models\Employee', 'user_id', 'employee_id');
    }

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }
    public function calculateworkingtime($employee_id, $start, $end){
        // return 1;
       
        $attendanceEmployees = AttendanceEmployee::where('employee_id', $employee_id)->whereBetween('date', [$start, $end])->get();
        // $attendanceEmployees = AttendanceEmployee::where('employee_id', 136)
        // ->whereBetween('date', ['2024-10-19', '2024-10-25'])
        // ->get();
      
        $Asistidos = 0;
        foreach ( $attendanceEmployees as $value){
            if ($value->clock_in!=null){
                $Asistidos ++;
            }
        }
         
        $Extra = $Asistidos*(1/6);
        $laboradorados = $Asistidos + $Extra;
        $result["labor"]=$laboradorados;
        $result["Asistidos"]=$Asistidos;
        return $result;
    }
}
