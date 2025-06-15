<?php

namespace Modules\Teams\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'is_active' => 'sometimes|boolean',
            'max_concurrent_bookings' => 'sometimes|integer|min:1',
        ];
    }
}