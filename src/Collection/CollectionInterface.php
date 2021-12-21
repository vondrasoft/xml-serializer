<?php
declare(strict_types=1);

namespace XmlSerializer\Collection;

use XmlSerializer\Arrayable;
use XmlSerializer\Model\AbstractItem;

interface CollectionInterface extends Arrayable
{
    public function getItems(): array;

    public function getItemsCount(): int;

    public function findItemByName(string $name): ?AbstractItem;
}
