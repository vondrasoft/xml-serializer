<?php
declare(strict_types=1);

namespace XmlSerializer\Collection;

use XmlSerializer\Model\Attribute;

class AttributeCollection extends AbstractCollection
{
    public function addAttribute(Attribute $attribute): self
    {
        $this->items[] = $attribute;
        return $this;
    }

    public function findItemByName(string $name): ?Attribute
    {
        /** @var Attribute $attribute */
        foreach ($this->items as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }
}
