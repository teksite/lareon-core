<?php

namespace Lareon\Steward\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lareon\Steward\App\Enums\MenuAreaType;

class MenuRegisteringEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Collection of registered menu items.
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    protected array $items = [];

    /**
     * Collection of children items grouped by parent group.
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    protected array $children = [];

    public function __construct(public readonly MenuAreaType $area)
    {
        $this->items[$this->area->value] = [];
    }

    /**
     * Add a single menu item.
     *
     * @param array{title: string, url: ?string, route: ?string, icon?: string|null, permission?: string|null, order?: int, active?: string|null, badge?: string|null} $item
     * @param string|null $group
     * @return $this
     */
    public function add(array $item, string $group = null): self
    {
        $this->items[$this->area->value][] = [...$item, 'group' => $group];
        return $this;
    }

    /**
     * Add multiple menu items.
     *
     * @param array<int, array> $items
     * @param string|null $group
     * @return $this
     */
    public function addMany(array $items, string $group = null): self
    {
        foreach ($items as $item) {
            $this->add($item, $group);
        }
        return $this;
    }

    /**
     * Add a child item to a specific group.
     *
     * @param array $item
     * @param string $group
     * @return $this
     */
    public function addItem(array $item, string $group): self
    {
        if (!isset($this->children[$group])) {
            $this->children[$group] = [];
        }
        $this->children[$group][] = $item;
        return $this;
    }


    /**
     * Add multiple child items to a specific group.
     *
     * @param array $items
     * @param string $group
     * @return $this
     */
    public function addManyItem(array $items, string $group): self
    {
        foreach ($items as $item) {
            $this->addItem($item, $group);
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
        $items = $this->items[$this->area->value] ?? [];
        usort($items, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
        return array_values($items);
    }

    /**
     * Get menu items as hierarchical tree with children injection based on group.
     *
     * @return array<int, array<string, mixed>>
     */
    public function tree(): array
    {
        $tree = [];

        $parentItems = $this->all();

        // Sort parent items by order
        usort($parentItems, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));

        // Build tree structure
        foreach ($parentItems as $parent) {
            $groupName = $parent['group'] ?? null;
            if ($groupName) {
                $parentWithChildren = $parent;
                $parentWithChildren['children'] = [];

                if (isset($this->children[$groupName])) {
                    $children = $this->children[$groupName];
                    usort($children, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
                    $parentWithChildren['children'] = $children;

                    $tree[] = $parentWithChildren;

                }
            }



        }
        return $tree;
    }

    /**
     * Get hierarchical tree with full multi-level support.
     *
     * @return array<int, array<string, mixed>>
     */
    public function deepTree(): array
    {
        $allItems = $this->all();
        $tree = [];
        $itemsByIdentifier = [];

        // Index items by their group/title for quick lookup
        foreach ($allItems as $item) {
            $identifier = $item['group'] ?? $item['title'];
            $itemsByIdentifier[$identifier] = $item;
            $itemsByIdentifier[$identifier]['children'] = [];
        }

        // Build the tree structure
        foreach ($allItems as $item) {
            $identifier = $item['group'] ?? $item['title'];

            if (!empty($item['parent']) && isset($itemsByIdentifier[$item['parent']])) {
                // This is a child item, add to parent's children
                $itemsByIdentifier[$item['parent']]['children'][] = $item;
            } else {
                // This is a parent item
                if (!in_array($itemsByIdentifier[$identifier], $tree, true)) {
                    $tree[] = &$itemsByIdentifier[$identifier];
                }
            }
        }

        // Sort entire tree recursively
        $this->sortTreeRecursively($tree);

        return $tree;
    }

    /**
     * Recursively sort tree items by order.
     *
     * @param array &$tree
     * @return void
     */
    protected function sortTreeRecursively(array &$tree): void
    {
        usort($tree, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));

        foreach ($tree as &$item) {
            if (!empty($item['children'])) {
                $this->sortTreeRecursively($item['children']);
            }
        }
    }

    /**
     * Filter menus by user permissions.
     *
     * @return array<int, array<string, mixed>>
     */
    public function visible(): array
    {
        return array_values(array_filter($this->all(), function ($item) {
            if (!isset($item['permission']) || is_null($item['permission'])) {
                return true;
            }

            return auth()->check() && auth()->user()->can($item['permission']);
        }));
    }

    /**
     * Get visible tree (permission-filtered).
     *
     * @return array<int, array<string, mixed>>
     */
    public function visibleTree(): array
    {
        $tree = $this->tree();

        // Filter tree items by permission
        $filtered = array_filter($tree, function ($item) {
            return $this->hasPermission($item);
        });

        // Filter children recursively
        foreach ($filtered as &$item) {
            if (!empty($item['children'])) {
                $item['children'] = array_values(array_filter($item['children'], function ($child) {
                    return $this->hasPermission($child);
                }));
            }
        }

        return array_values($filtered);
    }

    /**
     * Check if an item has permission.
     *
     * @param array $item
     * @return bool
     */
    protected function hasPermission(array $item): bool
    {
        if (!isset($item['permission']) || is_null($item['permission'])) {
            return true;
        }

        return auth()->check() && auth()->user()->can($item['permission']);
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
