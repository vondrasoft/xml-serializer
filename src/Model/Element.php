<?php
declare(strict_types=1);

namespace XmlSerializer\Model;

use XmlSerializer\Collection\AttributeCollection;
use XmlSerializer\Collection\ElementCollection;

class Element extends AbstractItem
{
    protected AttributeCollection $attributes;
    protected ElementCollection $elements;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getAttributes(): AttributeCollection
    {
        return $this->attributes;
    }

    public function setAttributes(AttributeCollection $attributes): Element
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getElements(): ElementCollection
    {
        return $this->elements;
    }

    public function setElements(ElementCollection $elements): Element
    {
        $this->elements = $elements;
        return $this;
    }
}
