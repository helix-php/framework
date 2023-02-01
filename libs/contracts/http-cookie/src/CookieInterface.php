<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Cookie;

use Helix\Contracts\Http\Header\HeaderInterface;

interface CookieInterface extends HeaderInterface
{
    /**
     * Retrieves the Cookie name as a non empty string.
     *
     * The string MUST contain only the US-ASCII characters except control
     * characters, spaces, tabs and one of separator chars like the following:
     *
     * - ( ) < > @ , ; : \ " / [ ] ? = { }.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * A Cookie Name can contain any US-ASCII characters except for: the control
     * character, space, or a tab. It also must not contain separator characters
     * like the following:
     *
     *  - ( ) < > @ , ; : \ " / [ ] ? = { }.
     *
     * Note: Some Cookie Name have a specific semantic:
     *
     *  - "__Secure-" prefix: Cookies with names starting with "__Secure-"
     *    (dash is part of the prefix) must be set with the secure flag from a
     *    secure page (HTTPS).
     *
     *  - "__Host-" prefix: Cookies with names starting with "__Host-" must be
     *    set with the secure flag, must be from a secure page (HTTPS), must not
     *    have a domain specified (and therefore, are not sent to subdomains),
     *    and the path must be "/".
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new cookie name.
     *
     * @psalm-immutable
     *
     * @param non-empty-string $name
     *
     * @return self
     */
    public function withName(string $name): self;

    /**
     * Retrieves the Cookie value as a string.
     *
     * The string CAN optionally be wrapped in double quotes and include any
     * US-ASCII character excluding a control character, whitespace, double
     * quotes, comma, semicolon, and backslash.
     *
     * @return string
     */
    public function getValue(): string|\Stringable;

    /**
     * A Cookie Value can optionally be wrapped in double quotes and include any
     * US-ASCII character excluding a control character, whitespace, double
     * quotes, comma, semicolon, and backslash.
     *
     * Encoding: Many implementations perform URL encoding on cookie values.
     * However, this is not required by the RFC specification. The URL encoding
     * does help to satisfy the requirements of the characters allowed for the
     * value.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new cookie value.
     *
     * @psalm-immutable
     *
     * @param string|\Stringable $value
     *
     * @return self
     */
    public function withValue(string|\Stringable $value): self;
}
