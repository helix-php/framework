<?php

declare(strict_types=1);

namespace Helix\Http\Cookie;

use Helix\Contracts\Http\Cookie\SameSiteInterface;

enum SameSite: string implements SameSiteInterface
{
    /**
     * The browser sends the cookie only for same-site requests (that is,
     * requests originating from the same site that set the cookie). If the
     * request originated from a different URL than the current one, no cookies
     * with the SameSite=Strict attribute are sent.
     */
    case STRICT = 'Strict';

    /**
     * The cookie is withheld on cross-site subrequests, such as calls to load
     * images or frames, but is sent when a user navigates to the URL from an
     * external site, such as by following a link
     */
    case LAX = 'Lax';

    /**
     * The browser sends the cookie with both cross-site and same-site requests.
     */
    case NONE = 'None';

    /**
     * @param non-empty-string|\Stringable $value
     *
     * @return SameSiteInterface
     */
    public static function parse(string|\Stringable $value): SameSiteInterface
    {
        /** @var array<non-empty-string, UserSameSite> $memory */
        static $memory = [];

        $value = (string)$value;
        $lower = \strtolower($value);

        return self::tryFrom(\ucfirst($lower)) ?? $memory[$lower] ??= new UserSameSite($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
