<?php
namespace Modules\Bookings\App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'team' => [
                'id' => $this->team->id,
                'name' => $this->team->name,
            ],
            'booking_date' => $this->booking_date->format('Y-m-d'),
            'start_time' => $this->formatTime($this->start_time),
            'end_time' => $this->formatTime($this->end_time),
            'status' => $this->status,
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            'location' => $this->location,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'cancelled_at' => $this->cancelled_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function formatTime($time)
    {
        // Handle different time formats
        if (is_string($time) && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $time)) {
            // Already a time string
            return substr($time, 0, 5); // Return H:i format
        }
        
        // If it's a Carbon instance or datetime
        try {
            return \Carbon\Carbon::parse($time)->format('H:i');
        } catch (\Exception $e) {
            return $time; // Return as is if parsing fails
        }
    }
}