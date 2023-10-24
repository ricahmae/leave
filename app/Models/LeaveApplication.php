<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;
    protected $table = 'leave_applications';

    public $fillable = [
        'user_id',
        'leave_type_id',
        'reference_number',
        'location',
        'specific_location',
        'with_pay',
        'whole_day',
        'leave_credit_total',
        'status',
        'remarks',
        'date'
    ];
        public function leave_type(){
            return $this->belongsTo(LeaveType::class, 'leave_type_id','id');
        }
        public function requirements()
        {
            return $this->hasMany(LeaveApplicationRequirement::class);
        }
        public function logs()
        {
            return $this->hasMany(LeaveApplicationLog::class);
        }
        public function dates()
        {
            return $this->hasMany(LeaveApplicationDateTime::class);
        }
}
