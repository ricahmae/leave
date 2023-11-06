<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveType;
use App\Models\EmployeeProfile;
use App\Models\LeaveType as ModelsLeaveType;
use App\Models\RequirementLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            
           $requirements =Requirement::all(); 
             return response()->json(['data' => $requirements ], Response::HTTP_OK);
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
            $requirement = new Requirement();
            $requirement->name = ucwords($request->name);
            $requirement->description = $request->description;
            $requirement->save();

            $requirement_log = new RequirementLog();
            $requirement_log->requirement_id = $requirement->id;
            $requirement_log->action_by = '1';
            $requirement_log->action_name = 'Add';
            $requirement_log->save();


            return response()->json(['data' => 'Success'], Response::HTTP_OK);
        }catch(\Throwable $th){
           
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id,Requirement $requirement)
    {
        try{
            $data = Requirement::find($id);

            return response() -> json(['data' => $data], 200);
        }catch(\Throwable $th){
           
            return response() -> json(['message' => $th -> getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requirement $requirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,Request $request)
    {
        try{
            $requirement = Requirement::findOrFail($id);
            $requirement->name = ucwords($request->name);
            $requirement->description = $request->description;
            $requirement->update();

            $requirement_log = new RequirementLog();
            $requirement_log->requirement_id = $requirement->id;
            $requirement_log->action_by = '1';
            $requirement_log->action_name = 'Update ';
            $requirement_log->save();


          
            return response() -> json(['data' => "Success"], 200);
        }catch(\Throwable $th){
           
            return response() -> json(['message' => $th -> getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requirement $requirement)
    {
        //
    }

    public function deactivateLeaveType(Request $request,$leave_type_id)
    {
        try{
            $user_id = Auth::user()->id;
            $user = EmployeeProfile::where('id','=',$user_id)->first();
            $user_password=$user->password;
            $password=$request->password;
            if($user_password==$password)
            {
                $deactivate_leave_type = ModelsLeaveType::findOrFail($leave_type_id);
                $deactivate_leave_type->status="deactivated";
                $deactivate_leave_type->reason=$request->reason;
                $deactivate_leave_type->update();
                $process_name="Deactivate";
                $leave_type_logs = $this->storeLeaveTypeLog($leave_type_id,$process_name);
                return response()->json(['data' => 'Success'], Response::HTTP_OK);
            }
           
            
        }catch(\Throwable $th){
         
            return response()->json(['message' => $th->getMessage()], 500);
        }
        
    }

    public function reactivateLeaveType(Request $request,$leave_type_id)
    {
        try{
            $user_id = Auth::user()->id;
            $user = EmployeeProfile::where('id','=',$user_id)->first();
            $user_password=$user->password;
            $password=$request->password;
            if($user_password==$password)
            {
                $deactivate_leave_type = LeaveType::findOrFail($leave_type_id);
                $deactivate_leave_type->status="active";
                $deactivate_leave_type->reason=$request->reason;
                $deactivate_leave_type->update();
                $process_name="Reactivate";
                $leave_type_logs = $this->storeLeaveTypeLog($leave_type_id,$process_name);
                return response()->json(['data' => 'Success'], Response::HTTP_OK);
            }
           
            
        }catch(\Throwable $th){
         
            return response()->json(['message' => $th->getMessage()], 500);
        }
        
    }
}
