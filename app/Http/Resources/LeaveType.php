<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveType extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'period' => $this->period,
            'file_date' => $this->file_date,
            'attachment' => $this->attachment,
            'leave_credit' => $this->leave_credit ? new LeaveCredit( $this->leave_credit) : null,
            'requirements' => $this->requirements ? Requirement::collection($this->requirements) : [],
        ];
    }
}
