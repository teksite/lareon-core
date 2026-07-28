<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

#[Fillable(['title'])]
#[Table('meta_elements')]
class MetaElement extends Model
{


    public static function rules(string $operation, int|null $id = null): array
    {
        return match (true) {
            $operation === 'create'          => [
                'title' => 'required|string|max:100',
                'path'  => 'required|string|max:100',
            ],
            ($operation === 'update' && $id) => [
                'title' => ['required', 'string', Rule::unique('meta_elements', 'title')->ignore($id)],
                'path'  => ['required', 'string', Rule::unique('meta_elements', 'path')->ignore($id)],
            ],
            default                          => throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };
    }

    public function templates(): HasMany
    {
        return $this->hasMany(MetaFieldTemplate::class, 'element_id');
    }

}
