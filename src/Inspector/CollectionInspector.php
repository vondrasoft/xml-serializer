<?php
declare(strict_types=1);

namespace XmlSerializer\Inspector;

use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Exception\CollectionException;
use XmlSerializer\Model\Element;

class CollectionInspector implements CollectionInspectorInterface
{
    protected ?ElementCollection $collection;

    public function __construct(?ElementCollection $collection = null)
    {
        $this->collection = $collection;
    }
    
    public function setCollection(ElementCollection $collection): self
    {
        $this->collection = $collection;
        return $this;
    }
    
    public function getCollection(): ?ElementCollection
    {
        return $this->collection;
    }

    public function getElementByPath(string $path): ?Element
    {
        if (\is_null($this->collection)) {
            throw new CollectionException('You cannot call this method, because the collection is not set.');
        }

        $pathParts = \explode('.', $path);
        $element = $this->collection->findItemByName(\current($pathParts));
        \array_shift($pathParts);

        foreach ($pathParts as $part) {
            if (\is_null($element)) {
                break;
            }

            $element = $element->getElements()->findItemByName($part);
        }

        if (!\is_null($element)) {
            return $element;
        }
        
        return null;
    }
}
