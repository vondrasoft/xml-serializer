<?php
declare(strict_types=1);

namespace XmlSerializer\Inspector;

use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Exception\CollectionException;
use XmlSerializer\Exception\CollectionItemNotExistException;
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

        $pathNames = \explode('.', $path);
        $element = $this->collection->findItemByName(\current($pathNames));
        \array_shift($pathNames);

        foreach ($pathNames as $name) {
            if (\is_null($element)) {
                break;
            }

            \preg_match('/\[(\d+)\]/', $name, $idSelector);

            if (!empty($idSelector[0])) {
                try {
                    $element = $element->getElements()->getItemById(\intval($idSelector[1]));
                } catch (CollectionItemNotExistException) {
                    return null;
                }
            } else {
                $element = $element->getElements()->findItemByName($name);
            }
        }

        if (!\is_null($element)) {
            return $element;
        }
        
        return null;
    }
}
