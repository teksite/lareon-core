<?php

namespace Lareon\Modules\Auth\App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Layout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ?string $title=null )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return \Illuminate\Support\Facades\View::first(['authentication.layouts.master','auth::authentication.layouts.master']);
    }
}
