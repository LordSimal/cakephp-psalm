<?php

namespace LordSimal\CakePHPPsalm;

use LordSimal\CakePHPPsalm\Type\TableLocatorHandler;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

final class Plugin implements PluginEntryPointInterface
{
    #[\Override]
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        require_once __DIR__ . '/Type/TableLocatorHandler.php';
        $psalm->registerHooksFromClass(TableLocatorHandler::class);
    }
}
