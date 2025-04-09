<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'seat_number', 'is_reserved'];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_seat');
    }
}
