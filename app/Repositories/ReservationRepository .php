<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Seat;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getAll()
    {
        return Reservation::with(['session', 'seats', 'user'])->get();
    }

    public function store(array $fields)
    {
        $reservation = Reservation::create([
            'session_id' => $fields['session_id'],
            'user_id' => $fields['user_id'],
            'total_amount' => $this->calculateTotalAmount($fields['seats']),
        ]);

        $reservation->seats()->attach($fields['seats']);

        return $reservation;
    }

    public function findById(int $id)
    {
        return Reservation::with(['session', 'seats', 'user'])->findOrFail($id);
    }

    public function updatePaymentStatus(int $reservationId, string $status)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['payment_status' => $status]);

        return $reservation;
    }

    public function cancel(int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'cancelled']);
        return $reservation;
    }

    public function expireUnpaidReservations()
    {
        $expiredReservations = Reservation::where('payment_status', 'pending')
                                          ->where('created_at', '<', now()->subMinutes(15))
                                          ->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->update(['status' => 'expired']);
            foreach ($reservation->seats as $seat) {
                $seat->update(['is_reserved' => false]);
            }
        }

        return $expiredReservations;
    }

    private function calculateTotalAmount(array $seats)
    {
        return count($seats) * 10;  
    }
}
