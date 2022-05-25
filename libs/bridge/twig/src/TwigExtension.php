<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Bridge\Twig;

use Helix\Boot\Attribute\Register;
use Helix\Foundation\Path;
use Helix\View\RegistrarInterface;
use Twig\Loader\FilesystemLoader;

final class TwigExtension
{
    #[Register]
    public function loadRenderer(RegistrarInterface $views, Path $path): void
    {
        $views->register('twig', new TwigEngine(
            new FilesystemLoader($path->views),
        ));
    }
}
