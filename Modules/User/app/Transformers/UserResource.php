<?php

namespace Modules\User\App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'tenant_id' => $this->tenant_id,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    // // If you need extra data, use this method instead
    // public function with($request)
    // {
    //     return [
    //         'meta' => [
    //             'tenant' => $this->tenant?->name ?? null,
    //         ]
    //     ];
    // }
}