<?php
declare(strict_types=1);

namespace XmlSerializer\Model;

use XmlSerializer\Collection\AttributeCollection;
use XmlSerializer\Collection\ElementCollection;

class Element extends AbstractItem
{
    protected AttributeCollection $attributes;
    protected ElementCollection $elements;
    protected ?string $value = null;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->elements = new ElementCollection();
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): Element
    {
        $this->value = $value;
        return $this;
    }

    public function toArray(): array
    {
        $data = ['name' => $this->name];
        
        if (isset($this->attributes) && \count($this->attributes)) {
            $data['attributes'] = $this->attributes->toArray();
        }

        $data['value'] = isset($this->elements) && \count($this->elements) ? $this->elements->toArray() : $this->value;

        return $data;
    }
}
