<?php

declare(strict_types=1);

namespace ReviewApp;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Locator\LocatorInterface;
use Cake\ORM\Locator\TableLocator;
use ReviewApp\Model\Table\ArchivedUsersTable;
use ReviewApp\Model\Table\UsersTable;
use ReviewPlugin\Model\Table\PostsTable;

const USERS = 'Users';

/** @param array<string, mixed> $options */
function inspect(LocatorInterface $locator, TableLocator $concrete, bool $choose, string $dynamic, array $options): void
{
    $literal = $locator->get('Users');
    /** @psalm-check-type-exact $literal = \ReviewApp\Model\Table\UsersTable */
    $concreteTable = $concrete->get('Users');
    /** @psalm-check-type-exact $concreteTable = \ReviewApp\Model\Table\UsersTable */
    $alias = 'Users';
    $variable = $locator->get($alias);
    /** @psalm-check-type-exact $variable = \ReviewApp\Model\Table\UsersTable */
    $constant = $locator->get(USERS);
    /** @psalm-check-type-exact $constant = \ReviewApp\Model\Table\UsersTable */
    $plugin = $locator->get('ReviewPlugin.Posts');
    /** @psalm-check-type-exact $plugin = \ReviewPlugin\Model\Table\PostsTable */
    $fqcn = $locator->get(UsersTable::class);
    /** @psalm-check-type-exact $fqcn = \ReviewApp\Model\Table\UsersTable */
    $union = $locator->get($choose ? 'Users' : 'ArchivedUsers');
    /** @psalm-check-type-exact $union = \ReviewApp\Model\Table\UsersTable|\ReviewApp\Model\Table\ArchivedUsersTable */
    $override = $locator->get('Users', ['className' => ArchivedUsersTable::class]);
    /** @psalm-check-type-exact $override = \ReviewApp\Model\Table\ArchivedUsersTable */
    $named = $locator->get(options: ['className' => 'ArchivedUsers'], alias: 'Users');
    /** @psalm-check-type-exact $named = \ReviewApp\Model\Table\ArchivedUsersTable */
    $fqcnOverride = $locator->get(UsersTable::class, ['className' => ArchivedUsersTable::class]);
    /** @psalm-check-type-exact $fqcnOverride = \ReviewApp\Model\Table\UsersTable */
    $emptyOverride = $locator->get('Users', ['className' => '']);
    /** @psalm-check-type-exact $emptyOverride = \ReviewApp\Model\Table\UsersTable */
    $knownOptions = ['className' => 'ReviewPlugin.Posts'];
    $shape = $locator->get('Users', $knownOptions);
    /** @psalm-check-type-exact $shape = \ReviewPlugin\Model\Table\PostsTable */
    $emptyOptions = $locator->get('Users', []);
    /** @psalm-check-type-exact $emptyOptions = \ReviewApp\Model\Table\UsersTable */
    $otherOptions = $locator->get('Users', ['table' => 'custom_users']);
    /** @psalm-check-type-exact $otherOptions = \ReviewApp\Model\Table\UsersTable */
    $unknown = $locator->get($dynamic);
    /** @psalm-trace $unknown */
    $unresolved = $locator->get('DoesNotExist');
    /** @psalm-trace $unresolved */
    $dynamicOptions = $locator->get('Users', $options);
    /** @psalm-trace $dynamicOptions */
    $partialUnion = $locator->get($choose ? 'Users' : 'DoesNotExist');
    /** @psalm-trace $partialUnion */
    $dynamicClass = $locator->get('Users', ['className' => $dynamic]);
    /** @psalm-trace $dynamicClass */
    $optionalOptions = $choose ? ['className' => 'ArchivedUsers'] : [];
    $optionalClass = $locator->get('Users', $optionalOptions);
    /** @psalm-trace $optionalClass */
    $unpacked = $locator->get(...['Users']);
    /** @psalm-trace $unpacked */
    echo $literal::class;
}

final class Consumer
{
    use LocatorAwareTrait;

    public function inspect(): void
    {
        $table = $this->fetchTable('Users');
        /** @psalm-check-type-exact $table = \ReviewApp\Model\Table\UsersTable */
        $override = $this->fetchTable(options: ['className' => ArchivedUsersTable::class], alias: 'Users');
        /** @psalm-check-type-exact $override = \ReviewApp\Model\Table\ArchivedUsersTable */
        $default = $this->fetchTable();
        /** @psalm-trace $default */
        echo $table::class;
    }
}
