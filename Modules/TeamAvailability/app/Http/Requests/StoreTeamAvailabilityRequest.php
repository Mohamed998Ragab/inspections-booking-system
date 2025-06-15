<?php

namespace Modules\TeamAvailability\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'team_id' => 'required|integer|exists:teams,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'day_of_week.between' => 'Day of week must be between 0 (Sunday) and 6 (Saturday)',
            'end_time.after' => 'End time must be after start time',
        ];
    }
}