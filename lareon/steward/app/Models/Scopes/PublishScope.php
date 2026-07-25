<?php

namespace Lareon\Steward\App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Lareon\Steward\App\Enums\PublishStatusEnum;

class PublishScope implements Scope
{

    public function __construct(public null|string|array $permissions = null) {}

    protected static array $columnExistsCache = [];
    private const string AdminRoutePattern = 'admin.*';

    protected static ?bool $canSeeAllRecordsCache = null;


    public function apply(Builder $builder, Model $model): void
    {

        $table = $model->getTable();


        if ($this->canSeeAllRecords()) return;

        $hasPublishedAtColumn = $this->hasPublishedAtColumn($model->getTable());

        $builder->where(function (Builder $query) use ($hasPublishedAtColumn) {
            $query->where('publish_status', PublishStatusEnum::PUBLISHED->value)
                  ->orWhere(function (Builder $subQuery) use ($hasPublishedAtColumn) {
                      $subQuery->where('publish_status', PublishStatusEnum::POSTPONE->value)
                               ->when($hasPublishedAtColumn, fn(Builder $q) => $q->where('published_at', '<=', now()));
                  });
        });
    }

    protected function hasPublishedAtColumn(string $table): bool
    {
        return self::$columnExistsCache[$table] ??= Schema::hasColumn($table, 'published_at');
    }

    protected function canSeeAllRecords(): bool
    {
        $key = is_array($this->permissions)
            ? implode('|', $this->permissions)
            : (string)$this->permissions;

        return self::$canSeeAllRecordsCache[$key] ??= $this->resolveCanSeeAllRecords();
    }

    protected function resolveCanSeeAllRecords(): bool
    {
        if (app()->runningInConsole() && !app()->runningUnitTests()) return true;
        if (!request()->routeIs(self::AdminRoutePattern)) return false;
        if (empty($this->permissions)) return true;


        return Auth::check() && Auth::user()->canAny($this->permissions);
    }

    public static function flushCache(): void
    {
        self::$columnExistsCache = [];

        self::$canSeeAllRecordsCache = null;
    }
}
