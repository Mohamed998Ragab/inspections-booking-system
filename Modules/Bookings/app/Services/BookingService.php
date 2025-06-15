<?php
namespace Modules\Bookings\App\Services;

use Carbon\Carbon;
use Modules\Bookings\App\Models\Booking;
use Modules\Bookings\App\Repositories\BookingRepositoryInterface;
use Modules\TeamAvailability\App\Models\TeamAvailability;
use Modules\Teams\App\Models\Team;
use Modules\Tenant\App\Services\TenantContextService;

class BookingService implements BookingServiceInterface
{
    protected $bookingRepository;
    protected $slotService;
    protected $tenantContext;

    public function __construct(
        BookingRepositoryInterface $bookingRepository, 
        SlotGenerationService $slotService,
        TenantContextService $tenantContext
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->slotService = $slotService;
        $this->tenantContext = $tenantContext;
    }

    public function createBooking(array $data)
    {
        // Validate team exists and belongs to current tenant
        $team = Team::find($data['team_id']);
        if (!$team || $team->tenant_id !== $this->tenantContext->getCurrentTenant()) {
            throw new \Exception('Team not found or access denied');
        }

        // Validate slot availability
        if (!$this->isSlotAvailable($data['team_id'], $data['booking_date'], $data['start_time'], $data['end_time'])) {
            throw new \Exception('Selected time slot is not available');
        }

        return $this->bookingRepository->create($data);
    }

    public function isSlotAvailable($teamId, $date, $startTime, $endTime)
    {
        $bookingDate = Carbon::parse($date);
        $dayOfWeek = $bookingDate->dayOfWeek;

        // Check if team has availability for this day/time
        $availability = TeamAvailability::where('team_id', $teamId)
            ->where('day_of_week', $dayOfWeek)
            ->active()
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->exists();

        if (!$availability) {
            return false;
        }

        // Check for conflicts with existing bookings
        $conflicts = Booking::where('team_id', $teamId)
            ->where('booking_date', $date)
            ->active()
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        return !$conflicts;
    }

    public function getUserBookings($userId)
    {
        return $this->bookingRepository->getByUser($userId);
    }

    public function cancelBooking($bookingId, $userId)
    {
        $booking = $this->bookingRepository->find($bookingId);
        
        // Enhanced authorization check
        if ($booking->user_id !== $userId || $booking->tenant_id !== $this->tenantContext->getCurrentTenant()) {
            throw new \Exception('Unauthorized to cancel this booking');
        }

        // Fix: Properly combine date and time for comparison
        $bookingDateTime = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time);
        
        // Prevent cancelling past bookings
        if ($bookingDateTime->isPast()) {
            throw new \Exception('Cannot cancel past bookings');
        }

        return $this->bookingRepository->cancel($booking, $userId);
    }
}