<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveApplication as ResourcesLeaveApplication;
use App\Models\EmployeeLeaveCredit;
use App\Models\EmployeeProfile;
use App\Models\LeaveApplicationDateTime;
use App\Models\LeaveApplicationLog;
use App\Models\LeaveApplicationRequirement;
use Illuminate\Http\Request;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $file_service;
    public function __construct(
        FileService $file_service
    ) { 
        $this->file_service = $file_service; 
    }

    public function index()
    {
        try{ 
            $leave_applications=[];
            
           $leave_applications =LeaveApplication::all();
           $leave_application_resource=ResourcesLeaveApplication::collection($leave_applications);
           
             return response()->json(['data' => $leave_application_resource], Response::HTTP_OK);
        }catch(\Throwable $th){
        
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
   

    public function getLeaveApplications(Request $request)
    {
        $status = $request->status;  
        $leave_applications = [];   

        if($status == 'for-verification-hrmo'){
            $leave_applications = LeaveApplication::where('status', '=', 'for-verification-hrmo' );          
        }
        else if($status == 'for-approval-supervisor'){
            $leave_applications = LeaveApplication::where('status', '=', 'for-approval-supervisor' );
    
    
        }
        else if($status == 'for-approval-head'){
            $leave_applications = LeaveApplication::where('status', '=', 'for-approval-head' );

      
        }
        else if($status == 'declined'){
            $leave_applications = LeaveApplication::where('status', '=', 'declined');
                                                   
        }
        else if($status == 'approved'){
            $leave_applications = LeaveApplication::where('status', '=', 'approved');
                                                   
        }
        else{
            $leave_applications = LeaveApplication::where('status', '=', $status )
            ->whereHas('leave_application_logs', function($log) use ($status) {
                $log->whereAction($status);
            });
        }


        if (isset($request->search)) {
            $search = $request->search; 
            $leave_applications = $leave_applications->where('reference_number','like', '%' .$search . '%');
                                                 
            $leave_applications = isset($search) && $search; 
        }

        return ResourcesLeaveApplication::collection($leave_applications->paginate(50));
    }


    public function updateStatus (Request $request)
    {
        try {
                $user_id = Auth::user()->id;
                $user = EmployeeProfile::where('id','=',$user_id)->first();
                $user_password=$user->password;
                $password=$request->password;
                if($user_password==$password)
                {
                            $message_action = '';
                            $action = '';
                            $new_status = '';
                            $status = $request->status;

                            if($status == 'for-approval-supervisor' ){
                                $action = 'Aprroved by Supervisor';
                                $new_status='for-approval-head';
                                $message_action="Approved";
                            }
                            else if($status == 'for-approval-head'){
                                $action = 'Aprroved by Department Head';
                                $new_status='approved';
                                $message_action="Approved";
                            }
                            else if($status == 'for-verification-hrmo'){
                                $action = 'Verified by HRMO';
                                $new_status='verified';
                                $message_action="verified";
                            }
                            else{
                                $action = $status;
                            }
                            $leave_application_id = $request->leave_application_id;
                            $leave_applications = LeaveApplication::where('id','=', $leave_application_id)
                                                                    ->first();
                            if($leave_applications){
                            
                                $leave_application_log = new LeaveApplicationLog();
                                $leave_application_log->action = $action;
                                $leave_application_log->leave_application_id = $leave_application_id;
                                $leave_application_log->action_by = $user_id;
                                $leave_application_log->date = now()->toDateString('Ymd');
                                $leave_application_log->save();

                                $leave_application = LeaveApplication::findOrFail($leave_application_id);   
                                $leave_application->status = $new_status;
                                $leave_application->update();
                                    
                                return response(['message' => 'Application has been sucessfully '.$message_action, 'data' => $leave_application], Response::HTTP_CREATED); 
                                }
                }           
            }


         catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(),'error'=>true]);
        }
      
    }


    public function declineLeaveApplication(Request $request)
    {
        try {
                    $leave_application_id = $request->leave_application_id;
                    $leave_applications = LeaveApplication::where('id','=', $leave_application_id)
                                                            ->first();
                if($leave_applications)
                {
                        $user_id = Auth::user()->id;     
                        $user = EmployeeProfile::where('id','=',$user_id)->first();
                        $user_password=$user->password;
                        $password=$request->password;
                        if($user_password==$password)
                        {
                            if($user_id){
                                $leave_application_log = new LeaveApplicationLog();
                                $leave_application_log->action = 'declined';
                                $leave_application_log->leave_application_id = $leave_application_id;
                                $leave_application_log->date = now()->toDateString('Ymd');
                                $leave_application_log->action_by = $user_id;
                                $leave_application_log->save();

                                $leave_application = LeaveApplication::findOrFail($leave_application_id);
                                $leave_application->declined_at = now();
                                $leave_application->status = 'declined';
                                $leave_application->update();
                                return response(['message' => 'Application has been sucessfully declined', 'data' => $leave_application], Response::HTTP_CREATED);  
            
                            }
                         }
                }
            } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(),  'error'=>true]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        try{
            $leave_type_id = $request->leave_type_id;
            $user_id = Auth::user()->id;
            $user = EmployeeProfile::where('id','=',$user_id)->first();
            $user_status = $user->status;
            if($user_status == 'Permanent')
            {
                $employee_leave_credit=EmployeeLeaveCredit::where('employee_id','=',$user->id)
                                                    ->where('leave_type_id','=', $leave_type_id)
                                                    ->first(); 
                $total_leave_credit=$employee_leave_credit->total_leave_credit;
                    if($total_leave_credit > 0)
                    {
                            $leave_application = new Leaveapplication();
                            $leave_application->leave_type_id = $leave_type_id;
                            $leave_application->reference_number = $request->reference_number;
                            $leave_application->country = $request->country;
                            $leave_application->city = $request->city;
                            $leave_application->patient_type = $request->patient_type;
                            $leave_application->illnes = $request->illnes;
                            $leave_application->reason = $request->reason;
                            $leave_application->with_pay = $request->with_pay;
                            $leave_application->whole_day = $request->whole_day;
                            $leave_application->leave_credit_total = "2";
                            $leave_application->status = "for-verification-hrmo";
                            $leave_application->date = now()->toDateString('Ymd');
                            $leave_application->save();
                            
                            $leave_application_date_time = new LeaveApplicationDateTime();
                            $leave_application_date_time->date_from = $request->date_from;
                            $leave_application_date_time->date_to = $request->date_to;
                            $leave_application_date_time->time_from = $request->time_from;
                            $leave_application_date_time->time_to = $request->time_to;
                            $leave_application_date_time->save();
                
                            if ($request->hasFile('requirements')) {
                                $requirements = $request->file('requirements');
                                if($requirements){
                
                                    $leave_application_id = $leave_application->id; 
                                    foreach ($requirements as $requirement) {
                                        $leave_application_requirement = $this->storeLeaveApplicationRequirement($leave_application_id);
                                        $leave_application_requirement_id = $leave_application_requirement->id;
                
                                        if($leave_application_requirement){
                                    
                                            $filename = config('enums.storage.leave') . '/' 
                                                        . $leave_application_requirement_id ;
                
                                            $uploaded_image = $this->file_service->uploadRequirement($leave_application_requirement_id->id, $requirement, $filename, "REQ");
                
                                            if ($uploaded_image) {                     
                                                $leave_application_requirement_id = LeaveApplicationRequirement::where('id','=',$leave_application_requirement->id)->first();  
                                                if($leave_application_requirement  ){
                                                    $leave_application_requirement_name = $requirement->getleaveOriginalName();
                                                    $leave_application_requirement =  LeaveApplicationRequirement::findOrFail($leave_application_requirement->id);
                                                    $leave_application_requirement->name = $leave_application_requirement_name;
                                                    $leave_application_requirement->filename = $uploaded_image;
                                                    $leave_application_requirement->update();
                                                }                                      
                                            }                           
                                        }
                                    }
                                        
                                }     
                            }
                            $process_name="Applied";
                            $leave_application_logs = $this->storeLeaveApplicationLog($leave_application_id,$process_name);
                    }
            }
           
            return response()->json(['data' => 'Success'], Response::HTTP_OK);
        }catch(\Throwable $th){
         
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    

    public function storeLeaveApplicationRequirement($leave_application_id)
    {
        try {
            $leave_application_requirement = new LeaveApplicationRequirement();                       
            $leave_application_requirement->leave_application_id = $leave_application_id;
            $leave_application_requirement->save();

            return $leave_application_requirement;
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage(),'error'=>true]);
        }
    }
    public function storeLeaveApplicationLog($leave_application_id,$process_name)
    {
        try {
            $user_id = Auth::user()->id;
            $user = EmployeeProfile::where('id','=',$user_id)->first();
            $leave_application_log = new LeaveApplicationLog();                       
            $leave_application_log->leave_application_id = $leave_application_id;
            $leave_application_log->action_by = $user->id;
            $leave_application_log->action = $process_name;
            $leave_application_log->status = "applied";
            $leave_application_log->date = now()->toDateString('Ymd');
            $leave_application_log->save();
            return $leave_application_log;
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage(),'error'=>true]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(LeaveApplication $leaveApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveApplication $leaveApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveApplication $leaveApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveApplication $leaveApplication)
    {
        //
    }
}
