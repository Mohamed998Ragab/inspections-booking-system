<?php
namespace Modules\Bookings\App\Repositories;

use Modules\Bookings\App\Models\Booking;

class BookingRepository implements BookingRepositoryInterface
{
    public function getAll()
    {
        return Booking::with(['team', 'user'])->orderBy('booking_date', 'desc')->get();
    }

    public function find($id)
    {
        return Booking::with(['team', 'user'])->findOrFail($id);
    }

    public function getByUser($userId)
    {
        return Booking::with(['team'])
            ->where('user_id', $userId)
            ->orderBy('booking_date', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data)
    {
        $booking->update($data);
        return $booking->fresh();
    }

    public function cancel(Booking $booking, $cancelledBy)
    {
        // Note: Only update status since cancelled_at and cancelled_by columns 
        // don't exist in your migration
        $booking->update([
            'status' => 'cancelled',
        ]);
        return $booking->fresh();
    }

    public function delete(Booking $booking)
    {
        $booking->delete();
    }
}