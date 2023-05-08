<?php

namespace App\MoonShine\Resources;

use App\Models\Setting;

use App\MoonShine\Fields\BlockedIpsField;
use App\MoonShine\Fields\SettingField;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Code;
use MoonShine\Fields\Json;
use MoonShine\Fields\NoInput;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\FormActions\FormAction;
use MoonShine\Resources\SingletonResource;
use MoonShine\Fields\ID;

class SettingResource extends SingletonResource
{
    public static string $model = Setting::class;

    public static string $title = 'Setting';

    public function getId(): int|string
    {
        return 1;
    }

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    SettingField::make('Настройки сайта', 'settings')->language('js')->lineNumbers(),

                ])->columnSpan(6),
                Column::make([
                    BlockedIpsField::make('Заблокированные IP-адреса','blocked_ips')->fields([
                        Text::make('IP-адрес','ip'),
                        Text::make('Дней блокировки','days',fn($item)=>Carbon::createFromDate($item->finish_date)->diffInDays(Carbon::now())+1),
                        NoInput::make('Закончится','finish_date')
                    ])->removable()->hint('Не забудьте нажать на кнопку "Сохранить", для того чтобы изменения вступили в силу')
                ])->columnSpan(6)
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

}
