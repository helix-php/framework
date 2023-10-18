<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Tests\Reader;

use Helix\Contracts\ParamResolver\Exception\SignatureExceptionInterface;
use Helix\ParamResolver\Factory\ReaderInterface;
use Helix\ParamResolver\Factory\StatelessReader;
use Helix\ParamResolver\Tests\TestCase;

function testing_function(\StdClass $arg): void {}

/**
 * @group param-resolver
 */
class ReadersTestCase extends TestCase
{
    /**
     * @return array<non-empty-string, array{ReaderInterface}>
     */
    public function readersDataProviders(): array
    {
        return [
            StatelessReader::class => [new StatelessReader()],
        ];
    }

    /**
     * @dataProvider readersDataProviders
     */
    public function testCreationFromFunction(ReaderInterface $reader): void
    {
        $params = $reader->fromFunction(__NAMESPACE__ . '\\testing_function');

        $this->assertCount(1, $params);
        $this->assertSame('arg', $params[0]->getName());
    }

    /**
     * @dataProvider readersDataProviders
     */
    public function testCreationFromUnknownFunction(ReaderInterface $reader): void
    {
        $this->expectException(SignatureExceptionInterface::class);

        $reader->fromFunction(__NAMESPACE__ . '\\__unknown_function');
    }

    /**
     * @dataProvider readersDataProviders
     */
    public function testCreationFromObjectMethod(ReaderInterface $reader): void
    {
        $params = $reader->fromMethod($this, __FUNCTION__);

        $this->assertCount(1, $params);
        $this->assertSame('reader', $params[0]->getName());
    }

    /**
     * @dataProvider readersDataProviders
     */
    public function testCreationFromUndefinedObjectMethod(ReaderInterface $reader): void
    {
        $this->expectException(SignatureExceptionInterface::class);

        $reader->fromMethod($this, '__undefined_method');
    }

    /**
     * @dataProvider readersDataProviders
     */
    public function testCreationFromStaticMethod(ReaderInterface $reader): void
    {
        $params = $reader->fromMethod(self::class, 'testing_method');

        $this->assertCount(1, $params);
        $this->assertSame('arg', $params[0]->getName());
    }

    /**
     * @dataProvider readersDataProviders
     */
    public function testCreationFromUndefinedStaticMethod(ReaderInterface $reader): void
    {
        $this->expectException(SignatureExceptionInterface::class);

        $reader->fromMethod(self::class, '__undefined_method');
    }

    private static function testing_method(\StdClass $arg): void {}
}
