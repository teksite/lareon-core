<?php

namespace Lareon\Steward\App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Teksite\Handler\Actions\ServiceResult;
use Teksite\Handler\Contracts\ServiceResult as ServiceResultContract;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Services\FetchDataService;

trait HasTrashLogic
{
    /**
     * Cached model class name (per using class, since traits are
     * compiled into the class that uses them).
     *
     * @var string|null
     */
    private static ?string $modelClass = null;

    /**
     * Get the model class name - must be implemented by the using class
     */
    abstract protected function getModelClass(): string;

    /**
     * Resolve and cache the model class name
     */
    private function modelClass(): string
    {
        return self::$modelClass ??= $this->getModelClass();
    }


    /**
     * Get the trashed model query builder instance
     */
    private function trashedQuery(): Builder
    {
        return ($this->modelClass())::onlyTrashed();
    }

    /**
     * Count all trashed records
     */
    public function trashCount(): ServiceResultContract
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => $this->trashedQuery()->count())
                             ->run();
    }


    /**
     * Get paginated trashed records
     */
    public function getTrashes(int $perPage = 25, mixed $fetchData = []): ServiceResultContract
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => FetchDataService::get(
                                 $this->trashedQuery(),
                                 ['title',  'deleted_at'],
                                 perPage: $perPage,
                             ))
                             ->run();
    }


    /**
     * Restore one or multiple records
     */
    public function restore(int|array|null $id = null): ServiceResultContract
    {
        if (is_null($id)) return new ServiceResult(false, null);

        return ServiceWrapper::make(true)
                             ->do(fn() => $this->scopedTrashedQuery($id)->restore())
                             ->run();
    }

    /**
     * Restore a single record
     */
    public function restoreOne(int $id): ServiceResultContract
    {
        return $this->restore($id);
    }


    /**
     * Restore all trashed records
     */
    public function restoreAll(): ServiceResultContract
    {
        return ServiceWrapper::make(true)
                             ->do(fn() => $this->trashedQuery()->restore())
                             ->run();
    }

    /**
     * Permanently delete records with their relationships
     */
    public function wipe(null|int|array $id = null): ServiceResultContract
    {
        if (is_null($id)) return new ServiceResult(false, null);

        return ServiceWrapper::make(true)
                             ->do(function () use ($id) {
                                 $query = $this->scopedTrashedQuery($id);
                                 $this->deleteRelationships($query);
                                 $query->forceDelete();
                             })
                             ->run();
    }

    /**
     * Permanently delete a single record
     */
    public function wipeOne(int $id): ServiceResultContract
    {
        return $this->wipe($id);
    }

    /**
     * Permanently delete all trashed records
     */
    public function wipeAll(): ServiceResultContract
    {
        return ServiceWrapper::make(true)
                             ->do(function () {
                                 $query = $this->trashedQuery();
                                 $this->deleteRelationships($query);
                                 $query->forceDelete();
                             })
                             ->run();
    }


    /**
     * Build a trashed query scoped to one or more specific IDs
     */
    private function scopedTrashedQuery(int|array $id): Builder
    {
        $ids = is_array($id) ? $id : [$id];

        return $this->trashedQuery()->whereIn('id', $ids);
    }

    /**
     * Detach configured relationships for every record matched by the query
     */
    private function deleteRelationships(Builder $query): void
    {
        $relations = config('steward.relations_on_trash', []);

        if (empty($relations)) return;

        $modelClass = $this->modelClass();

        $relations = array_filter(
            $relations,
            fn(string $relation) => method_exists($modelClass, $relation)
        );

        if (empty($relations)) return;


        $query->clone()->chunkById(200, function ($items) use ($relations) {
            foreach ($items as $item) {
                foreach ($relations as $relation) {
                    $item->$relation()->detach();
                }
            }
        });
    }
}
