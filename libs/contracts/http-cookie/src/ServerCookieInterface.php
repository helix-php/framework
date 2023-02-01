<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Cookie;

interface ServerCookieInterface extends CookieInterface
{
    /**
     * Retrieves the host to which the cookie will be sent.
     *
     * If omitted, this attribute defaults to the host of the current document
     * URL, not including subdomains.
     *
     * Contrary to earlier specifications, leading dots in domain names
     * (.example.com) are ignored.
     *
     * @return non-empty-string|\Stringable|null
     */
    public function getDomain(): string|\Stringable|null;

    /**
     * @psalm-immutable
     *
     * @param non-empty-string|\Stringable $domain
     *
     * @return self
     */
    public function withDomain(string|\Stringable $domain): self;

    /**
     * @psalm-immutable
     *
     * @return self
     */
    public function withoutDomain(): self;

    /**
     * Retrieves the maximum lifetime of the cookie as a DateTime object.
     *
     * If unspecified, the cookie becomes a session cookie. A session finishes
     * when the client shuts down, after which the session cookie is removed.
     *
     * @return \DateTimeInterface|null
     */
    public function getExpires(): ?\DateTimeInterface;

    /**
     * @psalm-immutable
     *
     * @param \DateTimeInterface $date
     *
     * @return self
     */
    public function withExpires(\DateTimeInterface $date): self;

    /**
     * @psalm-immutable
     *
     * @return self
     */
    public function withoutExpires(): self;

    /**
     * Retrieves the number of seconds until the cookie expires. A zero or
     * negative number will expire the cookie immediately.
     *
     * If both {@see getExpires()} and {@see getMaxAge()} are set (i.e. returns
     * not {@see null} value), this value has precedence.
     *
     * @return int<0, max>|null
     */
    public function getMaxAge(): ?int;

    /**
     * @psalm-immutable
     *
     * @param int<0, max> $seconds
     *
     * @return self
     */
    public function withMaxAge(int $seconds): self;

    /**
     * @psalm-immutable
     *
     * @return self
     */
    public function withoutMaxAge(): self;

    /**
     * Retrieves the path that must exist in the requested URL for the browser
     * to send the Cookie header.
     *
     * The forward slash (`/`) character is interpreted as a directory
     * separator, and subdirectories are matched as well. For example,
     * for path "/docs":
     *
     *  - The request paths "/docs", "/docs/", "/docs/web/", and
     *    "/docs/web/http" WILL all match.
     *
     *  - The request paths "/", "/docsets", "/fr/docs" WILL NOT match.
     *
     * @return non-empty-string|\Stringable
     */
    public function getPath(): string|\Stringable;

    /**
     * @psalm-immutable
     *
     * @param non-empty-string|\Stringable $path
     *
     * @return self
     */
    public function withPath(string|\Stringable $path): self;

    /**
     * Retrieves that the cookie is sent to the server only when a request is
     * made with the "https:" scheme (except on "localhost"), and therefore, is
     * more resistant to man-in-the-middle attacks.
     *
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * @psalm-immutable
     *
     * @param bool $secure
     *
     * @return self
     */
    public function withSecure(bool $secure): self;

    /**
     * Forbids JavaScript from accessing the cookie, for example, through the
     * `document.cookie` property. Note that a cookie that has been created
     * with {@see isHttpOnly()} will still be sent with JavaScript-initiated
     * requests, for example, when calling `XMLHttpRequest.send()` or `fetch()`.
     *
     * This mitigates attacks against cross-site scripting (XSS).
     *
     * @link https://developer.mozilla.org/en-US/docs/Glossary/Cross-site_scripting
     *
     * @return bool
     */
    public function isHttpOnly(): bool;

    /**
     * @psalm-immutable
     *
     * @param bool $httpOnly
     *
     * @return self
     */
    public function withHttpOnly(bool $httpOnly): self;

    /**
     * Retrieves that the cookie should be stored using partitioned storage.
     *
     * @link https://developer.mozilla.org/en-US/docs/Web/Privacy/Partitioned_cookies
     *
     * @return bool
     */
    public function isPartitioned(): bool;

    /**
     * @psalm-immutable
     *
     * @param bool $partitioned
     *
     * @return self
     */
    public function withPartitioned(bool $partitioned): self;

    /**
     * Controls whether or not a cookie is sent with cross-site requests,
     * providing some protection against cross-site request forgery attacks
     * (CSRF).
     *
     * @link https://developer.mozilla.org/en-US/docs/Glossary/CSRF
     *
     * @return SameSiteInterface|null
     */
    public function getSameSite(): ?SameSiteInterface;

    /**
     * @psalm-immutable
     *
     * @param SameSiteInterface $sameSite
     *
     * @return self
     */
    public function withSameSite(SameSiteInterface $sameSite): self;

    /**
     * @psalm-immutable
     *
     * @return self
     */
    public function withoutSameSite(): self;
}
