<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function show($id){
        return new ReviewResource(Review::findOrFail($id));
    }
    public function store(Request $request){
        Log::info(print_r($request->all(), true));
        $data = $request->validate([
            'id'=> 'required|size:36|unique:reviews',
            'content'=>'required|min:2',
            'rating'=>'required|in:1,2,3,4,5',
            'user_id'=>'required'
        ]);
        $booking = Booking::findByReviewKey($data['id']);
        $user = User::where('id', $data['user_id'])->first();
        Log::info(print($user->id));
        if($booking === null || $user == null){
            return abort(404);
        }
        $booking->review_key = '';
        $booking->save();
        $review = Review::make($data);
        $review->booking_id = $booking->id;
        $review->bookable_id = $booking->bookable_id;
        $review->user_id = $user->id;
        $review->save();
        return new ReviewResource($review);
    }
}
