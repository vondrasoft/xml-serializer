<?php
declare(strict_types=1);

namespace XmlSerializer;

use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Serializer\XmlSerializer;

class XmlSerializerManager
{
    protected XmlSerializer $serializer;
    protected ElementCollectionFactory $collectionFactory;

    public function __construct(XmlSerializer $serializer, ElementCollectionFactory $collectionFactory)
    {
        $this->serializer = $serializer;
        $this->collectionFactory = $collectionFactory;
    }

    public function getXmlFromArray(array $data): string
    {
        return $this->serializer->serialize($this->collectionFactory->createCollectionFromArray($data));
    }
}
