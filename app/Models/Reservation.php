<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'total_amount',
        'payment_status',
        'status'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'reservation_seat');
    }

    public function getTotalAmountAttribute($value)
    {
        return number_format($value, 2);
    }
}
