<?php

namespace Modules\Tenant\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tenants,slug',
            'domain' => 'nullable|string|unique:tenants,domain',
        ];
    }
}