<?php

namespace Lareon\Modules\Menu\App\Models;

use Closure;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

#[Fillable(['menu_id', 'parent_id', 'position', 'title', 'subtitle', 'pre_icon', 'next_icon', 'image', 'url', 'classes', 'attributes',])]
class SubMenu extends Model
{
    use HasRecursiveRelationships;

    protected $table = 'menus_items';

    /** Rules for ONE item (apply them under the "items.*." prefix). */
    public static function rules(): array
    {
        return [
            'parent_id'  => ['nullable', 'string', 'max:64'],
            'position'   => ['nullable', 'integer', 'min:0', 'max:65535'],
            'title'      => ['nullable', 'string', 'max:255'],
            'subtitle'   => ['nullable', 'string', 'max:255'],
            'pre_icon'   => ['nullable', 'string', 'max:255'],
            'next_icon'  => ['nullable', 'string', 'max:255'],
            'image'      => ['nullable', 'string', 'max:2048'],
            'classes'    => ['nullable', 'string', 'max:500'],
            'attributes' => ['nullable', 'string'],
            'url'        => ['nullable', 'string', 'max:2048', function (string $attribute, mixed $value, Closure $fail,) {
                if (!self::isSafeUrl($value)) $fail('آدرس نامعتبر است. فقط http، https، mailto، tel یا آدرس نسبی مجاز است.');
            },
            ],

        ];
    }

    public static function isSafeUrl(?string $url,): bool
    {
        if ($url === null || $url === '') return true;

        $normalized = preg_replace('/[\x00-\x20\x7F]+/', '', $url);

        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $normalized, $match)) {
            return in_array(strtolower($match[1]), ['http', 'https', 'mailto:', 'tel:' ,'/'], true);
        }
        return true;
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
