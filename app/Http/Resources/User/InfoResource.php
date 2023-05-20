<?php

namespace App\Http\Resources\User;

use App\Models\GbookPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class InfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nick' => $this->nick,
            'name' => $this->name,
            'city' => $this->city,
            'country' => $this->country,
            'favourite_clubs' => $this->favourite_clubs,
            'is_habar' => $this->is_habar,
            'post_count' => GbookPost::where('user_id',$this->id)->count(),
            'image_url' => $this->image_url ? Storage::disk('public')->url($this->image_url) : null,
            'thumb_url' => $this->image_url ? Storage::disk('public')->url('user_avatars/thumb_'.basename($this->image_url)) : null,
            'banned_until' => $this->banned_until ? Carbon::make($this->banned_until)->format('d.m.Y') : null,
            'register_date' => Carbon::parse($this->created_at)->format('d.m.Y')
        ];
    }
}
