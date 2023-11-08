<?php

namespace App\Http\Controllers;

use App\Models\LeaveCredit;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeLeaveCredit;
use App\Http\Resources\EmployeeProfile;
use App\Http\Resources\LeaveCredit as ResourcesLeaveCredit;
use App\Models\DailyTimeRecord;
use App\Models\EmployeeLeaveCredit as ModelsEmployeeLeaveCredit;
use App\Models\EmployeeProfile as ModelsEmployeeProfile;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
class LeaveCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $leave_credits=[];
            
           $leave_credits =LeaveCredit::all();
           $leave_credit_resource=ResourcesLeaveCredit::collection($leave_credits);
           
             return response()->json(['data' => $leave_credit_resource], Response::HTTP_OK);
        }catch(\Throwable $th){
        
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function addMonthlyLeaveCredit()
    {
        $currentMonth = date('m');
        $currentDate = date('Y-m-d');
        $pastMonth = date('m', strtotime('-1 month'));
       // Subtract one month to get the last month
        $lastMonthDate = date('Y-m-d', strtotime('-1 month', strtotime($currentDate)));

        // Get the first day of the last month
        $firstDayOfLastMonth = date('Y-m-01', strtotime($lastMonthDate));

        // Get the last day of the last month
        $lastDayOfLastMonth = date('Y-m-t', strtotime($lastMonthDate));
        $currentDate = date('Y-m-d');
        // Subtract one month to get the last month
         $lastMonthDate = date('Y-m-d', strtotime('-1 month', strtotime($currentDate)));
         // Get the first day of the last month
         $firstDayOfLastMonth = date('Y-m-01', strtotime($lastMonthDate));
         // Get the last day of the last month
         $lastDayOfLastMonth = date('Y-m-t', strtotime($lastMonthDate));
         $employees = ModelsEmployeeProfile::with('biometric.dtr')
         ->get();
     
     foreach ($employees as $employee) {
             $leaveTypes = LeaveType::where('status', '=', 'special')->get();
                 foreach ($leaveTypes as $leaveType) {
                    $total_absences="1";
                    $total_undertime="5";
                     $month_credit_value = $leaveType->leave_credit_year/12;
                     $absent_credit_value = $leaveType->leave_credit_year/360 * $total_absences;
                     $deduct_credit_value = $month_credit_value - $absent_credit_value;

                     $employeeCredit = new ModelsEmployeeLeaveCredit();
                     $employeeCredit->leave_type_id = $leaveType->id;
                     $employeeCredit->employee_profile_id = $employee->id;
                     $employeeCredit->operation = "add";
                     $employeeCredit->credit_value = $month_credit_value;
                     $employeeCredit->date = now()->toDateString('Ymd');
                     $employeeCredit->save();

                     $employeeCredit = new ModelsEmployeeLeaveCredit();
                     $employeeCredit->leave_type_id = $leaveType->id;
                     $employeeCredit->employee_profile_id = $employee->id;
                     $employeeCredit->operation = "deduct";
                     $employeeCredit->credit_value = $deduct_credit_value;
                     $employeeCredit->date = now()->toDateString('Ymd');
                     $employeeCredit->save();


                 }
     }
        

   
        return response()->json(['data' => $add_credit_value], Response::HTTP_OK);

    }

    
    public function create()
    {
       
    }

 
    public function store(Request $request)
    {
        try{
            $leave_credit = new LeaveCredit();
            $leave_credit->day_value = $request->day_value;
            $leave_credit->month_value = $request->month_value;
            $leave_credit->save();

            return response()->json(['data' => 'Success'], Response::HTTP_OK);
        }catch(\Throwable $th){
           
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveCredit $leaveCredit)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveCredit $leaveCredit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,Request $request)
    {
        try{
            $leave_credit = LeaveCredit::findOrFail($id);
            $leave_credit->day_value = $request->day_value;
            $leave_credit->month_value = $request->month_value;
            $leave_credit->update();

          
            return response() -> json(['data' => "Success"], 200);
        }catch(\Throwable $th){
           
            return response() -> json(['message' => $th -> getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveCredit $leaveCredit)
    {
        //
    }

   
}

