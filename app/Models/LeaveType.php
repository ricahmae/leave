<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    
    protected $table = 'leave_types';

    public $fillable = [
        'name',
        'description',
        'leave_credit_id'
    ];

        public function leave_credit(){
             return $this->belongsTo(LeaveCredit::class, 'leave_credit_id', 'id');
        }
        public function requirements(){ 
            return $this->belongsToMany(Requirement::class, 'leave_type_requirements', 'leave_type_id', 'requirement_id');
        }
       
}
