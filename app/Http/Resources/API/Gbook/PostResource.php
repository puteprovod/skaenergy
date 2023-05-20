<?php

namespace App\Http\Resources\API\Gbook;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
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
            'nick' => $this->name,
            'city' => $this->city,
            'comment' => $this->prepareComment(),
            'date' => $this->getDateFormatted(),
            'user' => $this->user_id ? \App\Http\Resources\User\IndexResource::make(User::find($this->user_id)) : null,
        ];
    }
}
