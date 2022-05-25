<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Http\Extension;

use Helix\Boot\Attribute\Singleton;
use Helix\Contracts\View\FactoryInterface;
use Helix\Foundation\Path;
use Helix\View\Engine\PhpEngine;
use Helix\View\Factory;
use Helix\View\RegistrarInterface;

final class ViewExtension
{
    #[Singleton(as: [Factory::class, RegistrarInterface::class])]
    public function getViewFactory(Path $path): FactoryInterface
    {
        return new Factory([
            'php' => new PhpEngine($path->views),
        ]);
    }
}
