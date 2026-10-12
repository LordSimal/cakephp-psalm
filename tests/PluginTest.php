<?php

namespace LordSimal\CakePHPPsalm\Tests;

use LordSimal\CakePHPPsalm\Plugin;
use LordSimal\CakePHPPsalm\Type\TableLocatorHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

final class PluginTest extends TestCase
{
    private RegistrationInterface&MockObject $registration;

    #[\Override]
    protected function setUp(): void
    {
        $this->registration = $this->createMock(RegistrationInterface::class);
        $this->registration->expects($this->once())
            ->method('registerHooksFromClass')
            ->with(TableLocatorHandler::class);
    }

    public function testHasEntryPoint(): void
    {
        $plugin = new Plugin();
        $plugin($this->registration, null);
    }

    public function testAcceptsConfig(): void
    {
        $plugin = new Plugin();
        $plugin($this->registration, new SimpleXMLElement('<myConfig></myConfig>'));
    }
}
