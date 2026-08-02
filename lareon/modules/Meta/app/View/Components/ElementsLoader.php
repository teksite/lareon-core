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


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        return view('meta::components.editor.section.elements-loader', ['elements' => $this->loadElements()]);
    }

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    private function loadElements(): array
    {
        return $this->template->elements
            ->sortBy('pivot.sort')
            ->map(function ($element) {
                return [
                    'view' => app(MetaElementLogic::class)
                        ->getElementView($element->element)
                        ->result,
                    'props' => [
                        'name' => $element->pivot->name,
                        'title' => $element->pivot->title,
                        'arguments' => $element->pivot->settings['arguments'] ?? [],
                        'settings' => $element->settings,
                        'element' => $element,
                        'pivot' => $element->pivot,
                    ],
                ];
            })
            ->all();
    }
}
