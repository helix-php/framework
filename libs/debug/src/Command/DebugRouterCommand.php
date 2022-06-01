<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Debug\Command;

use Helix\Contracts\Router\RepositoryInterface;
use Helix\Contracts\Router\RouteInterface;
use Helix\Foundation\Console\Command;
use Helix\Foundation\Console\Helper\TreeItem;
use SebastianBergmann\Environment\Console;

final class DebugRouterCommand extends Command
{
    protected string $name = 'debug:router';
    protected string $description = 'Displays a list of registered routes';

    /**
     * @param RepositoryInterface $routes
     * @return void
     */
    public function invoke(RepositoryInterface $routes): void
    {
        $methodSize = $this->getMaxMethodSize($routes);

        foreach ($routes as $route) {
            $prefix = $this->getRouteMethodString($route, $methodSize);
            $prefix .= $this->getRoutePathString($route);
            $suffix = $this->getRouteHandlerString($route);

            $tree = $this->tree($prefix, $suffix);

            foreach ($route->getParameters() as $parameter => $value) {
                $prefix = "<fg=yellow>$parameter</>";
                $suffix = "<fg=gray>/</><fg=blue>$value</><fg=gray>/</>";

                $tree->addChild(
                    (new TreeItem($prefix . ' <fg=gray>=</> ' . $suffix))
                        ->withoutSeparator()
                );
            }

            $tree->render($this->output);
        }
    }

    /**
     * @param RepositoryInterface $routes
     * @return int
     */
    private function getMaxMethodSize(RepositoryInterface $routes): int
    {
        $size = 0;

        foreach ($routes as $route) {
            $method = $route->getMethod();

            $size = \max($size, \mb_strlen($method->getName()));
        }

        return $size;
    }

    /**
     * @param RouteInterface $route
     * @return non-empty-string
     */
    private function getRouteHandlerString(RouteInterface $route): string
    {
        $handler = $route->getHandler();

        if (\is_string($handler)) {
            return $handler;
        }

        return \get_debug_type($handler);
    }

    /**
     * @param RouteInterface $route
     * @param int $size
     * @return non-empty-string
     */
    private function getRouteMethodString(RouteInterface $route, int $size): string
    {
        $method = $route->getMethod();
        $color = $method->isIdempotent() ? 'green' : 'red';

        return \sprintf("<fg=$color>%-{$size}s</> ", $method->getName());
    }

    /**
     * @param RouteInterface $route
     * @return non-empty-string
     */
    private function getRoutePathString(RouteInterface $route): string
    {
        $path = $route->getPath();
        $path = \preg_replace('/\{.+?}/', '<fg=yellow;options=bold>$0</>', $path);

        return \sprintf('<options=bold>%s</> ', $path);
    }
}
