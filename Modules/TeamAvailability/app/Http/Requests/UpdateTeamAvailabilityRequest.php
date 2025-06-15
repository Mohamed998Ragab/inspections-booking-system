<?php

namespace Modules\TeamAvailability\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'day_of_week' => 'sometimes|integer|between:0,6',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
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