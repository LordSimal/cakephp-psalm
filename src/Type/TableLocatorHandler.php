<?php

declare(strict_types=1);

namespace LordSimal\CakePHPPsalm\Type;

use Cake\Core\App;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Locator\LocatorInterface;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\Table;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar\String_;
use Psalm\Plugin\EventHandler\Event\MethodReturnTypeProviderEvent;
use Psalm\Plugin\EventHandler\MethodReturnTypeProviderInterface;
use Psalm\Type\Atomic\TKeyedArray;
use Psalm\Type\Atomic\TLiteralString;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Union;

/** @api */
class TableLocatorHandler implements MethodReturnTypeProviderInterface
{
    public static function getClassLikeNames(): array
    {
        return [LocatorInterface::class, TableLocator::class, LocatorAwareTrait::class];
    }

    public static function getMethodReturnType(MethodReturnTypeProviderEvent $event): ?Union
    {
        if (!in_array($event->getMethodNameLowercase(), ['get', 'fetchtable'], true)) {
            return null;
        }

        $alias = null;
        $options = null;
        foreach ($event->getCallArgs() as $position => $arg) {
            // An unpacked argument can supply either parameter; do not guess its contents.
            if ($arg->unpack) {
                return null;
            }
            $name = $arg->name !== null ? $arg->name->toString() : null;
            if ($name === 'alias' || ($name === null && $position === 0)) {
                $alias = $arg->value;
            } elseif ($name === 'options' || ($name === null && $position === 1)) {
                $options = $arg->value;
            } else {
                return null;
            }
        }

        $names = $alias !== null ? self::literalStrings($alias, $event) : null;
        if ($options !== null) {
            $type = $event->getSource()->getNodeTypeProvider()->getType($options);
            if ($type === null) {
                return null;
            }
            if (!$type->isEmptyArray()) {
                // Only a single known shape can establish whether className overrides the alias.
                $types = $type->getAtomicTypes();
                if (count($types) !== 1) {
                    return null;
                }
                $shape = reset($types);
                if (!$shape instanceof TKeyedArray) {
                    return null;
                }
                $className = $shape->properties['className'] ?? null;
                if ($className !== null) {
                    if ($className->possibly_undefined) {
                        return null;
                    }
                    $overrides = self::stringsFromType($className);
                    if ($names === null || $overrides === null) {
                        return null;
                    }
                    $resolved = [];
                    foreach ($names as $name) {
                        // Cake gives FQCN aliases precedence unless an explicit alias option is set.
                        if (str_contains($name, '\\')) {
                            if (isset($shape->properties['alias']) || $shape->fallback_params !== null) {
                                return null;
                            }
                            $resolved[] = $name;
                            continue;
                        }
                        foreach ($overrides as $override) {
                            $resolved[] = $override === '' || $override === '0' ? $name : $override;
                        }
                    }
                    $names = $resolved;
                } elseif ($shape->fallback_params !== null) {
                    return null;
                }
            }
        }

        if ($names === null) {
            return null;
        }

        $tables = [];
        foreach ($names as $name) {
            if ($name === '') {
                return null;
            }
            $className = App::className($name, 'Model/Table', 'Table');
            if ($className === null || !is_a($className, Table::class, true)) {
                return null;
            }
            $tables[$className] = new TNamedObject($className);
        }

        return $tables !== [] ? new Union(array_values($tables)) : null;
    }

    /** @return non-empty-list<string>|null */
    private static function literalStrings(Expr $expression, MethodReturnTypeProviderEvent $event): ?array
    {
        if ($expression instanceof String_) {
            return [$expression->value];
        }

        $type = $event->getSource()->getNodeTypeProvider()->getType($expression);

        return $type !== null ? self::stringsFromType($type) : null;
    }

    /** @return non-empty-list<string>|null */
    private static function stringsFromType(Union $type): ?array
    {
        $strings = [];
        foreach ($type->getAtomicTypes() as $atomic) {
            if (!$atomic instanceof TLiteralString) {
                return null;
            }
            $strings[] = $atomic->value;
        }

        return $strings;
    }
}
