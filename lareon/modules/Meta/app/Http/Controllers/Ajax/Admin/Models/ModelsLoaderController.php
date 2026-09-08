<?php

namespace Lareon\Modules\Meta\App\Http\Controllers\Ajax\Admin\Models;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Meta\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Page\App\Models\Page;
use Teksite\Handler\Facade\Responder;

class ModelsLoaderController extends Controller implements HasMiddleware
{


    public static function middleware(): array
    {
        return [
            new Middleware('can:admin'),
        ];
    }

    public function load(Request $request)
    {
        $data = $request->validate([
            'valueField'  => ['required', 'string'],
            'labelField'  => ['required', 'string'],
            'searchField' => ['required', 'string'],
            'query'       => ['nullable', 'string'],
            'model'       => ['required', 'string'],
        ]);


        $valueField = $data['valueField'];
        $labelField = $data['labelField'];
        $searchField = $data['searchField'];
        $query = $data['query'] ?? '';
        $model = $data['model'];


        if (!$query || !$searchField || !$valueField || !$model || !$labelField) return Responder::success(['items' => []])->reply();

        $modelQuery = $model::query();

        $searchFields = array_filter(explode('|', $searchField));

        if ($query !== '') {
            $modelQuery->where(function ($q) use ($searchFields, $query) {
                foreach ($searchFields as $index => $field) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $q->{$method}($field, 'like', "%{$query}%");
                }
            });
        }


        $columns = array_unique([$valueField, $labelField, ...$searchFields,]);

        $items = $modelQuery->select($columns)->limit(20)->get();

        return Responder::success()->data($items)->reply();

    }
}
