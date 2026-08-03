<?php

namespace Lareon\Steward\App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AdminTrash extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $items = null,
        public string $trashIndex,
        public string $backTo,
        public string $title,) {}
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('lareon::admin.layouts.trash');
    }
}
