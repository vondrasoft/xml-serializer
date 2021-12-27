<?php
declare(strict_types=1);

namespace XmlSerializer\Collection;

use XmlSerializer\Exception\CollectionItemNotExistException;
use XmlSerializer\Model\AbstractItem;

abstract class AbstractCollection implements CollectionInterface, \Countable, \JsonSerializable
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function count(): int
    {
        return \count($this->items);
    }
    
    public function getItemById(int $id): AbstractItem
    {
        if (!isset($this->items[$id]) || !$this->items[$id] instanceof AbstractItem) {
            throw new CollectionItemNotExistException(\sprintf('Item with id %d does not exist.', $id));
        }
        
        return $this->items[$id];
    }
}
