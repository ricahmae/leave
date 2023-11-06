<?php

namespace App\Http\Controllers;

use App\Models\ObApplication;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObApplication as ResourcesObApplication;
use App\Models\EmployeeProfile;
use App\Models\ObApplicationLog;
use App\Models\ObApplicationRequirement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
class ObApplicationController extends Controller
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
            $official_business_applications=[];
            
            $official_business_applications =ObApplication::all();
            $official_business_application_resource=ResourcesObApplication::collection($official_business_applications);
           
             return response()->json(['data' => $official_business_application_resource], Response::HTTP_OK);
        }catch(\Throwable $th){
        
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

   
    public function getObApplications(Request $request)
    {
        $status = $request->status;  
        $ob_applications = [];

       if($status == 'for-approval-supervisor'){
            $ob_applications = ObApplication::where('status', '=', 'for-approval-supervisor');
            
        }
        else if($status == 'for-approval-head'){
            $ob_applications = obApplication::where('status', '=', 'for-approval-head');

        }
        else if($status == 'declined'){
            $ob_applications = obApplication::where('status', '=', 'declined');
                                                   
        }
        else if($status == 'approved'){
            $ob_applications = obApplication::where('status', '=', 'approved');
        
        }
        else{
            $ob_applications = obApplication::where('status', '=', $status );
        }


        if (isset($request->search)) {
            $search = $request->search; 
            $ob_applications = $ob_applications->where('reference_number','like', '%' .$search . '%');
                                                 
            $ob_applications = isset($search) && $search; 
        }

        return ResourcesObApplication::collection($ob_applications->paginate(50));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                            else{
                                $action = $status;
                            }
                            $ob_application_id = $request->ob_application_id;
                            $ob_applications = ObApplication::where('id','=', $ob_application_id)
                                                                    ->first();
                            if($ob_applications){
                            
                                $ob_application_log = new ObApplicationLog();
                                $ob_application_log->action = $action;
                                $ob_application_log->ob_application_id = $ob_application_id;
                                $ob_application_log->action_by = $user_id;
                                $ob_application_log->date = now()->toDateString('Ymd');
                                $ob_application_log->save();

                                $ob_application = ObApplication::findOrFail($ob_application_id);   
                                $ob_application->status = $new_status;
                                $ob_application->update();
                                    
                                return response(['message' => 'Application has been sucessfully '.$message_action, 'data' => $ob_application], Response::HTTP_CREATED); 
                                }
                }           
            }


         catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(),'error'=>true]);
        }
      
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $official_business_application = new ObApplication();
            $official_business_application->date_from = $request->date_from;
            $official_business_application->date_to = $request->date_to;
            $official_business_application->business_from = $request->business_from;
            $official_business_application->business_to = $request->business_to;
            $official_business_application->status = "for-approval-supervisor";
            $official_business_application->reason = "for-approval-supervisor";
            $official_business_application->date = now()->toDateString('Ymd');
            $official_business_application->save();
            
            if ($request->hasFile('requirements')) {
                $requirements = $request->file('requirements');

                if($requirements){

                    $official_business_application_id = $official_business_application->id; 
                    foreach ($requirements as $requirement) {
                        $official_business_requirement = $this->storeOfficialBusinessApplicationRequirement($official_business_application_id);
                        $official_business_requirement_id = $official_business_requirement->id;

                        if($official_business_requirement){
                            $filename = config('enums.storage.ob') . '/' 
                                        . $official_business_requirement_id ;

                            $uploaded_image = $this->file_service->uploadRequirement($official_business_requirement_id->id, $requirement, $filename, "REQ");

                            if ($uploaded_image) {                     
                                $official_business_requirement_id = ObApplicationRequirement::where('id','=',$official_business_requirement->id)->first();  
                                if($official_business_requirement  ){
                                    $official_business_requirement_name = $requirement->getobOriginalName();
                                    $official_business_requirement =  ObApplicationRequirement::findOrFail($official_business_requirement->id);
                                    $official_business_requirement->name = $official_business_requirement_name;
                                    $official_business_requirement->filename = $uploaded_image;
                                    $official_business_requirement->update();
                                }                                      
                            }                           
                        }
                    }
                        
                }     
            }
            $process_name="Applied";
            $official_business_logs = $this->storeOfficialBusinessApplicationLog($official_business_application_id,$process_name);
            return response()->json(['data' => 'Success'], Response::HTTP_OK);
        }catch(\Throwable $th){
         
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function declineObApplication(Request $request)
    {
        try {
                    $ob_application_id = $request->ob_application_id;
                    $ob_applications = ObApplication::where('id','=', $ob_application_id)
                                                            ->first();
                if($ob_applications)
                {
                        $user_id = Auth::user()->id;     
                        $user = EmployeeProfile::where('id','=',$user_id)->first();
                        $user_password=$user->password;
                        $password=$request->password;
                        if($user_password==$password)
                        {
                            if($user_id){
                                $ob_application_log = new ObApplicationLog();
                                $ob_application_log->action = 'declined';
                                $ob_application_log->ob_application_id = $ob_application_id;
                                $ob_application_log->date = now()->toDateString('Ymd');
                                $ob_application_log->action_by = $user_id;
                                $ob_application_log->save();

                                $ob_application = ObApplication::findOrFail($ob_application_id);
                                $ob_application->declined_at = now();
                                $ob_application->status = 'declined';
                                $ob_application->update();
                                return response(['message' => 'Application has been sucessfully declined', 'data' => $ob_application], Response::HTTP_CREATED);  
            
                            }
                         }
                }
            } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(),  'error'=>true]);
        }
    }

    
    public function storeOfficialBusinessApplicationRequirement($official_business_application_id)
    {
        try {
            $official_business_application_requirement = new ObApplicationRequirement();                       
            $official_business_application_requirement->official_business_application_id = $official_business_application_id;
            $official_business_application_requirement->save();

            return $official_business_application_requirement;
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage(),'error'=>true]);
        }
    }
    public function storeOfficialBusinessApplicationLog($official_time_application_id,$process_name)
    {
        try {
            $user_id="1";
            $official_time_application_log = new ObApplicationLog();                       
            $official_time_application_log->official_time_application_id = $official_time_application_id;
            $official_time_application_log->action_by = $user_id;
            $official_time_application_log->action = $process_name;
            $official_time_application_log->status = "applied";
            $official_time_application_log->date = now()->toDateString('Ymd');
            $official_time_application_log->save();

            return $official_time_application_log;
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage(),'error'=>true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ObApplication $obApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ObApplication $obApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ObApplication $obApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ObApplication $obApplication)
    {
        //
    }
}
