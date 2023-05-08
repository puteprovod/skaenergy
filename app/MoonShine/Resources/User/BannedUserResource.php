<?php

namespace App\MoonShine\Resources\User;

use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Actions\FiltersAction;

class BannedUserResource extends UserResource
{
    public static int $itemsPerPage = 100;
    public static string $title = 'Находящиеся в бане';
    public static string $subTitle = 'Все проштрафившиеся пользователи';
    public function query(): Builder
    {
        return parent::query()->where('banned_until','<>','0000-00-00');
    }
    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
