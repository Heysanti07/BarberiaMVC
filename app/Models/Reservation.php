<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'consultant_id',
        'reservation_date',
        'start_time',
        'end_time',
        'reservation_status',
        'total_amount',
        'payment_status',
        'cancellation_reason',
    ];


    public function user()
    {
        // La reserva pertenece a un usuario (user_id)
        return $this->belongsTo(User::class);
    }

    public function consultant() {

        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function reservationDetil( ){
        return $this->hasOne(ReservationDetail::class);
    }

}
