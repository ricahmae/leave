<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirementLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'leave_requirement_id' => $this->name,
            'action' => $this->action,
            'action_by' => $this->actioned_by ? new EmployeeProfile( $this->action_by) : null,
           
        ];
    }
}
