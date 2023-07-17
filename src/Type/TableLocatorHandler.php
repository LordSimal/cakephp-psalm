<?php
declare(strict_types=1);

namespace LordSimal\CakePHPPsalm\Type;

use Cake\Core\App;
use PhpParser\Node\Scalar\String_;
use Psalm\Plugin\EventHandler\Event\MethodReturnTypeProviderEvent;
use Psalm\Plugin\EventHandler\MethodReturnTypeProviderInterface;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Union;

class TableLocatorHandler implements MethodReturnTypeProviderInterface
{

    public static function getClassLikeNames(): array {
        return [
            'Cake\ORM\Locator\LocatorInterface', // get()
            'Cake\ORM\Locator\LocatorAwareTrait' // fetchTable()
        ];
    }

    public static function getMethodReturnType( MethodReturnTypeProviderEvent $event ): ?Union {

        $function_names = ['get', 'fetchtable'];

        if (in_array($event->getMethodNameLowercase(),$function_names)) {
            $args = $event->getCallArgs();

            if (!$args) {
                return null;
            }

            // The first argument needs to be a string, otherwise continue
            if($args[0]->value instanceof String_){
                $table_name = $args[0]->value->value;
                $className = App::className($table_name, 'Model/Table', 'Table');
                if ($className !== null) {
                    return new Union([
                        new TNamedObject($className),
                    ]);
                }
            }
        }

        return null;
    }
}