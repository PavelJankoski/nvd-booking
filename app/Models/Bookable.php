<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Bookable
 *
 * @mixin Builder
 */
class Bookable extends Model
{
    use HasFactory;
    public function bookings(){
        return $this->hasMany(Booking::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function availableFor($from, $to) : bool {
        return 0 == $this->bookings()->betweenDates($from, $to)->count();
    }

    public function priceFor($from, $to) {
        $days = (new Carbon($from))->diffInDays(new Carbon($to)) + 1;
        $price = $days * $this->price;
        return response()->json([
            'data' => [
                'total' => $price,
                'breakdown' => [
                    $this->price => $days
                ]
            ]
        ]);
    }


}
