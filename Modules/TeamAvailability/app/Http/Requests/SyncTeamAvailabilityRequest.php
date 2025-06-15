<?php

namespace Modules\TeamAvailability\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncTeamAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'availability' => 'required|array',
            'availability.*.day_of_week' => 'required|integer|between:0,6',
            'availability.*.start_time' => 'required|date_format:H:i',
            'availability.*.end_time' => 'required|date_format:H:i|after:availability.*.start_time',
            'availability.*.is_active' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'availability.*.day_of_week.between' => 'Day of week must be between 0 (Sunday) and 6 (Saturday)',
            'availability.*.end_time.after' => 'End time must be after start time',
        ];
    }
}