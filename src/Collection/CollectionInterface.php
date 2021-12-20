<?php
declare(strict_types=1);

namespace XmlSerializer\Collection;

use XmlSerializer\Model\AbstractItem;

interface CollectionInterface
{
    public function getItems(): array;

    public function getItemsCount(): int;

    public function findItemByName(string $name): ?AbstractItem;
}
