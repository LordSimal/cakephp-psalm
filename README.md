# Psalm plugin for CakePHP 5

[![Latest Stable Version](http://poser.pugx.org/lordsimal/cakephp-psalm/v)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![Total Downloads](http://poser.pugx.org/lordsimal/cakephp-psalm/downloads)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![Latest Unstable Version](http://poser.pugx.org/lordsimal/cakephp-psalm/v/unstable)](https://packagist.org/packages/lordsimal/cakephp-psalm) [![License](http://poser.pugx.org/lordsimal/cakephp-psalm/license)](https://packagist.org/packages/lordsimal/cakephp-psalm)

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
composer require --dev lordsimal/cakephp-psalm @dev
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
