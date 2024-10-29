<?php

namespace App\Http\Controllers;

use App\Models\AccountList;
use App\Models\Allowance;
use App\Models\AllowanceOption;
use App\Models\AttendanceEmployee;
use App\Models\Commission;
use App\Models\DeductionOption;
use App\Models\Employee;
use App\Models\Isr2024Weekly;
use App\Models\Loan;
use App\Models\LoanOption;
use App\Models\OtherPayment;
use App\Models\Overtime;
use App\Models\PaySlip;
use App\Models\PayslipType;
use App\Models\SaturationDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use DateTime;
class SetSalaryController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Set Salary')) {
            $employees = Employee::where(
                [
                    'created_by' => \Auth::user()->creatorId(),
                ]
            )->get();
            $labor=new AttendanceEmployee();
            $startend=$this->getweek();
            foreach($employees as $employee){
                $result=$labor->calculateworkingtime( $employee->id, $startend["start"], $startend["end"]);

                $employee->net_salary=$employee->salary * $result["labor"];

            }
            
            return view('setsalary.index', compact('employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Edit Set Salary')) {

            $payslip_type      = PayslipType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $allowance_options = AllowanceOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $loan_options      = LoanOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $deduction_options = DeductionOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if (\Auth::user()->type == 'employee') {
                $currentEmployee      = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $allowances           = Allowance::where('employee_id', $currentEmployee->id)->get();
                $commissions          = Commission::where('employee_id', $currentEmployee->id)->get();
                $loans                = Loan::where('employee_id', $currentEmployee->id)->get();
                $saturationdeductions = SaturationDeduction::where('employee_id', $currentEmployee->id)->get();
                $otherpayments        = OtherPayment::where('employee_id', $currentEmployee->id)->get();
                $overtimes            = Overtime::where('employee_id', $currentEmployee->id)->get();
                $employee             = Employee::where('user_id', '=', \Auth::user()->id)->first();

                return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances'));
            } else {
                $allowances           = Allowance::where('employee_id', $id)->get();
                $commissions          = Commission::where('employee_id', $id)->get();
                $loans                = Loan::where('employee_id', $id)->get();
                $saturationdeductions = SaturationDeduction::where('employee_id', $id)->get();
                $otherpayments        = OtherPayment::where('employee_id', $id)->get();
                $overtimes            = Overtime::where('employee_id', $id)->get();
                $employee             = Employee::find($id);

                return view('setsalary.edit', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function getweek(){
        $currentDate = new DateTime();
        $sevenDaysAgo = clone $currentDate; // Clone to avoid modifying the original object
        $sevenDaysAgo->modify('-6 days'); // Subtract 7 days
        $start=$sevenDaysAgo->format('Y-m-d'); 
        $end=$currentDate->format('Y-m-d');
        $result['start']= $start;
        $result['end']=$end;
        return $result;
    }
    public function show($id)
    {

        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $payslip_type      = PayslipType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $allowance_options = AllowanceOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $loan_options      = LoanOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $deduction_options = DeductionOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
       
        // foreach ($attendanceemployee as $attendance){
        //     $this->formatTimeToString($attendance->late) + $this->formatTimeToString($attendance->late); 
        // }

        $currentDate = new DateTime();
        // Clone the DateTime object to get the date 7 days ago
        $sevenDaysAgo = clone $currentDate; // Clone to avoid modifying the original object
        $sevenDaysAgo->modify('-7 days'); // Subtract 7 days
        $start=$sevenDaysAgo->format('Y-m-d'); 
        $end=$currentDate->format('Y-m-d');

        if (\Auth::user()->type == 'employee') {
            $currentEmployee      = Employee::where('user_id', '=', \Auth::user()->id)->first();
            $allowances           = Allowance::where('employee_id', $currentEmployee->id)->get();
            $commissions          = Commission::where('employee_id', $currentEmployee->id)->get();
            $loans                = Loan::where('employee_id', $currentEmployee->id)->get();
            $otherpayments        = OtherPayment::where('employee_id', $currentEmployee->id)->get();
            $overtimes            = Overtime::where('employee_id', $currentEmployee->id)->get();
            $employee             = Employee::where('user_id', '=', \Auth::user()->id)->first();

            $startend=$this->getweek();
            $basedataforsalary = $this->calculatEvaluate($employee, $startend["start"], $startend["end"]);

            $saturationdeductions = SaturationDeduction::where('employee_id', $id)->orwhere(function($query){
                $query->where('title', 'IMSS')->orwhere('title', 'ISR')->orwhere('title','Subsidio');
            })->get();
            // $this->calculatEvaluate($employee);
            // var_dump($this->calculatEvaluate($employee)); die();
            foreach ($allowances as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($commissions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($loans as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($saturationdeductions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($otherpayments as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments','basedataforsalary', 'saturationdeductions', 'loans', 'deduction_options', 'allowances'));
        } else {
            $allowances           = Allowance::where('employee_id', $id)->get();
            $commissions          = Commission::where('employee_id', $id)->get();
            $loans                = Loan::where('employee_id', $id)->get();
            $otherpayments        = OtherPayment::where('employee_id', $id)->get();
            $overtimes            = Overtime::where('employee_id', $id)->get();
            $employee             = Employee::find($id);
            
            $startend=$this->getweek();
            $basedataforsalary = $this->calculatEvaluate($employee, $startend["start"], $startend["end"]);

            // $saturationdeductions = SaturationDeduction::where('employee_id', $id)->get();
            $saturationdeductions = SaturationDeduction::where('employee_id', $id)->orwhere(function($query){
                $query->where('title', 'IMSS')->orwhere('title', 'ISR')->orwhere('title','Subsidio');
            })->get();


            foreach ($allowances as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($commissions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($loans as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($saturationdeductions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($otherpayments as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes','basedataforsalary', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances'));
        }
    }
    public function calculatEvaluate($employee, $start, $end){
        $Vacationday = 12;
        $Vacationbonus = 0.25;
        $Aguinaldo = 12;
        $Daypyear = 365;
        $Totaldays = $Aguinaldo + $Daypyear; 
        $FDI =$Totaldays/ $Daypyear; //1.0328767

        $SDI = floatval($employee-> salary) * $FDI;
        $UMA3 = 325.71;
        $diferencia=0;
        if($UMA3 > $SDI){
            $diferencia = $SDI - $UMA3;
        }

       
        // Format and display the date from 7 days ago

        // $attendanceemployee = AttendanceEmployee::where('employee_id', $employee->id)->whereBetween('date', [$start, $end])->get();
        // $Asistidos = 0;
        // foreach ( $attendanceemployee as $value){
        //     if ($value->clock_in!=null){
        //         $Asistidos ++;
        //     }
        // }
        // $Extra = $Asistidos*(1/6);
        // $labor = $Asistidos + $Extra;

        $labor=new AttendanceEmployee();
        $result=$labor->calculateworkingtime( $employee->id, $start, $end);
        
        $importe = $diferencia * $result["Asistidos"];
        $excedentepatro = 0.4;
        $cuotacorre = $importe * $excedentepatro;
        $excedentpatron = 0.40 * $excedentepatro;

        $prestaciodiner = 0.25 * ($SDI * $result["labor"]);
        $prestacioespec = 0.375 * ($SDI *$result["labor"]);
        $invalidday = 0.625 * ($SDI * $result["labor"]);
        $guarderyprestasoc = 1.125 * ($SDI * $result["labor"]);

        $totalimss = ($excedentpatron +  $prestaciodiner +  $prestacioespec +  $invalidday + $guarderyprestasoc);

        $baseValueIsr = Isr2024Weekly::where('limif', '<', $employee->salary* $result["labor"])
                                    ->where('limsu', '>', $employee->salary* $result["labor"])
                                    ->get();
        $Isr = floatval($baseValueIsr[0]->cuota) + ((floatval($employee->saltots)-floatval($baseValueIsr[0]->limif))*floatval($baseValueIsr[0]->porcen));
        
        if (floatval($employee->salary)*$result["labor"]<2271){
            $subsidio =89.00;
        }else{
            $subsidio =0;
        }
      
        $baseVals["imss"] =$totalimss;
        $baseVals["isr"] =$Isr;
        $baseVals["subsidio"] =$subsidio;

        $saturationdeductions = new SaturationDeduction();
        $saturationdeductions->updatedata($baseVals);
        return 1;

    }

    public function employeeUpdateSalary(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'salary_type' => 'required',
                'salary' => 'required',
                'saltots' => 'required',
                'account_type' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $employee = Employee::findOrFail($id);
        // var_dump($input);
        $employee->salary_type = $request -> salary_type;
        $employee->salary = $request -> salary;
        $employee->saltots = $request -> saltots;
        $employee->account_type = $request -> account_type;
        $employee -> save();
        
        // $payslip=PaySlip::where("employeer_id", "=", $id);
        // var_dump($payslip); die();
        return redirect()->back()->with('success', 'Employee Salary Updated.');
    }

    public function employeeSalary()
    {
        if (\Auth::user()->type == "employee") {

            $employees = Employee::where('user_id', \Auth::user()->id)->get();

            return view('setsalary.index', compact('employees'));
        }
    }

    public function employeeBasicSalary($id)
    {

        $payslip_type = PayslipType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $payslip_type->prepend('Select Payslip Type', '');
        $accounts = AccountList::where('created_by', \Auth::user()->creatorId())->get()->pluck('account_name', 'id');
        $accounts->prepend('Select Account Type', '');

        $employee     = Employee::find($id);

        return view('setsalary.basic_salary', compact('employee', 'payslip_type', 'accounts'));
    }
    public function formatTimeToString($time) {
        // Split the time string by colon
        // Split the time string by colon
        $timeParts = explode(':', $time);
        
        // Set default values
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        
        // Assign values from timeParts based on the number of parts
        if (count($timeParts) === 3) {
            $hours = (int)$timeParts[0];
            $minutes = (int)$timeParts[1];
            $seconds = (int)$timeParts[2];
        } elseif (count($timeParts) === 2) {
            $minutes = (int)$timeParts[0];
            $seconds = (int)$timeParts[1];
        } elseif (count($timeParts) === 1) {
            $seconds = (int)$timeParts[0];
        } else {
            throw new InvalidArgumentException("Invalid time format. Expected format is 'HH:MM:SS', 'MM:SS', or 'SS'");
        }

        // Calculate total seconds
        $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
        
        return $totalSeconds;
    }
    
    
}
