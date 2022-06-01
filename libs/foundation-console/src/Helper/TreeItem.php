<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Console\Helper;

use Helix\Foundation\Console\Helper\TreeItem\Location;
use Symfony\Component\Console\Output\OutputInterface;

class TreeItem extends Item
{
    /**
     * @var array<TreeItem>
     */
    private array $children = [];

    /**
     * @var string
     */
    private string $firstItemChar = '┌┄';

    /**
     * @var string
     */
    private string $middleItemChar = '├┄';

    /**
     * @var string
     */
    private string $lastItemChar = '└┄';

    /**
     * @var string
     */
    private string $childItemChar = '┆';

    /**
     * @param string $title
     * @param Location|null $location
     * @param string $suffix
     */
    public function __construct(
        string $title,
        string $suffix = '',
        private ?Location $location = null,
    ) {
        parent::__construct($title, $suffix);
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    /**
     * @param Location $location
     * @return $this
     */
    public function withLocation(Location $location): self
    {
        $self = clone $this;
        $self->setLocation($location);

        return $self;
    }

    /**
     * @return iterable<TreeItem>
     */
    public function getChildren(): iterable
    {
        return $this->children;
    }

    /**
     * @param iterable<TreeItem> $children
     * @return void
     */
    public function setChildren(iterable $children): void
    {
        $this->children = [];
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * @param iterable<TreeItem> $children
     * @return $this
     */
    public function withChildren(iterable $children): self
    {
        $self = clone $this;
        $self->setChildren($children);

        return $self;
    }

    /**
     * @param TreeItem $item
     * @return void
     */
    public function addChild(TreeItem $item): void
    {
        $this->children[] = $item;
    }

    /**
     * @param TreeItem $item
     * @return $this
     */
    public function withAddedChild(TreeItem $item): self
    {
        $self = clone $this;
        $self->addChild($item);

        return $self;
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    public function render(OutputInterface $output): void
    {
        $output->writeln((string)$this);
    }

    /**
     * @return string
     */
    protected function getFormattedListPrefix(): string
    {
        if ($this->location === null) {
            return '';
        }

        $prefix = match ($this->location) {
            Location::FIRST => $this->firstItemChar,
            Location::MIDDLE => $this->middleItemChar,
            Location::LAST => $this->lastItemChar
        };

        return ' <fg=gray>' . $prefix . '</> ';
    }

    /**
     * @return non-empty-string
     */
    protected function getFormattedChildPrefix(): string
    {
        return match ($this->location) {
            Location::LAST, null => '   ',
            default => ' <fg=gray>' . $this->childItemChar . '</> ',
        };
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result = [$this->getFormattedListPrefix() . parent::__toString()];

        $last = \count($this->children) - 1;

        foreach ($this->children as $i => $child) {
            $location = $i < $last ? Location::MIDDLE : Location::LAST;

            $result[] = $this->getFormattedChildPrefix()
                . $child->withLocation($location);
        }

        return \implode("\n", $result);
    }
}
