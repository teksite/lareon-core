<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

#[Fillable(['title' ,'element'])]
#[Table('meta_elements')]
class MetaElement extends Model
{


    public static function rules(string $operation, int|null $id = null): array
    {
        return match (true) {
            $operation === 'create'          => [
                'title' => 'required|string|max:100|unique:meta_elements,title',
                'element'  => 'required|string|max:100|unique:meta_elements,element',
            ],
            ($operation === 'update' && $id) => [
                'title' => ['required', 'string', Rule::unique('meta_elements', 'title')->ignore($id)],
                'element'  => ['required', 'string', Rule::unique('meta_elements', 'element')->ignore($id)],
            ],
            default                          => throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };
    }

    public function templates(): HasMany
    {
        return $this->hasMany(MetaFieldTemplate::class, 'element_id');
    }

}
