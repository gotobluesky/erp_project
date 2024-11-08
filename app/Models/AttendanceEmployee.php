<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
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
       
        $sunday = null; // Initialize the Sunday variable
        $i = 0;
        while ($start < $end) {
            $dayOfWeek = $start->format('l');
    
            if ($dayOfWeek == "Sunday") {
                $sunday = clone $start; // Clone the DateTime object when we find a Sunday
              
            }
    
            $start->modify('+1 day'); // Move to the next day
            $i += 1;
        }
        
      // Move $start back by the total number of iterations
        $start->modify('-' . $i . ' days');
         
        $attendanceEmployees = AttendanceEmployee::where('employee_id', $employee_id)->where('date', '>=', $start)->where('date', '<=', $end)->get();
       
        $Asistidos = 0;
        $result["sunday"]=0;
        foreach ( $attendanceEmployees as $value){
           
            if($value->date==$sunday->format("Y-m-d")){
                if ($value->clock_in!=null){
                    $result["sunday"]=1;
                    $Asistidos ++;
                }
            }else{
                if ($value->clock_in!=null){
                    $Asistidos ++;
                }
            }
        }
        
        $Extra = $Asistidos*(1/6);
        
        $laboradorados = $Asistidos + $Extra;
        $result["labor"]=$laboradorados;
        $result["Asistidos"]=$Asistidos;
        return $result;
    }
     public function calculateovertime($employee_id, $start, $end){
         
         $attendanceEmployees = AttendanceEmployee::where('employee_id', $employee_id)->where('date', '>=', $start)->where('date', '<=', $end)->get();
         
         $totalovertime=0;
         foreach ( $attendanceEmployees as $value){
             
            list($hours, $minutes, $seconds) = explode(":", $value->overtime);

            
            $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
           
            if($totalSeconds>0){
                 $totalovertime+=$totalSeconds;
            }
        }
        
        return $totalovertime/3600;
    }
}
