<?php
namespace Modules\Bookings\App\Repositories;

use Modules\Bookings\App\Models\Booking;

interface BookingRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function getByUser($userId);
    public function create(array $data);
    public function update(Booking $booking, array $data);
    public function cancel(Booking $booking, $cancelledBy);
    public function delete(Booking $booking);
}