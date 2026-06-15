<?php

namespace Lareon\Steward\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Lareon\Steward\App\Enums\CacheAction;
use Lareon\Steward\App\Enums\CacheType;

class CacheExecutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('admin.setting.cache.execute');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type'   => ['required', new Enum(CacheType::class),],
            'action' => ['required', new Enum(CacheAction::class),],
        ];
    }
}
