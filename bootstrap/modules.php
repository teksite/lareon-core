<?php return [
  'User' => [
    'provider' => 'Lareon\\Modules\\User\\App\\Providers\\UserServiceProvider',
    'active' => true,
    'type' => 'steward',
  ],
  'Auth' => [
    'provider' => 'Lareon\\Modules\\Auth\\App\\Providers\\AuthServiceProvider',
    'active' => true,
    'type' => 'steward',
  ],
  'FileManager' => [
    'provider' => 'Lareon\\Modules\\FileManager\\App\\Providers\\FileManagerServiceProvider',
    'active' => true,
    'type' => 'self',
  ],
];