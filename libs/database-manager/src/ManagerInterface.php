<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Database\Manager;

use Helix\Contracts\Database\DriverInterface;
use Helix\Contracts\Manager\ManagerInterface as BaseManagerInterface;

/**
 * @template-extends BaseManagerInterface<DriverInterface>
 */
interface ManagerInterface extends BaseManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public function get(string $name = null): DriverInterface;
}
