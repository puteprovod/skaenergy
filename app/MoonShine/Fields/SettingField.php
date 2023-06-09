<?php

namespace App\MoonShine\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MoonShine\Fields\Code;

class SettingField extends Code
{
    public function afterSave(Model $item): void
    {
        parent::afterSave($item); // TODO: Change the autogenerated stub
        Cache::forget('settings');
    }
}
