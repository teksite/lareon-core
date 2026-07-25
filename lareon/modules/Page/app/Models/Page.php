<?php

namespace Lareon\Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Lareon\Steward\App\Casts\PublishAt;
use Lareon\Steward\App\Enums\PublishStatusEnum;
use Lareon\Steward\App\Models\Scopes\PublishScope;
use Teksite\Extralaravel\Casts\SlugCast;


#[Fillable(['parent_id', 'label', 'slug', 'title', 'excerpt', 'body', 'image', 'template', 'publish_status', 'published_at'])]
class Page extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'published_at'     => PublishAt::class,
            'published_status' => PublishStatusEnum::class,
            'slug'             => SlugCast::class,

        ];
    }


    public static function rules(): array
    {
        return [
            'parent_id'      => 'nullable|exists:pages,id',
            'slug'           => 'required|string|max:255|unique:pages,slug',
            'title'          => 'required|string|max:255',
            'excerpt'        => 'nullable|string',
            'body'           => 'nullable|string',
            'image'          => 'nullable|string|max:255',
            'template'       => 'nullable|string',
            'publish_status' => ['required', 'string', Rule::in(array_column(PublishStatusEnum::cases(), 'value'))],
            'published_at'   => 'nullable|date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new PublishScope('admin.page.read'));
    }


    public function breadCrumb()
    {
        return [
            $this->attributes['title'] = $this->path(),
        ];

    }


    public function sitemapGroup(): string
    {
        return 'pages';
    }

    public function path(): string
    {
        return route('pages.show', $this);
    }


}
