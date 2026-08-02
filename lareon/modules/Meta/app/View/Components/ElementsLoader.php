<?php

namespace Lareon\Modules\Meta\App\View\Components;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Lareon\Modules\Meta\App\Logics\MetaElementLogic;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

class ElementsLoader extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public int|MetaTemplate $template)
    {
        $this->template = $template instanceof MetaTemplate
            ? $template
            : MetaTemplate::find($template);

        $this->loadElements();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        return view('meta::components.editor.section.elements-loader');
    }

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    private function loadElements()
    {

        $views = [];

        if ($this->template === null) return $views;

        $logic = (new MetaElementLogic());

        $elements = $this->template->elements;
        foreach ($elements as $element) {
           $name=$element->pivot->name;
           $title=$element->pivot->title;
           dd($element);
            $res = $logic->getElementView($element->element);
            if (!$res->result) continue;
            $elementPath =  view($res->result , )->render();
            dd($elementPath);
        }

    }
}
