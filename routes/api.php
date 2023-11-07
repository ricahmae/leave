<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('signin', 'UserController@signIn');
    Route::post('send-otp', 'UserController@sendOTPEmail');
    Route::post('validate-otp', 'UserController@validateOTP');
    Route::post('reset-password', 'UserController@resetPassword');
    Route::get('leave_types', 'LeaveTypeController@index');
    Route::get('leave_applications', 'LeaveApplicationController@index');

    Route::get('user_leave_applications', 'LeaveApplicationController@getUserLeaveApplication');
    Route::get('official_time_applications', 'OfficialTimeApplicationController@index');
    Route::get('official_business_applications', 'ObApplicationController@index');
    Route::get('employee_leave_credit', 'LeaveApplicationController@getEmployeeLeaveCredit');
    Route::get('employee_leave_credit_logs', 'LeaveApplicationController@getEmployeeLeaveCreditLogs');
    Route::get('user_leave_credit_logs', 'LeaveApplicationController@getUserLeaveCreditsLogs');
    Route::get('days', 'LeaveCreditController@addMonthlyLeaveCredit');
    
    Route::post('leave_type', 'LeaveTypeController@store');
});

Route::middleware('auth.cookie')->group(function(){
    Route::namespace('App\Http\Controllers')->group(function(){
        Route::post('authenticity-check', 'UserController@isAuthenticated');

        /**
         * User Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('users', 'UserController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('user', 'UserController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('user/{id}', 'UserController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('user/{id}', 'UserController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('user/{id}', 'UserController@destroy');
        });

        /**
         * Employee Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('employee-profiles', 'EmployeeProfileController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('employee-profile', 'EmployeeProfileController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('employee-profile/{id}', 'EmployeeProfileController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('employee-profile/{id}', 'EmployeeProfileController@update');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('employee-profile-image/{id}', 'EmployeeProfileController@updateEmployeeProfile');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('employee-profile/{id}', 'EmployeeProfileController@destroy');
        });

        /**
         * CivilServiceEligibility Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('civil-service-eligiblities', 'CivilServiceEligibilityController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('civil-service-eligiblity', 'CivilServiceEligibilityController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('civil-service-eligiblity/{id}', 'CivilServiceEligibilityController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('civil-service-eligiblity/{id}', 'CivilServiceEligibilityController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('civil-service-eligiblity/{id}', 'CivilServiceEligibilityController@destroy');
        });

        /**
         * Civil Service Eligibility Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('civil-service-eligiblities', 'CivilServiceEligibilityController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('civil-service-eligiblity', 'CivilServiceEligibilityController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('civil-service-eligiblity/{id}', 'CivilServiceEligibilityController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('civil-service-eligiblity/{id}', 'CivilServiceEligibilityController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('civil-service-eligiblity/{id}', 'CivilServiceEligibilityController@destroy');
        });

        /**
         * Contact Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('contacts', 'ContactController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('contact', 'ContactController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('contact/{id}', 'ContactController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('contact/{id}', 'ContactController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('contact/{id}', 'ContactController@destroy');
        });

        /**
         * FamilyBackground Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('family-backgrounds', 'FamilyBackgroundController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('family-background', 'FamilyBackgroundController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('family-background/{id}', 'FamilyBackgroundController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('family-background/{id}', 'FamilyBackgroundController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('family-background/{id}', 'FamilyBackgroundController@destroy');
        });

        /**
         * Idenfication Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('identifications', 'IdenficationController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('identification', 'IdenficationController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('identification/{id}', 'IdenficationController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('identification/{id}', 'IdenficationController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('identification/{id}', 'IdenficationController@destroy');
        });

        /**
         * IssuanceInformation Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('issuance-informations', 'IssuanceInformationController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('issuance-information', 'IssuanceInformationController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('issuance-information/{id}', 'IssuanceInformationController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('issuance-information/{id}', 'IssuanceInformationController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('issuance-information/{id}', 'IssuanceInformationController@destroy');
        });

        /**
         * LegalInformation Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('legal_informations', 'LegalInformationController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('legal_information', 'LegalInformationController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('legal_information/{id}', 'LegalInformationController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('legal_information/{id}', 'LegalInformationController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('legal_information/{id}', 'LegalInformationController@destroy');
        });

        /**
         * LegalInformationQuestion Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('legal_information_questions', 'LegalInformationQuestionController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('legal_information_question', 'LegalInformationQuestionController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('legal_information_question/{id}', 'LegalInformationQuestionController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('legal_information_question/{id}', 'LegalInformationQuestionController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('legal_information_question/{id}', 'LegalInformationQuestionController@destroy');
        });

        /**
         * OtherInformation Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('other_informations', 'OtherInformationController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('other_information', 'OtherInformationController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('other_information/{id}', 'OtherInformationController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('other_information/{id}', 'OtherInformationController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('other_information/{id}', 'OtherInformationController@destroy');
        });

        /**
         * PasswordTrail Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('password_trails', 'PasswordTrailController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('password_trail', 'PasswordTrailController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('password_trail/{id}', 'PasswordTrailController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('password_trail/{id}', 'PasswordTrailController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('password_trail/{id}', 'PasswordTrailController@destroy');
        });

        /**
         * PersonalInformation Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('personal-informations', 'PersonalInformationController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('personal-information', 'PersonalInformationController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('personal-information/{id}', 'PersonalInformationController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('personal-information/{id}', 'PersonalInformationController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('personal-information/{id}', 'PersonalInformationController@destroy');
        });

        /**
         * References Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('references', 'ReferencesController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('reference', 'ReferencesController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('reference/{id}', 'ReferencesController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('reference/{id}', 'ReferencesController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('reference/{id}', 'ReferencesController@destroy');
        });

        /**
         * Training Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('trainings', 'TrainingController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('training', 'TrainingController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('training/{id}', 'TrainingController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('training/{id}', 'TrainingController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('training/{id}', 'TrainingController@destroy');
        });

        /**
         * WorkExperience Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('work-experiences', 'WorkExperienceController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('work-experience', 'WorkExperienceController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('work-experience/{id}', 'WorkExperienceController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('work-experience/{id}', 'WorkExperienceController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('work-experience/{id}', 'WorkExperienceController@destroy');
        });

        /**
         * DepartmentGroup Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('department_groups', 'DepartmentGroupController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('department_group', 'DepartmentGroupController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('department_group/{id}', 'DepartmentGroupController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('department_group/{id}', 'DepartmentGroupController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('department_group/{id}', 'DepartmentGroupController@destroy');
        });

        /**
         * Department Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('departments', 'DepartmentController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('department', 'DepartmentController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('department/{id}', 'DepartmentController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('department/{id}', 'DepartmentController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('department/{id}', 'DepartmentController@destroy');
        });

        /**
         * JobPosition Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('job_positions', 'JobPositionController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('job_position', 'JobPositionController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('job_position/{id}', 'JobPositionController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('job_position/{id}', 'JobPositionController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('job_position/{id}', 'JobPositionController@destroy');
        });

        /**
         * Plantilla Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('plantillas', 'PlantillaController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('plantilla', 'PlantillaController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('plantilla/{id}', 'PlantillaController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('plantilla/{id}', 'PlantillaController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('plantilla/{id}', 'PlantillaController@destroy');
        });

        /**
         * Station Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('stations', 'StationController@index');
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('station', 'StationController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('station/{id}', 'StationController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('station/{id}', 'StationController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('station/{id}', 'StationController@destroy');
        });



        /**
         * Module without authorization needed
         */
        Route::delete('signout', 'UserController@signOut');






          /**
         * Leave Type Module
         */
        Route::middleware('auth.permission::admin view')->group(function(){
          
        });

        Route::middleware('auth.permission::admin create')->group(function(){
            Route::post('leave_types', 'LeaveTypeController@store');
        });

        Route::middleware('auth.permission::admin view')->group(function(){
            Route::get('leave_types/{id}', 'LeaveTypeController@show');
        });

        Route::middleware('auth.permission::admin update')->group(function(){
            Route::put('leave_types/{id}', 'LeaveTypeController@update');
        });

        Route::middleware('auth.permission::admin delete')->group(function(){
            Route::delete('leave_types/{id}', 'LeaveTypeController@destroy');
        });


    });


});