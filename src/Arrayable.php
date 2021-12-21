<?php
declare(strict_types=1);

namespace XmlSerializer;

interface Arrayable
{
    public function toArray(): array;
}
