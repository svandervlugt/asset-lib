<?php
namespace PhpUnit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ResolverTest extends TestCase
{
    /**
     * @dataProvider commandProvider
     */
    public function testImportTypes(string $expected_file, string $input)
    {
        $process = new Process(
            'resolver resolve ' . $input,
            __DIR__ . '/fixtures',
            ['PATH' => realpath(__DIR__ . '/../bin')]
        );
        $process->run();

        self::assertTrue($process->isSuccessful(), $process->getErrorOutput());
        self::assertEquals(
            $this->fixLineEndings(file_get_contents(__DIR__ . '/expected/' . $expected_file)),
            $this->fixLineEndings($process->getOutput())
        );
    }

    public function commandProvider()
    {
        return [
            ['resolver.less-import-syntax.txt', 'resolver/less/import-syntax/main.less'],
            ['resolver.js-require-syntax.txt', 'resolver/js/require-syntax/main.js'],
            ['resolver.ts-import-syntax.txt', 'resolver/ts/import-syntax/main.ts'],
            ['resolver.dts-module.txt', 'resolver/ts/dts-module/main.ts'],
        ];
    }

    private function fixLineEndings(string $input, string $desired = "\n"): string
    {
        $input = implode($desired, array_map('trim', explode("\n", $input)));

        return str_replace("\\", '/', $input);
    }
}
