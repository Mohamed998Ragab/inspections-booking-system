<?php
namespace Modules\Bookings\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Tenant\App\Services\TenantContextService;

class CreateBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'team_id' => 'required|exists:teams,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $tenantContext = app(TenantContextService::class);
        $this->merge([
            'tenant_id' => $tenantContext->getCurrentTenant(),
            'user_id' => $this->user()->id,
        ]);
    }

        /**
     * Override validated() to include the merged fields
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Add the fields that were merged in prepareForValidation
        $validated['tenant_id'] = $this->input('tenant_id');
        $validated['user_id'] = $this->input('user_id');
        
        return $validated;
    }
}