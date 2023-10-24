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
            'action_name' => $this->action_name,
            // 'action_by' => $this->actioned_by ? new User( $this->action_by) : null,
           
        ];
    }
}
