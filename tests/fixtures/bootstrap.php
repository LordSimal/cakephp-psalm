<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require __DIR__ . '/UsersTable.php';
require __DIR__ . '/ArchivedUsersTable.php';
require __DIR__ . '/PostsTable.php';

Cake\Core\Configure::write('App.namespace', 'ReviewApp');
