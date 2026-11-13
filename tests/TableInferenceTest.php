<?php

declare(strict_types=1);

namespace LordSimal\CakePHPPsalm\Tests;

use PHPUnit\Framework\TestCase;

/** @psalm-suppress UnusedClass Instantiated by PHPUnit. */
final class TableInferenceTest extends TestCase
{
    public function testInferredTypesAndConservativeFallbacks(): void
    {
        $command = [
            PHP_BINARY,
            dirname(__DIR__) . '/vendor/bin/psalm',
            '--config=' . __DIR__ . '/fixtures/psalm.xml',
            '--no-cache',
            '--no-progress',
            '--threads=1',
            '--show-info=true',
            '--output-format=json',
        ];
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, dirname(__DIR__));
        self::assertIsResource($process);
        $output = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);
        self::assertSame(0, $exitCode, (string)$output . (string)$stderr);
        self::assertIsString($output);
        /** @var list<array{type: string, message: string}> $issues */
        $issues = json_decode($output, true, 512, JSON_THROW_ON_ERROR);
        $traces = [];
        foreach ($issues as $issue) {
            if ($issue['type'] === 'Trace') {
                $traces[] = $issue['message'];
            }
        }
        foreach (
            ['unknown', 'unresolved', 'dynamicOptions', 'partialUnion', 'dynamicClass',
            'optionalClass', 'unpacked', 'default'] as $variable
        ) {
            self::assertNotEmpty(
                preg_grep('/^\$' . $variable . ': Cake\\\\ORM\\\\Table(?:<.*>)?$/', $traces),
                'Expected a Table fallback for $' . $variable . ': ' . $output
            );
        }
    }
}
