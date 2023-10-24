<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalInformation extends Model
{
    use HasFactory;

    protected $table = 'legal_informations';

    public $fillable = [
        'employee_id',
        'details',
        'answer',
        'legal_information_question_id'
    ];

    public $timestamps = TRUE;

    public function legalInformationQuestion()
    {
        return $this->belongsTo(LegalInformationQuestion::class);
    }
}
