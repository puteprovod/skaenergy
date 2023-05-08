<?php

namespace App\MoonShine\Fields;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Json;

class BlockedIpsField extends Json
{
    public function afterSave(Model $item): void
    {
        $newArray = [];
        foreach ($item->blocked_ips as $blocked_ip) {
            $newArray[] = [
                'ip' => $blocked_ip['ip'],
                'days' => $blocked_ip['days'],
                'finish_date' => Carbon::now()->addDays($blocked_ip['days'])->toDateString()
            ];
        }
        $item->update(['blocked_ips' => $newArray]);
    }
}
