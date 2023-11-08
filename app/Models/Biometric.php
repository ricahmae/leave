<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biometric extends Model
{
    use HasFactory;
    protected $table = 'biometrics';

    public $fillable = [
       'id',
       'biometric_name'
    ];
            public function dtr()
        {
            return $this->hasMany(DailyTimeRecord::class);
        }
}
