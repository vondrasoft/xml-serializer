<?php
declare(strict_types=1);

namespace XmlSerializer\Model;

class Attribute extends AbstractItem
{
    protected string $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): Attribute
    {
        $this->value = $value;
        return $this;
    }
}
