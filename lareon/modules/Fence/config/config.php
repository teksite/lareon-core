<?php

return [

    'name' => 'Fence',

    /*
    |--------------------------------------------------------------------------
    | Enable Fence
    |--------------------------------------------------------------------------
    |
    | Master switch for the IP fence. When set to false, the middleware is
    | bypassed entirely and every visitor is allowed through, regardless of
    | the configured mode or the stored IP addresses.
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Fence Mode
    |--------------------------------------------------------------------------
    |
    | Determines how the stored IP addresses are interpreted.
    |
    | Available modes:
    |
    | "black" - Only IP addresses stored as BLACK are denied access.
    |           Everyone else is allowed.
    |
    | "white" - Only IP addresses stored as WHITE are allowed access.
    |           Everyone else is denied.
    |
    | WARNING: Before switching to "white" mode, make sure your own IP
    | address is on the whitelist. Otherwise you may lock yourself out of
    | the site, including the admin panel.
    |
    */

    'mode' => 'black',

    /*
    |--------------------------------------------------------------------------
    | Storage Driver
    |--------------------------------------------------------------------------
    |
    | Defines where the IP addresses are persisted. Both drivers expose the
    | same interface, so switching between them requires no code changes.
    |
    | Available drivers:
    |
    | "file"     - Stores records in a JSON file (see "store_file" below).
    |              Suitable for small lists and setups without a database.
    |
    | "database" - Stores records in the database using the Fence model.
    |              Recommended for large lists and high-traffic sites.
    |
    | Note: Existing records are NOT migrated automatically when you change
    | the driver. Each driver keeps its own separate list.
    |
    */

    'store_type' => 'file',

    /*
    |--------------------------------------------------------------------------
    | File Storage Path
    |--------------------------------------------------------------------------
    |
    | Absolute path of the JSON file used by the "file" driver. The file and
    | its parent directory are created automatically on the first write.
    |
    | Keep this file outside of the public directory so it can never be
    | accessed directly from the browser.
    |
    */

    'store_file' => storage_path('app/private/fence.json'),
];
