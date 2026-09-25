<?php

namespace Lareon\Modules\Questionnaire\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Lareon\Steward\App\Enums\PublishStatusEnum;

#[Fillable(['title', 'body', 'template', 'has_file', 'response_client', 'active',])]
class Form extends Model
{
    use SoftDeletes;

    protected $table = 'questionnaire_forms';


    protected function casts(): array
    {
        return [
            'has_file'        => 'boolean',
            'active'          => 'boolean',
            'response_client' => 'boolean',
        ];
    }

    public static function rules(string $operation= 'create' , int|null $ignoreId =null): array
    {
        $rules = [
            'body'            => 'nullable',
            'template'        => 'nullable|string',
            'has_file'        => 'sometimes|in:0,1',
            'response_client' => 'sometimes|in:0,1',
            'active'          => 'sometimes|in:0,1',
        ];

        $rules['title'] = match (true) {
            $operation === 'create' => 'required|string|max:255|unique:questionnaire_forms,slug',
            $operation === 'update' => ['required', 'string', 'max:255', Rule::unique('questionnaire_forms', 'title')->ignore($ignoreId)],
            default                 => throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };

        return $rules;

    }

    public function inbox(): HasMany
    {
        return $this->hasMany(FormInbox::class, 'form_id');
    }
    public function unreadInbox(): HasMany
    {
        return $this->inbox()->where('read_at', null);
    }

    public function validationRules(): HasOne
    {
        return $this->hasOne(FormRule::class, 'form_id');
    }

    public function announcement(): HasOne
    {
        return $this->hasOne(FormAnnouncement::class, 'form_id');
    }

    public function response(): HasOne
    {
        return $this->hasOne(ResponseTemplate::class, 'form_id');
    }
}
