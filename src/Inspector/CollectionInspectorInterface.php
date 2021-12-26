<?php
declare(strict_types=1);

namespace XmlSerializer\Inspector;

use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Model\Element;

interface CollectionInspectorInterface
{
    public function setCollection(ElementCollection $collection): CollectionInspectorInterface;

    public function getCollection(): ?ElementCollection;

    public function getElementByPath(string $path): ?Element;
}
