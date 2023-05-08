<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GbookPost extends Model
{
    use HasFactory;
    protected $table = 'gbook_posts';
    protected $guarded = [];

    public function getDateFormatted(): string
    {
        $post_date = Carbon::createFromTimestamp($this->date)->timezone("Asia/Vladivostok");
        if (Carbon::now()->diffInDays($post_date) < 1)
            return 'Сегодня в ' . $post_date->format("H:i");
        return $post_date->format("d.m.Y H:i");
    }
}
