<?php

namespace App\MoonShine\Fields;

use MoonShine\Decorations\Button;
use MoonShine\Decorations\Decoration;

class ClosureButton extends Decoration
{
    public function __construct(string $label, string $link, bool $blank = false)
    {
        parent::__construct($label);

        $this->addLink($label, $link, $blank);
    }
}
