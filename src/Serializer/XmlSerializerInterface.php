<?php
declare(strict_types=1);

namespace XmlSerializer\Serializer;

use XmlSerializer\Collection\ElementCollection;

interface XmlSerializerInterface
{
    public function serialize(ElementCollection $collection): string;

    public function deserialize(string $xml): ElementCollection;
}
