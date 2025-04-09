<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Repositories\SeatRepositoryInterface;
use App\Repositories\SessionRepositoryInterface;
use App\Repositories\ReservationRepositoryInterface;

class ReservationController extends Controller
{
    protected $reservationRepository;
    protected $sessionRepository;
    protected $seatRepository;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        SessionRepositoryInterface $sessionRepository,
        SeatRepositoryInterface $seatRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->sessionRepository = $sessionRepository;
        $this->seatRepository = $seatRepository;
    }

    public function index()
    {
        $reservations = $this->reservationRepository->getAll();
        return response()->json($reservations, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'session_id' => 'required|integer',
            'seats' => 'required|array',
            'seats.*' => 'integer',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => "You need to be logged in to make a reservation"], 401);
        }

        foreach ($fields['seats'] as $seatId) {
            $seat = $this->seatRepository->findById($seatId);
            if ($seat->is_reserved) {
                return response()->json(['message' => 'One or more seats are already reserved'], 400);
            }
        }

        $fields['user_id'] = Auth::id();
        $reservation = $this->reservationRepository->store($fields);

        foreach ($fields['seats'] as $seatId) {
            $this->seatRepository->reserveSeat($seatId);
        }

        return response()->json([
            'message' => 'Reservation created successfully!',
            'reservation' => $reservation,
        ], 201);
    }

    public function cancel($id)
    {
        $reservation = $this->reservationRepository->findById($id);
        $user = Auth::user();
        
        if ($reservation->user_id != $user->id) {
            return response()->json(['message' => "You can't cancel this reservation"], 403);
        }

        $this->reservationRepository->cancel($id);

        foreach ($reservation->seats as $seat) {
            $this->seatRepository->freeSeat($seat->id);
        }

        return response()->json(['message' => 'Reservation cancelled successfully!'], 200);
    }

    public function expire()
    {
        $this->reservationRepository->expireUnpaidReservations();

        return response()->json(['message' => 'Expired reservations have been cleared'], 200);
    }
}
