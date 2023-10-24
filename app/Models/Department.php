<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    public $fillable = [
        'name',
        'code',
        'department_group_id'
    ];

    public $timestamps = TRUE;

    public function employee()
    {
        return $this->hasMany(EmployeeProfile::class);
    }
}
