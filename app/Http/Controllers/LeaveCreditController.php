<?php

namespace App\Http\Controllers;

use App\Models\LeaveCredit;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveCredit as ResourcesLeaveCredit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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
