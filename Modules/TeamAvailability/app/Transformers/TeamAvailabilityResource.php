<?php

namespace Modules\TeamAvailability\App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamAvailabilityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'day_of_week' => $this->day_of_week,
            'day_name' => $this->getDayName(),
            'start_time' => $this->start_time?->format('H:i'),
            'end_time' => $this->end_time?->format('H:i'),
            'time_range' => $this->getFormattedTimeRange(),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Include team info when needed
            'team' => $this->whenLoaded('team', function () {
                return [
                    'id' => $this->team->id,
                    'name' => $this->team->name,
                ];
            }),
        ];
    }
}