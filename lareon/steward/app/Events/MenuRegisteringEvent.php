<?php

namespace Lareon\Steward\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lareon\Steward\App\Enums\MenuAreaType;

class MenuRegisteringEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Collection of registered menu items.
     *
     * @var array<int, array<string, mixed>>
     */
    protected array $items = [];

    public function __construct(public readonly MenuAreaType $area)
    {
        $this->items[$this->area->value] = [];
    }

    /**
     * Add a single menu item.
     *
     * @param array{title: string, url: ?string, route: ?string, icon?: string|null, permission?: string|null, order?: int, parent?: string|null, active_pattern?: string|null, badge?: string|null} $item
     * @param string $module
     * @return $this
     */
    public function add(array $item, string $module = 'general'): self
    {
        $defaults = [
            'icon'           => null,
            'permission'     => null,
            'order'          => 999,
            'parent'         => null,
            'active_pattern' => null,
            'badge'          => null,
            'module'         => $module,
        ];

        $this->items[$this->area->value][] = array_merge($defaults, $item);

        return $this;
    }

    /**
     * Add multiple menu items.
     *
     * @param array<int, array> $items
     * @param string $module
     * @return $this
     */
    public function addMany(array $items, string $module = 'general'): self
    {
        foreach ($items as $item) {
            $this->add($item, $module);
        }

        return $this;
    }

    /**
     * Get all registered menu items sorted by order.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $items = $this->items;
        $items = $items[$this->area->value] ?? [];
        usort($items, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
        return array_values($items);
    }

    /**
     * Get menu items as hierarchical tree.
     *
     * @return array<string, array>
     */
    public function tree(): array
    {
        $tree = [];

        foreach ($this->all() as $item) {
            if (empty($item['parent'])) {
                $tree[$item['title']] = ['item' => $item, 'children' => []];
            } elseif (isset($tree[$item['parent']])) {
                $tree[$item['parent']]['children'][] = $item;
            }
        }

        return $tree;
    }

    /**
     * Filter menus by user permissions.
     *
     * @return array<int, array<string, mixed>>
     */
    public function visible(): array
    {
        return array_values(array_filter($this->all(), function ($item) {
            if (!isset($item['permission']) || is_null($item['permission']) === null) return true;

            return auth()->check() && auth()->user()->can($item['permission']);
        }
        ));
    }


    /**
     * Count total menus for this area.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items[$this->area->value] ?? []);
    }

    /**
     * Check if there are any menus for this area.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}
