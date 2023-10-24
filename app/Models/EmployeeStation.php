<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStation extends Model
{
    use HasFactory;

    protected $table = 'employee_stations';

    public $fillable = [
        'employee_profile_id',
        'job_position_id'
    ];

    public $timestamps = TRUE;

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class);
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }
}
