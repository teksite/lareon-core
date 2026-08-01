<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

#[Fillable(['title' ,'element' ,'settings'])]
#[Table('meta_elements')]
class MetaElement extends Model
{

    protected function casts(): array
    {
        return [
            'settings'=>'json'
        ];
    }
    public static function rules(string $operation, int|null $id = null): array
    {
        return match (true) {
            $operation === 'create'          => [
                'title' => 'required|string|max:100|unique:meta_elements,title',
                'element'  => 'required|string|max:100|unique:meta_elements,element',
                'settings'  => 'nullable|array',
            ],
            ($operation === 'update' && $id) => [
                'title' => ['required', 'string', Rule::unique('meta_elements', 'title')->ignore($id)],
                'settings'  => 'nullable|array',
//                'element'  => ['required', 'string', Rule::in('meta_elements', 'element')->ignore($id)],
            ],
            default                          => throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };
    }

    public function templates(): HasMany
    {
        return $this->hasMany(MetaFieldTemplate::class, 'meta_element_id');
    }

}
