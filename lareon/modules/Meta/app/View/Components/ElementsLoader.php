<?php

namespace Lareon\Modules\Meta\App\View\Components;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Lareon\Modules\Meta\App\Logics\MetaElementLogic;
use Lareon\Modules\Meta\App\Models\MetaModel;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

class ElementsLoader extends Component
{
    private MetaTemplate $template;

    /**
     * Create a new component instance.
     */
    public function __construct(int|MetaTemplate $template, public $value)
    {
        $this->template = $template instanceof MetaTemplate
            ? $template
            : MetaTemplate::findOrFail($template);
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        return view('meta::components.editor.section.elements-loader', [
            'elements' => $this->loadElements(),
        ]);
    }


    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    private function loadElements(): array
    {

        $elementLogic = app(MetaElementLogic::class);

        return $this->template->elements
            ->sortBy('pivot.sort')
            ->map(function (Model $element) use ($elementLogic) {

                $name = $element->pivot->name;
                $value = $this->value->firstWhere('key', $name)?->content;

                return [
                    'view' => $elementLogic->getElementView($element->element)->result,

                    'props' => [
                        'elementId' => $element->id,
                        'name'      => "meta_data[$name]",
                        'title'     => $element->pivot->title,
                        'arguments' => $element->pivot->settings['arguments'] ?? [],
                        'settings'  => $element->settings,
                        'element'   => $element,
                        'pivot'     => $element->pivot,
                        'value'     => $value,
                    ],
                ];
            })
            ->values()
            ->all();


    }
}
