# Psalm plugin for CakePHP

[![Latest Stable Version](https://poser.pugx.org/lordsimal/cakephp-psalm/v)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![Total Downloads](https://poser.pugx.org/lordsimal/cakephp-psalm/downloads)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![Latest Unstable Version](https://poser.pugx.org/lordsimal/cakephp-psalm/v/unstable)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![License](https://poser.pugx.org/lordsimal/cakephp-psalm/license)](https://packagist.org/packages/lordsimal/cakephp-psalm)

## Overview
This plugin provides correct return types for CakePHP specific methods in psalm.

## Requirements

* PHP 8.5 or newer
* CakePHP 6
* Psalm 7

CakePHP 6 currently requires development stability, and Psalm 7 is available as a release candidate.
Until stable releases are available, set `"minimum-stability": "dev"` and `"prefer-stable": true`
in your project's `composer.json`. This also allows CakePHP 6's Chronos 4 development dependency.

## Quickstart
Please refer to the [full Psalm documentation](https://psalm.dev/quickstart) for a more detailed guide on
how to use Psalm in your project.

First you need to install the psalm base package and create a `psalm.xml`
```bash
composer require --dev "vimeo/psalm:^7.0"
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

## How it works

Currently only the following return types are being corrected:

* `Cake\ORM\Locator\LocatorInterface::get()`
* `Cake\ORM\Locator\LocatorAwareTrait::fetchTable()`

The functionality for this can be found in `src/Type/TableLocatorHandler.php`

In there the `getClassLikeNames()` tells psalm on which classes it needs to
change return types.

In the `getMethodReturnType()` we check which method is currently called and
get the first argument value from that call.

With that value we get the "real" FQCN of the table which is returned in runtime 
and tell psalm to use that instead of the default `Cake\ORM\Table`.

## Help needed

I would definitely appreciate help related to the following aspects of this plugin:

* Testing
* Including more return types like [CakeDC/cakephp-phpstan](https://github.com/CakeDC/cakephp-phpstan) already does for PHPStan
