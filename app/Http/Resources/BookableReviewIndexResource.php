<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookableReviewIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = DB::table('users')->where('id', $this->user_id)->first();
        return [
            'created_at' => $this->created_at,
            'rating' => $this->rating,
            'content' => $this->content,
            'name' => $user == null ? "Pavel Jankoski" : $user->name
        ];
    }
}
