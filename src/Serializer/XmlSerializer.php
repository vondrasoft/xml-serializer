<?php
declare(strict_types=1);

namespace XmlSerializer\Serializer;

use XmlSerializer\Collection\ElementCollection;

class XmlSerializer implements XmlSerializerInterface
{
    public function serialize(ElementCollection $collection): string
    {
        // TODO: Implement serialize() method.
    }

    public function deserialize(string $xml): ElementCollection
    {
        // TODO: Implement deserialize() method.
    }
}
