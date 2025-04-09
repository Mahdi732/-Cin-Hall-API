<?php

namespace App\Repositories;

interface ReservationRepositoryInterface
{
    public function getAll();
    public function store(array $fields);
    public function findById(int $id);
    public function updatePaymentStatus(int $reservationId, string $status);
    public function cancel(int $id);
    public function expireUnpaidReservations();
}
