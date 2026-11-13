<?php

namespace LordSimal\CakePHPPsalm\Tests;

use LordSimal\CakePHPPsalm\Plugin;
use LordSimal\CakePHPPsalm\Type\TableLocatorHandler;
use PHPUnit\Framework\TestCase;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

/** @psalm-suppress UnusedClass Instantiated by PHPUnit. */
final class PluginTest extends TestCase
{
    public function testRegistersHandlerWithoutConfig(): void
    {
        $this->registerPlugin(null);
    }

    public function testRegistersHandlerWithConfig(): void
    {
        $this->registerPlugin(new SimpleXMLElement('<config/>'));
    }

    private function registerPlugin(?SimpleXMLElement $config): void
    {
        $registration = $this->createMock(RegistrationInterface::class);
        $registration->expects($this->once())
            ->method('registerHooksFromClass')
            ->with(TableLocatorHandler::class);

        (new Plugin())($registration, $config);
    }
}
