<?php

namespace Modules\Tenant\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:tenants,slug,' . $this->route('id'),
            'domain' => 'sometimes|nullable|string|unique:tenants,domain,' . $this->route('id'),
        ];
    }
}
