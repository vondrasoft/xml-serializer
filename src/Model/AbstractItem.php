<?php
declare(strict_types=1);

namespace XmlSerializer\Model;

use XmlSerializer\Arrayable;

abstract class AbstractItem implements Arrayable
{
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
