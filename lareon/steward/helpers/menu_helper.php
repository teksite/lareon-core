<?php

use Lareon\Steward\App\Service\MenuService;

if (!function_exists('steward_menus')) {
    /**
     * Get all registered menus from all modules
     *
     * @param bool $ignoreCache Whether to ignore cached menus
     * @return array<int, array<string, mixed>> Array of all menu items
     *
     * @example
     * $menus = steward_menus();
     * foreach($menus as $menu) {
     *     echo $menu['title'];
     * }
     */
    function steward_menus(bool $ignoreCache = false): array
    {
        return app(MenuService::class)->getAllMenus($ignoreCache);
    }
}

if (!function_exists('steward_menus_for_module')) {
    /**
     * Get menus belonging to a specific module
     *
     * @param string $module Module name (e.g., 'blog', 'shop')
     * @param bool $ignoreCache Whether to ignore cached menus
     * @return array<int, array<string, mixed>> Array of menu items for the module
     *
     * @example
     * $blogMenus = steward_menus_for_module('blog');
     */
    function steward_menus_for_module(string $module, bool $ignoreCache = false): array
    {
        return app(MenuService::class)->getMenusForModule($module, $ignoreCache);
    }
}

if (!function_exists('steward_grouped_menus')) {
    /**
     * Get hierarchical grouped menus for sidebar navigation
     *
     * @param bool $ignoreCache Whether to ignore cached menus
     * @return array<string, array> Grouped menus with parent-child structure
     *
     * @example
     * $grouped = steward_grouped_menus();
     * foreach($grouped as $parent) {
     *     echo $parent['item']['title'];
     *     foreach($parent['children'] as $child) {
     *         echo $child['title'];
     *     }
     * }
     */
    function steward_grouped_menus(bool $ignoreCache = false): array
    {
        return app(MenuService::class)->getGroupedMenus($ignoreCache);
    }
}

if (!function_exists('steward_clear_menu_cache')) {
    /**
     * Clear the menu cache for current user or all users
     *
     * @param bool $forAllUsers Whether to clear cache for all users
     * @return void
     *
     * @example
     * // Clear cache after updating menu structure
     * steward_clear_menu_cache();
     */
    function steward_clear_menu_cache(bool $forAllUsers = false): void
    {
        app(MenuService::class)->clearCache($forAllUsers);
    }
}
