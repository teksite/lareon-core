<?php

namespace Lareon\Modules\Questionnaire\App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Lareon\Modules\Questionnaire\App\Models\Form;

class FormLayout extends Component
{
    public null|Form $form;
    public bool      $ajax;

    /**
     * Create a new component instance.
     */
    public function __construct(int|string $form, $ajax = true,)
    {
        $this->form = Form::query()->find($form) ?? null;
        $this->ajax = $ajax;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return $this->form
            ? view('questionnaire::client.layouts.form-layout', ['form' => $this->form])
            : <<<EOT
                   <span class="text">{{__('the form is not available')}}</span>
                   EOT;
    }
}
