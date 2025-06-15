<?php
namespace Modules\Bookings\App\Services;

use Carbon\Carbon;
use Modules\Bookings\App\Models\Booking;
use Modules\TeamAvailability\App\Models\TeamAvailability;
use Modules\Teams\App\Models\Team;
use Modules\Tenant\App\Services\TenantContextService;

class SlotGenerationService
{
    protected $tenantContext;

    public function __construct(TenantContextService $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }

    public function generateSlots($teamId, $fromDate, $toDate)
    {
        // Validate team exists and belongs to current tenant
        $team = Team::find($teamId);
        if (!$team || $team->tenant_id !== $this->tenantContext->getCurrentTenant()) {
            throw new \Exception('Team not found or access denied');
        }

        $slots = [];
        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        // Get team's availability patterns
        $availabilities = TeamAvailability::where('team_id', $teamId)
            ->active()
            ->get()
            ->keyBy('day_of_week');

        // Get all existing bookings in the date range
        $existingBookings = Booking::where('team_id', $teamId)
            ->whereBetween('booking_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->active()
            ->get();

        // Group bookings by date for efficient lookup
        $bookingsByDate = $existingBookings->groupBy(function ($booking) {
            return $booking->booking_date->format('Y-m-d');
        });

        // Generate slots for each day in the range
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
            
            // Skip if no availability for this day
            if (!isset($availabilities[$dayOfWeek])) {
                continue;
            }

            $availability = $availabilities[$dayOfWeek];
            $dateString = $date->format('Y-m-d');
            
            // Generate hourly slots for this day
            $daySlots = $this->generateDaySlots(
                $dateString,
                $availability->start_time,
                $availability->end_time,
                $bookingsByDate->get($dateString, collect())
            );

            $slots = array_merge($slots, $daySlots);
        }

        return $slots;
    }

    private function generateDaySlots($dateString, $startTime, $endTime, $existingBookings)
    {
        $slots = [];
        
        // Simply extract time portion using Carbon
        $startTimeOnly = Carbon::parse($startTime)->format('H:i:s');
        $endTimeOnly = Carbon::parse($endTime)->format('H:i:s');
        
        // Create Carbon instances for the specific date
        $start = Carbon::parse($dateString . ' ' . $startTimeOnly);
        $end = Carbon::parse($dateString . ' ' . $endTimeOnly);

        // Generate 1-hour slots
        while ($start->lt($end)) {
            $slotEnd = $start->copy()->addHour();
            
            // Don't create slot if it would exceed availability end time
            if ($slotEnd->gt($end)) {
                break;
            }

            // Check if this slot conflicts with existing bookings
            $isAvailable = $this->isSlotAvailable(
                $start->format('H:i:s'),
                $slotEnd->format('H:i:s'),
                $existingBookings
            );

            $slots[] = [
                'date' => $dateString,
                'start_time' => $start->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'start_datetime' => $start->format('Y-m-d H:i:s'),
                'end_datetime' => $slotEnd->format('Y-m-d H:i:s'),
                'available' => $isAvailable,
                'day_of_week' => $start->format('l'),
            ];

            $start->addHour();
        }

        return $slots;
    }

    private function isSlotAvailable($startTime, $endTime, $existingBookings)
    {
        foreach ($existingBookings as $booking) {
            // Extract time only from booking times
            $bookingStart = Carbon::parse($booking->start_time)->format('H:i:s');
            $bookingEnd = Carbon::parse($booking->end_time)->format('H:i:s');

            // Check for time overlap
            // Overlap occurs if: slot_start < booking_end AND slot_end > booking_start
            if ($startTime < $bookingEnd && $endTime > $bookingStart) {
                return false;
            }
        }

        return true;
    }
}