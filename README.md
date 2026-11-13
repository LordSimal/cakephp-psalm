# Psalm plugin for CakePHP

[![Latest Stable Version](https://poser.pugx.org/lordsimal/cakephp-psalm/v)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![Total Downloads](https://poser.pugx.org/lordsimal/cakephp-psalm/downloads)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![Latest Unstable Version](https://poser.pugx.org/lordsimal/cakephp-psalm/v/unstable)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![License](https://poser.pugx.org/lordsimal/cakephp-psalm/license)](https://packagist.org/packages/lordsimal/cakephp-psalm)

## Overview
This plugin provides correct return types for CakePHP specific methods in psalm.

## Quickstart
Please refer to the [full Psalm documentation](https://psalm.dev/quickstart) for a more detailed guide on
how to use Psalm in your project.

First you need to install the psalm base package and create a `psalm.xml`
```bash
composer require --dev vimeo/psalm
./vendor/bin/psalm --init
```

Next you will need to require this package and enable it in psalm
```bash
composer require --dev lordsimal/cakephp-psalm
./vendor/bin/psalm-plugin enable lordsimal/cakephp-psalm
```

Finally you can try it out
```bash
./vendor/bin/psalm
```

## Supported inference

The plugin refines table return types for:

* `Cake\ORM\Locator\LocatorInterface::get()`
* `Cake\ORM\Locator\TableLocator::get()` (including inherited calls)
* `Cake\ORM\Locator\LocatorAwareTrait::fetchTable()`

It recognizes literal aliases, variables and constants with known literal-string types,
fully qualified table class names, and finite unions of resolvable aliases:

```php
$users = $locator->get('Users'); // App\Model\Table\UsersTable
$alias = 'Users';
$users = $locator->get($alias); // App\Model\Table\UsersTable
$posts = $locator->get('Blog.Posts'); // Blog\Model\Table\PostsTable
$users = $this->fetchTable(UsersTable::class);
```

A known `className` option takes precedence over a conventional alias. Positional and
named arguments are supported, including named arguments in a different order:

```php
$archived = $locator->get('Users', ['className' => ArchivedUsersTable::class]);
$archived = $this->fetchTable(
    options: ['className' => 'ArchivedUsers'],
    alias: 'Users',
);
```

CakePHP gives a fully qualified alias precedence over `className` when the options do
not contain an explicit `alias`. Empty string `className` values use the original alias.
The plugin follows these rules where they can be established statically.

## Application setup

Table classes must be autoloadable in the PHP process running Psalm. Composer's
application and plugin autoload mappings must therefore be available. Aliases also
need CakePHP's `App.namespace` configuration; fully qualified names do not.

If your Psalm setup does not already load this configuration, create a small bootstrap
such as `config/psalm-bootstrap.php`:

```php
<?php
require dirname(__DIR__) . '/vendor/autoload.php';

Cake\Core\Configure::write('App.namespace', 'App');
```

Reference it in your existing `psalm.xml`:

```xml
<psalm autoloader="config/psalm-bootstrap.php">
    <!-- Keep your existing projectFiles and plugins configuration here. -->
</psalm>
```

Use your actual application namespace. If you already have a Psalm autoloader, add the
configuration there instead. Plugin aliases require their corresponding namespaces to
be autoloadable too.

## Conservative fallbacks and limitations

When a class cannot be resolved, the plugin returns control to Psalm and retains its
normal return-type inference. It does not replace an uncertain result with `mixed`.
This includes dynamic strings, unions containing an unresolved alias, unpacked
arguments, dynamic or optional `className` overrides, and unknown options arrays.
A call to `fetchTable()` without an alias keeps Psalm's normal type; the plugin does
not infer a mutable `defaultTable` property.

Resolution uses CakePHP's conventional `Model/Table` location. Runtime state such as
`setConfig()`, custom locator locations, registered or cached instances, and custom
locator implementations is not modeled. Fully qualified aliases combined with both
`alias` and `className` options are also left to Psalm. Classes known only to Psalm's
scanner, but unavailable through PHP autoloading, cannot be resolved by this plugin.

More precise inference can reveal existing mistakes in consuming applications and
therefore produce new Psalm diagnostics after an upgrade.

## Compatibility and development

The package supports CakePHP 5 and Psalm 5, 6, and 7. PHP requirements are determined
by the installed CakePHP and Psalm versions; this release does not raise them.
The test suite supports PHPUnit 10–13. CI tests compatible PHP/Psalm/PHPUnit
combinations and includes a lowest-dependency job.

```bash
composer install
composer check
```

The checks run static analysis, coding standards, PHPUnit tests with end-to-end table
inference fixtures. PHPUnit runs Psalm directly to assert exact resolved types and
verify conservative `Table` fallbacks.
