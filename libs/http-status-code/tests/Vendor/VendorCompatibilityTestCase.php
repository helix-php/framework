<?php

declare(strict_types=1);

namespace Helix\Http\StatusCode\Tests\Vendor;

use Helix\Http\StatusCode\StatusCode;
use Helix\Http\StatusCode\Tests\TestCase;

/**
 * @group http-status-code
 */
abstract class VendorCompatibilityTestCase extends TestCase
{
    /**
     * @return iterable<int>
     */
    abstract protected function getStatusCodes(): iterable;

    public function statusCodesDataProvider(): array
    {
        $result = [];

        foreach ($this->getStatusCodes() as $code => $text) {
            $result['Status-Code: ' . $code] = [$code, $text];
        }

        return $result;
    }

    /**
     * @dataProvider statusCodesDataProvider
     */
    public function testStatusCodeAvailable(int $code, string $text): void
    {
        $actual = StatusCode::parse($code);

        $message = "Unsupported Status Code: $code ($text)";
        $this->assertInstanceOf(StatusCode::class, $actual, $message);
    }
}
