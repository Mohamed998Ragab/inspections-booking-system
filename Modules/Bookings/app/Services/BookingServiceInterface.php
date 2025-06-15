<?php
namespace Modules\Bookings\App\Services;

interface BookingServiceInterface
{
    public function createBooking(array $data);
    public function isSlotAvailable($teamId, $date, $startTime, $endTime);
    public function getUserBookings($userId);
    public function cancelBooking($bookingId, $userId);
}