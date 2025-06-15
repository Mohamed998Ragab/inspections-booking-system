<?php
namespace Modules\Bookings\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Bookings\App\Services\BookingServiceInterface;
use Modules\Bookings\App\Http\Requests\CreateBookingRequest;
use Modules\Bookings\App\Services\SlotGenerationService;
use Modules\Bookings\App\Transformers\BookingResource;

class BookingsController
{
    protected $bookingService;

    public function __construct(BookingServiceInterface $bookingService)
    {
        $this->bookingService = $bookingService;
    }


    public function generateSlots(Request $request, $id)
{
    $request->validate([
        'from' => 'required|date',
        'to' => 'required|date|after_or_equal:from',
    ]);

    $slotService = app(SlotGenerationService::class);
    $slots = $slotService->generateSlots($id, $request->from, $request->to);

    return response()->json([
        'team_id' => $id,
        'date_range' => [
            'from' => $request->from,
            'to' => $request->to
        ],
        'slots' => $slots,
        'total_slots' => count($slots)
    ]);
}


    public function index(Request $request)
    {
        $bookings = $this->bookingService->getUserBookings($request->user()->id);
        return BookingResource::collection($bookings);
    }

    public function store(CreateBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());
            return new BookingResource($booking);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->bookingService->cancelBooking($id, $request->user()->id);
            return response()->json(['message' => 'Booking cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}