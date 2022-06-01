<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Debug\Command;

use Helix\Boot\Attribute\Execution;
use Helix\Boot\Attribute\ServiceDefinition;
use Helix\Boot\ExtensionInterface;
use Helix\Boot\RepositoryInterface;
use Helix\Foundation\Console\Command;
use Helix\Foundation\Console\Helper\TreeItem;
use SebastianBergmann\Environment\Console;
use Symfony\Component\Console\Output\OutputInterface;

final class DebugExtensionsCommand extends Command
{
    protected string $name = 'debug:extensions';
    protected string $description = 'Displays a list of registered extensions';

    /**
     * @param RepositoryInterface $extensions
     * @return void
     * @throws \ReflectionException
     */
    public function invoke(RepositoryInterface $extensions): void
    {
        foreach ($extensions->getExtensions() as $extension) {
            $this->item($this->getExtNameString($extension), $this->getExtVersionString($extension));

            if ($description = $this->getExtDescriptionString($extension)) {
                $this->description($description);
            }

            $count = \iterator_count($extension->getMethodMetadata());
            $item = 0;

            /** @var \ReflectionMethod $method */
            foreach ($extension->getMethodMetadata() as $method => $meta) {
                $location = ++$item < $count ? TreeItem\Location::MIDDLE : TreeItem\Location::LAST;

                $tree = new TreeItem(
                    title: $this->getMetaNameString($meta),
                    suffix: $this->getMetaMethodString($method),
                    location: $location,
                );

                if ($meta instanceof ServiceDefinition) {
                    foreach ([$meta->id, ...$meta->aliases] as $alias) {
                        $tree->addChild((new TreeItem($alias))->withoutSeparator());
                    }
                }

                $tree->render($this->output);
            }

            $this->eol();
        }
    }

    /**
     * @param object $meta
     * @return non-empty-string
     */
    private function getMetaNameString(object $meta): string
    {
        $name = (new \ReflectionClass($meta))->getShortName();

        $color = $meta instanceof Execution ? 'blue' : 'yellow';

        return "<fg=$color>#[$name]</>";
    }

    /**
     * @param \ReflectionMethod $method
     * @return string
     */
    private function getMetaMethodString(\ReflectionMethod $method): string
    {
        return \vsprintf('<fg=gray>%s</><comment>%s</comment><fg=gray>()</>', [
            $method->isStatic() ? '::' : '->',
            $method->getName(),
        ]);
    }

    /**
     * @param ExtensionInterface $ext
     * @return non-empty-string
     */
    private function getExtVersionString(ExtensionInterface $ext): string
    {
        return '<comment>' . $ext->getVersion() . '</comment>';
    }

    /**
     * @param ExtensionInterface $ext
     * @return string
     */
    private function getExtDescriptionString(ExtensionInterface $ext): string
    {
        return $ext->getDescription();
    }

    /**
     * @param ExtensionInterface $ext
     * @return string
     */
    private function getExtNameString(ExtensionInterface $ext): string
    {
        return \sprintf('<fg=green>%s</>', $ext->getName());
    }
}
