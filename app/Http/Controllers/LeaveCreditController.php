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
 
        $currentDate = Carbon::now();
        $previousMonth = $currentDate->subMonth();
        $daysInPreviousMonth = $previousMonth->daysInMonth;

        $startDate = now()->subMonth()->firstOfMonth();
        $endDate = now()->subMonth()->endOfMonth();
       
        $results = ModelsEmployeeProfile::with('Dtr')
        ->whereHas('Dtr', function ($query) use ($startDate, $endDate) {
             $query->whereBetween('create_at', [$startDate, $endDate]);
             })
        ->get()
        ->map(function ($employee) use ($startDate, $endDate) {
             $total_minutes = $employee->Dtr
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('total_working_minutes')
        ->sum('total_working_minutes');

        return [
            'employee_profile_id' => $employee->id, // Change 'name' to the actual column name
            'total_minutes' => $total_minutes,
        ];

       
    });

    foreach ($results as $result) {
        $employee_id = $result['employee_profile_id'];
        $total_minutes = $result['total_minutes'];
        $leaveTypes = LeaveType::where('status', '!=', 'special')->get();
        foreach ($leaveTypes as $leaveType) {
            $year_credit_value = $leaveType->leave_credit_year/360;

            $employeeCredit = new ModelsEmployeeLeaveCredit();
            $employeeCredit->credit_value = $total_minutes;
            $employeeCredit->save();
        }
       
    }
        return response()->json(['data' => $daysInPreviousMonth], Response::HTTP_OK);

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
