<?php

declare(strict_types=1);

namespace Helix\Http\Cookie;

use Helix\Contracts\Http\Cookie\SameSiteInterface;
use Helix\Contracts\Http\Cookie\ServerCookieInterface;
use Helix\Contracts\Http\Header\Attribute\FlagInterface;
use Helix\Http\Cookie\SetCookie\SameSite;
use Helix\Http\Header\Attribute\Attribute;
use Helix\Http\Header\Attribute\Flag;
use Helix\Http\Header\Collection;
use Helix\Http\Header\AttributesTrait;
use Psr\Clock\ClockInterface;

class ServerCookie extends Cookie implements ServerCookieInterface
{
    use AttributesTrait;

    /**
     * Indicates the maximum lifetime of the cookie as an HTTP-date timestamp.
     *
     * When {@see ServerCookie} object is serialized into a string, the
     * specified {@see \DateTimeInterface} object will be converted to the
     * following string:
     *
     * ```
     * <day-name>, <day> <month> <year> <hour>:<minute>:<second> GMT
     * ```
     *
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Date
     *
     * If unspecified, the cookie becomes a session cookie. A session finishes
     * when the client shuts down, after which the session cookie is removed.
     *
     * > Warning: Many web browsers have a session restore feature that will
     * > save all tabs and restore them the next time the browser is used.
     * > Session cookies will also be restored, as if the browser was never
     * > closed.
     *
     * When an "Expires" date is set, the deadline is relative to the client the
     * cookie is being set on, not the server.
     *
     * > Note: If both "Expires" ({@see ServerCookie::$expires}) and "Max-Age"
     * > ({@see ServerCookie::$maxAge}) are set, "Max-Age" has precedence.
     *
     * If this value contains {@see null}, then this attribute will not be
     * added into serialized string.
     *
     * @var \DateTimeInterface|null
     */
    private ?\DateTimeInterface $expires = null;

    /**
     * Indicates the number of seconds until the cookie expires. A zero or
     * negative number will expire the cookie immediately.
     *
     * > Note: If both "Expires" ({@see ServerCookie::$expires}) and "Max-Age"
     * > ({@see ServerCookie::$maxAge}) are set, "Max-Age" has precedence.
     *
     * If this value contains {@see null}, then this attribute will not be
     * added into serialized string.
     *
     * @var int<0, max>|null
     */
    private ?int $maxAge = null;

    /**
     * Defines the host to which the cookie will be sent.
     *
     * If omitted, this attribute defaults to the host of the current document
     * URL, not including subdomains.
     *
     * Contrary to earlier specifications, leading dots in domain
     * names (".example.com") are ignored.
     *
     * Multiple host/domain values are not allowed, but if a domain is
     * specified, then subdomains are always included.
     *
     * If this value contains {@see null}, then this attribute will not be
     * added into serialized string.
     *
     * @var non-empty-string|null
     */
    private string|null $domain = null;

    /**
     * Indicates the path that must exist in the requested URL for the browser
     * to send the Cookie header.
     *
     * The forward slash ("/") character is interpreted as a directory
     * separator, and subdirectories are matched as well.
     *
     * For example, for "Path=/docs":
     *  - The request paths "/docs", "/docs/", "/docs/Web/", and
     *    "/docs/Web/HTTP" will all match.
     *  - The request paths "/", "/docsets", "/fr/docs" will NOT match.
     *
     * @var non-empty-string
     */
    private string $path = '/';

    /**
     * Controls whether or not a cookie is sent with cross-origin requests,
     * providing some protection against cross-site request forgery
     * attacks (CSRF: {@link https://developer.mozilla.org/en-US/docs/Glossary/CSRF}).
     *
     * All valid values are listed in {@see SameSite} enum.
     *
     * If this value contains {@see null}, then this attribute will not be
     * added into serialized string.
     *
     * @var SameSiteInterface|null
     */
    private ?SameSiteInterface $sameSite = null;

    /**
     * Indicates that the cookie is sent to the server only when a request is
     * made with the "https" scheme (except on localhost), and therefore, is
     * more resistant to man-in-the-middle
     * ({@link https://developer.mozilla.org/en-US/docs/Glossary/MitM}) attacks.
     *
     * If this value contains {@see false}, then this attribute will not be
     * added into serialized string.
     *
     * > Note: Do not assume that "Secure" prevents all access to sensitive
     * > information in cookies (session keys, login details, etc.). Cookies
     * > with this attribute can still be read/modified either with access to
     * > the client's hard disk or from JavaScript if the HttpOnly cookie
     * > attribute is not set.
     * >
     * > Insecure sites ("http") cannot set cookies with the "Secure"
     * > attribute (since Chrome 52 and Firefox 52). For Firefox, the "https"
     * > requirements are ignored when the Secure attribute is set by
     * > localhost (since Firefox 75).
     *
     * @var bool
     */
    private bool $secure = false;

    /**
     * Indicates that the cookie should be stored using partitioned storage.
     *
     * Cookies Having Independent Partitioned State (CHIPS, also know as
     * Partitioned cookies) allows developers to opt a cookie into partitioned
     * storage, with a separate cookie jar per top-level site.
     *
     * A partitioned third-party cookie is tied to the top-level site where it's
     * initially set and cannot be accessed from elsewhere. The aim is to allow
     * cookies to be set by third-party services, but only read within the
     * context of the top-level site where they were initially set. This allows
     * cross-site tracking to be blocked, while still enabling non-tracking uses
     * of third-party cookies such as persisting state of embedded maps or chat
     * widgets across different sites, and persisting config information for
     * subresource CDN load balancing and Headless CMS providers.
     *
     * @link https://developer.mozilla.org/en-US/docs/Web/Privacy/Partitioned_cookies
     *
     * @var bool
     */
    private bool $partitioned = false;

    /**
     * Forbids JavaScript from accessing the cookie, for example, through
     * the `document.cookie` (#1) property. Note that a cookie that has been created with "HttpOnly" will
     * still be sent with JavaScript-initiated requests, for example, when
     * calling `XMLHttpRequest.send()` (#2) or `fetch()` (#3). This mitigates attacks
     * against cross-site scripting (XSS: #4).
     *
     * - \#1 {@link https://developer.mozilla.org/en-US/docs/Web/API/Document/cookie}
     * - \#2 {@link https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest/send}
     * - \#3 {@link https://developer.mozilla.org/en-US/docs/Web/API/fetch}
     * - \#4 {@link https://developer.mozilla.org/en-US/docs/Glossary/Cross-site_scripting}
     *
     * If this value contains {@see false}, then this attribute will not be
     * added into serialized string.
     *
     * @var bool
     */
    private bool $httpOnly = false;

    /**
     * @param non-empty-string $name
     * @param string|\Stringable $value
     * @param \DateTimeInterface|null $expires
     * @param int<0, max>|null $maxAge
     * @param non-empty-string|\Stringable|null $domain
     * @param non-empty-string|\Stringable $path
     * @param SameSiteInterface|null $sameSite
     * @param bool $secure
     * @param bool $httpOnly
     * @param bool $partitioned
     * @param iterable<FlagInterface> $attributes Additional Cookie's attributes
     */
    public function __construct(
        string $name,
        string|\Stringable $value = '',
        ?\DateTimeInterface $expires = null,
        ?int $maxAge = null,
        \Stringable|string|null $domain = null,
        \Stringable|string $path = '/',
        ?SameSiteInterface $sameSite = null,
        bool $secure = false,
        bool $httpOnly = false,
        bool $partitioned = false,
        iterable $attributes = [],
    ) {
        parent::__construct($name, $value, $attributes);

        $this->setExpires($expires);
        $this->setMaxAge($maxAge);
        $this->setDomain($domain);
        $this->setPath($path);
        $this->setSameSite($sameSite);
        $this->setSecure($secure);
        $this->setHttpOnly($httpOnly);
        $this->setAttributes($attributes);
    }

    /**
     * Returns {@see true} in case of this cookie is session or {@see false}
     * instead.
     *
     * @return bool
     */
    public function isSession(): bool
    {
        return $this->maxAge === null && $this->expires === null;
    }

    /**
     * @return void
     */
    public function setSession(): void
    {
        $this->expires = $this->maxAge = null;
    }

    /**
     * @psalm-immutable
     *
     * @return $this
     */
    public function withMarkedAsSession(): self
    {
        $self = clone $this;
        $self->setSession();

        return $self;
    }

    /**
     * Returns {@see true} in case of the cookie marked as deleted.
     *
     * @param ClockInterface|null $clock
     *
     * @return bool
     */
    public function isDeleted(ClockInterface $clock = null): bool
    {
        if ($this->expires === null) {
            return false;
        }

        return $this->expires > ($clock?->now() ?? new \DateTime());
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->setMaxAge(null);
        $this->setExpires((new \DateTimeImmutable())->setTimestamp(0));
    }

    /**
     * @psalm-immutable
     *
     * @return self
     */
    public function withMarkedAsDeleted(): self
    {
        $self = clone $this;
        $self->delete();

        return $self;
    }


    /**
     * {@inheritDoc}
     */
    public function getExpires(): ?\DateTimeInterface
    {
        return $this->expires;
    }

    /**
     * Updates the "Expires" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withExpires()} method.
     *
     * @param \DateTimeInterface|null $expires
     *
     * @return void
     */
    public function setExpires(?\DateTimeInterface $expires): void
    {
        $this->expires = $expires;
    }

    /**
     * {@inheritDoc}
     */
    public function withExpires(\DateTimeInterface $date): self
    {
        $self = clone $this;
        $self->setExpires($date);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutExpires(): self
    {
        $self = clone $this;
        $self->setExpires(null);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    /**
     * Updates the "Max-Age" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withMaxAge()} method.
     *
     * @param int<0, max>|null $maxAge
     *
     * @return void
     */
    public function setMaxAge(?int $maxAge): void
    {
        $this->maxAge = $maxAge;
    }

    /**
     * {@inheritDoc}
     */
    public function withMaxAge(int $seconds): self
    {
        $self = clone $this;
        $self->setMaxAge($seconds);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutMaxAge(): self
    {
        $self = clone $this;
        $self->setMaxAge(null);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getDomain(): string|null
    {
        return $this->domain;
    }

    /**
     * Updates the "Domain" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withDomain()} method.
     *
     * @param non-empty-string|\Stringable|null $domain
     *
     * @return void
     */
    public function setDomain(string|\Stringable|null $domain): void
    {
        if ($domain === '') {
            throw new \InvalidArgumentException('Cookie domain cannot be empty');
        }

        $this->domain = $domain;
    }

    /**
     * {@inheritDoc}
     */
    public function withDomain(string|\Stringable $domain): self
    {
        $self = clone $this;
        $self->setDomain($domain);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutDomain(): self
    {
        $self = clone $this;
        $self->setDomain(null);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string|\Stringable
    {
        return $this->path;
    }

    /**
     * Updates the "Path" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withPath()} method.
     *
     * @param non-empty-string|\Stringable $path
     *
     * @return void
     */
    public function setPath(string|\Stringable $path): void
    {
        if ($path === '') {
            throw new \InvalidArgumentException('Cookie path cannot be empty');
        }

        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function withPath(string|\Stringable $path = ''): self
    {
        $self = clone $this;
        $self->setPath($path);

        return $self;
    }

    /**
     * See {@see ServerCookie::$sameSite} for more information.
     *
     * @return SameSiteInterface|null
     */
    public function getSameSite(): ?SameSiteInterface
    {
        return $this->sameSite;
    }

    /**
     * Updates the "SameSite" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withSameSite()} method.
     *
     * @param SameSiteInterface|null $sameSite
     *
     * @return void
     */
    public function setSameSite(SameSiteInterface|string|\Stringable|null $sameSite): void
    {
        if ($sameSite === '') {
            $sameSite = null;
        }

        if ($sameSite instanceof \Stringable || \is_string($sameSite)) {
            $sameSite = SameSite::parse($sameSite);
        }

        $this->sameSite = $sameSite;
    }

    /**
     * {@inheritDoc}
     */
    public function withSameSite(SameSiteInterface $sameSite): self
    {
        $self = clone $this;
        $self->setSameSite($sameSite);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutSameSite(): ServerCookieInterface
    {
        $self = clone $this;
        $self->setSameSite(null);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Updates the "Secure" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withSecure()} method.
     *
     * @param bool $secure
     *
     * @return void
     */
    public function setSecure(bool $secure): void
    {
        $this->secure = $secure;
    }

    /**
     * {@inheritDoc}
     */
    public function withSecure(bool $secure = true): self
    {
        $self = clone $this;
        $self->setSecure($secure);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * Updates the "HttpOnly" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withHttpOnly()} method.
     *
     * @param bool $httpOnly
     *
     * @return void
     */
    public function setHttpOnly(bool $httpOnly): void
    {
        $this->httpOnly = $httpOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function withHttpOnly(bool $httpOnly = true): self
    {
        $self = clone $this;
        $self->setHttpOnly($httpOnly);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function isPartitioned(): bool
    {
        return $this->partitioned;
    }

    /**
     * Updates the "Partitioned" attribute in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withPartitioned()} method.
     *
     * @param bool $partitioned
     *
     * @return void
     */
    public function setPartitioned(bool $partitioned): void
    {
        $this->partitioned = $partitioned;
    }

    /**
     * {@inheritDoc}
     */
    public function withPartitioned(bool $partitioned = true): self
    {
        $self = clone $this;
        $self->withPartitioned($partitioned);

        return $self;
    }

    /**
     * Returns string representation of the Cookie header value.
     *
     * Session cookies are removed when the client shuts down. Cookies are
     * session cookies if they do not specify the "Expires" or "Max-Age"
     * attribute.
     *
     * ```
     *  echo new Cookie('sessionId', 'afes7a8');
     *  >> "Set-Cookie: sessionId=38afes7a8"
     * ```
     *
     * Permanent cookies are removed at a specific date ("Expires") or after a
     * specific length of time ("Max-Age") and not when the client is closed.
     *
     * ```
     *  echo (new Cookie('id', 'a3fWa'))->withExpires(new \DateTime());
     *  >> "Set-Cookie: id=a3fWa; Expires=Wed, 21 Jun 2042 07:28:00 GMT"
     *
     *  echo (new Cookie('id', 'a3fWa'))->withMaxAge(2592000);
     *  >> "Set-Cookie: id=a3fWa; Max-Age=2592000"
     * ```
     *
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return Collection::build([
            new Attribute($this->getName(), $this->getValue()),
            Attribute::createOrNull('Expires', $this->expires
                ?->format(\DateTimeInterface::COOKIE)),
            Attribute::createOrNull('Max-Age', $this->maxAge),
            Attribute::createOrNull('Domain', $this->getDomain()),
            Attribute::createOrNull('Path', $this->getPath()),
            Attribute::createOrNull('SameSite', $this->sameSite?->getValue()),
            Flag::createIf('Secure', $this->secure),
            Flag::createIf('HttpOnly', $this->httpOnly),
            Flag::createIf('Partitioned', $this->partitioned),
            ...$this->attributes,
        ]);
    }
}
