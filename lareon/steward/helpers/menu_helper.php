
<?php


if (!function_exists('admin_menus')) {
    function admin_menus(bool $fresh = false): array
    {
        return app(\Lareon\Steward\App\Service\MenuService::class)->admin($fresh);
    }
}

if (!function_exists('panel_menus')) {
    function user_menus(bool $fresh = false): array
    {
        return app(\Lareon\Steward\App\Service\MenuService::class)->panel($fresh);
    }
}
