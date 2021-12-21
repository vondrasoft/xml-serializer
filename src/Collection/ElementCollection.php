<?php
declare(strict_types=1);

namespace XmlSerializer\Collection;

use XmlSerializer\Model\Element;

class ElementCollection extends AbstractCollection
{
    public function addElement(Element $element): self
    {
        $this->items[] = $element;
        return $this;
    }

    public function findItemByName(string $name): ?Element
    {
        /** @var Element $element */
        foreach ($this->items as $element) {
            if ($element->getName() === $name) {
                return $element;
            }
        }

        return null;
    }
}
