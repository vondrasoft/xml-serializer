<?php
declare(strict_types=1);

namespace XmlSerializer\Collection;

abstract class AbstractCollection implements CollectionInterface
{
    protected array $items = [];

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsCount(): int
    {
        return \count($this->items);
    }
}
