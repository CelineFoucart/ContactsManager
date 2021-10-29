<?php
require 'configs/config.php';
$path = __DIR__ . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR;

return
[
    'paths' => [
        'migrations' => $path . 'migrations',
        'seeds' => $path . 'seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => DB_SETTINGS['server'],
            'name' => DB_SETTINGS['dbname'],
            'user' => DB_SETTINGS['user'],
            'pass' => DB_SETTINGS['password'],
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => DB_SETTINGS['server'],
            'name' => DB_SETTINGS['dbname'],
            'user' => DB_SETTINGS['user'],
            'pass' => DB_SETTINGS['password'],
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
