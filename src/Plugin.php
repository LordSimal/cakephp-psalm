<?php

namespace LordSimal\CakePHPPsalm;

use LordSimal\CakePHPPsalm\Type\TableLocatorHandler;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{
    /** @return void */
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        require_once 'Type/TableLocatorHandler.php';
        $psalm->registerHooksFromClass(TableLocatorHandler::class);
    }
}
