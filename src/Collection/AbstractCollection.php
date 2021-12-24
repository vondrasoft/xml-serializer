<?php
declare(strict_types=1);

namespace XmlSerializer\Collection;

use XmlSerializer\Model\AbstractItem;

abstract class AbstractCollection implements CollectionInterface, \Countable
{
    protected array $items = [];

    public function getItems(): array
    {
        return $this->items;
    }
    
    public function toArray(): array
    {
        $data = [];

        /** @var AbstractItem $item */
        foreach ($this->items as $item) {
            $data[] = $item->toArray();
        }

        return $data;
    }

    public function count(): int
    {
        return \count($this->items);
    }
}
