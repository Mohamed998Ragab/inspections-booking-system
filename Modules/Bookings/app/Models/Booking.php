<?php
namespace Modules\Bookings\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Teams\App\Models\Team;
use Modules\User\App\Models\User;
use Modules\Tenant\App\Traits\BelongsToTenant;

class Booking extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'team_id', 'user_id', 'booking_date', 'start_time', 'end_time',
        'status', 'customer_name', 'customer_email', 'customer_phone', 'location', 'notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

        // Add these accessor methods to handle time formatting properly
        public function getStartTimeAttribute($value)
        {
            // If it's already a time string (H:i:s format), return as is
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
                return $value;
            }
            // If it's a datetime, extract time part
            return date('H:i:s', strtotime($value));
        }
    
        public function getEndTimeAttribute($value)
        {
            // If it's already a time string (H:i:s format), return as is
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
                return $value;
            }
            // If it's a datetime, extract time part
            return date('H:i:s', strtotime($value));
        }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }

        // Helper method to get full datetime for booking start
        public function getBookingStartDateTime()
        {
            return $this->booking_date->format('Y-m-d') . ' ' . $this->start_time;
        }
    
        // Helper method to get full datetime for booking end
        public function getBookingEndDateTime()
        {
            return $this->booking_date->format('Y-m-d') . ' ' . $this->end_time;
        }
}