<?php

namespace Lareon\Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Lareon\Modules\FileManager\App\Traits\HasImages;
use Lareon\Modules\Meta\App\Traits\HasTemplate;
use Lareon\Modules\Seo\App\Traits\HasSeo;
use Lareon\Steward\App\Casts\PublishAt;
use Lareon\Steward\App\Enums\PublishStatusEnum;
use Lareon\Steward\App\Models\Scopes\PublishScope;
use Teksite\Extralaravel\Casts\SlugCast;
use Teksite\FileManager\Concerts\HasAttachedFile;


#[Fillable(['parent_id', 'label', 'slug', 'title', 'excerpt', 'body', 'template_id', 'publish_status', 'published_at', 'primary_media_id'])]
class Page extends Model
{
    use SoftDeletes, HasImages, HasAttachedFile, HasTemplate , HasSeo;

    protected function casts(): array
    {
        return [
            'published_at'   => PublishAt::class,
            'publish_status' => PublishStatusEnum::class,
            'slug'           => SlugCast::class,
        ];
    }


    public static function rules(string $operation, int|null $id = null): array
    {
        $rules = [
            'parent_id'        => 'nullable|integer|min:0|exists:pages,id',
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string',
            'body'             => 'nullable|string',
            'primary_media_id' => 'nullable|string|max:255|exists:uploaded_files,id',
            'template_id'      => 'nullable|exists:meta_templates,id',
            'publish_status'   => ['required', 'integer', Rule::in(array_column(PublishStatusEnum::cases(), 'value'))],
            'published_at'     => 'nullable|date',
            'meta_data'=>'nullable|array',
        ];

        $rules['slug'] = match (true) {
            $operation === 'create' => 'required|string|max:255|unique:pages,slug',
            $operation === 'update' => ['required', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($id)],
            default                 => throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };

        return $rules;
    }

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new PublishScope('admin.page.read'));
    }

    public function breadCrumb(): array
    {
        return [
            [
                'title' => $this->attributes['title'],
                'url'   => $this->path(),
            ],
        ];
    }

    public function path(): ?string
    {
        return Route::has('pages.show') ? route('pages.show', $this) : null;
    }

    public function sitemapGroup(): string
    {
        return 'pages';
    }

    public function scopeWithCollection($query, string $collection)
    {
        return $query->with([
            'files' => fn($q) => $q->wherePivot('collection', $collection),
        ]);
    }
}
