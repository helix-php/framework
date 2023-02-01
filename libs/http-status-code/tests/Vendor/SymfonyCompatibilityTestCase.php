<?php

declare(strict_types=1);

namespace Helix\Http\StatusCode\Tests\Vendor;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * @group http-status-code
 */
class SymfonyCompatibilityTestCase extends VendorCompatibilityTestCase
{
    public function setUp(): void
    {
        if (!\class_exists(SymfonyResponse::class)) {
            $this->markTestIncomplete('"symfony/http-foundation" package required');
        }

        parent::setUp();
    }

    protected function getStatusCodes(): array
    {
        return SymfonyResponse::$statusTexts;
    }
}
