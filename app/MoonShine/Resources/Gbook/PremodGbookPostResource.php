<?php

namespace App\MoonShine\Resources\Gbook;
use MoonShine\Fields\SwitchBoolean;

class PremodGbookPostResource extends GbookPostResource
{
    public static int $itemsPerPage = 100;
    public static string $title = 'Премодерация постов';
    public static string $subTitle = 'Настройка отображения постов в гостевой книге';
    public function fields(): array
    {
        $array = parent::fields();
        $array[] = SwitchBoolean::make('Премод', 'premod')->sortable();
        return $array;
    }
}
