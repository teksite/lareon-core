<?php

namespace Lareon\Modules\Menu\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Lareon\Modules\Menu\App\Models\Menu;
use Lareon\Modules\Menu\App\Models\SubMenu;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class SubMenuLogic
{

    /**
     * Columns the form is allowed to change.
     * Add 'attributes' here only when the form actually sends it, otherwise it would be wiped.
     */
    private  array $FIELDS = ['title', 'subtitle', 'pre_icon', 'next_icon', 'image', 'url', 'classes'];

    private  int    $CHUNK = 500;
    private  string $TABLE = 'menus_items';

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get(SubMenu::class, ['title']),
        )->run();
    }


    /**
     * @throws \Throwable
     */
    public function allByMenu(Menu $menu, mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($menu) {
            return $menu->subs()->get([
                'id', 'parent_id', 'position', 'title', 'subtitle',
                'pre_icon', 'next_icon', 'image', 'url', 'classes',
            ]);
        })->run();
    }
    /**
     * @throws \Throwable
     */
    public function tree(Menu $menu, mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($menu) {
            $items = SubMenu::query()->where('menu_id', $menu->id)->tree()->get();
            return $items->toTree();
        })->run();
    }


    /**
     * @param Menu  $menu
     * @param array $items validated "items" input, keyed by id / temporary "rand..." key
     * @return ServiceResultContract
     * @throws \Throwable
     */
    public function sync(Menu $menu, array $items,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($menu ,$items) {

            return DB::transaction(function () use ($menu, $items) {
            $usesTimestamps = (new SubMenu)->usesTimestamps();
            $now = now();

            $current = DB::table($this->TABLE)
                         ->where('menu_id', $menu->id)
                         ->lockForUpdate()
                         ->get(array_merge(['id', 'parent_id', 'position'], $this->FIELDS))
                         ->keyBy('id');

            $nodes = $this->buildNodes($items, $current);


            $order = $this->topologicalOrder($nodes);

            $idMap = [];
            $keptIds = [];
            foreach ($nodes as $key => $node) {
                if (!$node['new']) {
                    $idMap[(string)$key] = (int)$key;
                    $keptIds[] = (int)$key;
                }
            }

            $created = 0;
            foreach ($order as $key) {
                $node = $nodes[$key];

                if (!$node['new']) continue;

                $row = $this->row($menu->id, $node, $idMap);
                if ($usesTimestamps) $row['created_at'] = $row['updated_at'] = $now;

                $idMap[$key] = (int)DB::table($this->TABLE)->insertGetId($row);
                $created++;
            }

            $updates = [];
            foreach ($order as $key) {
                $node = $nodes[$key];
                if ($node['new']) continue;

                $row = $this->row($menu->id, $node, $idMap);
                if (!$this->differs($row, (array)$current[(int)$key])) continue;


                $row['id'] = (int)$key;
                if ($usesTimestamps) $row['created_at'] = $row['updated_at'] = $now; // created_at only used on the (never taken) insert path
                $updates[] = $row;
            }

            if ($updates) {
                $updateColumns = array_merge(['parent_id', 'position'], $this->FIELDS);
                if ($usesTimestamps) $updateColumns[] = 'updated_at';


                foreach (array_chunk($updates, $this->CHUNK) as $chunk) {
                    DB::table($this->TABLE)->upsert($chunk, ['id'], $updateColumns);
                }
            }

            // 3) Delete removed items (after re-parenting, so kept children never get cascaded away)
            $removedIds = array_diff(array_map('intval', $current->keys()->all()), $keptIds);
            foreach (array_chunk($removedIds, $this->CHUNK) as $chunk) {
                DB::table($this->TABLE)->where('menu_id', $menu->id)->whereIn('id', $chunk)->delete();
            }

            return [
                'created' => $created,
                'updated' => count($updates),
                'deleted' => count($removedIds),
            ];
        });
        })->run();

    }

    /* ---------------------------------------------------------------------- */

    /**
     * Normalizes and validates the payload structure.
     * The client-sent "id" field is ignored: only array keys that match this menu's rows count as existing.
     *
     * @return array<string, array{new:bool, parent:?string, position:int, data:array}>
     */
    private function buildNodes(array $items, Collection $current,): array
    {
        $nodes = [];

        foreach ($items as $rawKey => $item) {
            $key = (string)$rawKey;
            if (!is_array($item) || !preg_match('/^[A-Za-z0-9_-]{1,64}$/', $key)) throw $this->invalid();


            $parent = $item['parent_id'] ?? null;
            if ($parent !== null && !is_scalar($parent)) throw $this->invalid();

            $parent = ($parent === null || $parent === '') ? null : (string)$parent;

            $nodes[$key] = [
                'new'      => !(ctype_digit($key) && $current->has((int)$key)),
                'parent'   => $parent,
                'position' => max(0, (int)($item['position'] ?? 0)),
                'data'     => $this->pick($item),
            ];
        }

        // Every parent must exist in the payload (this also blocks parents from other menus)
        foreach ($nodes as $key => $node) {

            if ($nodes[$key]['data']['title'] === null) continue;

            if ($node['parent'] !== null && ($node['parent'] === (string)$key || !isset($nodes[$node['parent']]))) throw $this->invalid();

        }

        return $nodes;
    }

    /**
     * Orders keys so every parent comes before its children; throws on cycles. O(n).
     *
     * @return string[]
     */
    private function topologicalOrder(array $nodes,): array
    {
        $order = [];
        $state = []; // 1 = on current chain, 2 = done

        foreach (array_keys($nodes) as $start) {
            $chain = [];
            $key = (string)$start;

            while ($key !== null && !isset($state[$key])) {
                $state[$key] = 1;
                $chain[] = $key;
                $key = $nodes[$key]['parent'];
            }

            if ($key !== null && $state[$key] === 1) throw $this->invalid('ساختار منو حلقه دارد؛ یک آیتم نمی‌تواند زیرمجموعه‌ی خودش باشد.');


            foreach (array_reverse($chain) as $done) {
                $state[$done] = 2;
                $order[] = (string)$done;
            }
        }

        return $order;
    }

    /** Whitelist of writable fields; empty strings become NULL. */
    private function pick(array $item,): array
    {
        $data = [];
        foreach ($this->FIELDS as $field) {
            $value = isset($item[$field]) && is_string($item[$field]) ? trim($item[$field]) : null;
            $data[$field] = $value === '' ? null : $value;
        }

        return $data;
    }

    private function row(int $menuId, array $node, array $idMap,): array
    {
        return array_merge([
            'menu_id'   => $menuId,
            'parent_id' => $node['parent'] === null ? null : $idMap[$node['parent']],
            'position'  => $node['position'],
        ], $node['data']);
    }

    private function differs(array $new, array $old,): bool
    {
        foreach (array_merge(['parent_id', 'position'], $this->FIELDS) as $column) {
            if ($this->norm($new[$column] ?? null) !== $this->norm($old[$column] ?? null)) return true;

        }

        return false;
    }

    private function norm(mixed $value,): ?string
    {
        return ($value === null || $value === '') ? null : (string)$value;
    }

    private function invalid(string $message = 'ساختار منوی ارسال‌شده نامعتبر است.',): ValidationException
    {
        return ValidationException::withMessages(['items' => $message]);
    }
}

