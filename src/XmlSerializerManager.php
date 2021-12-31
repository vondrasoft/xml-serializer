<?php
declare(strict_types=1);

namespace XmlSerializer;

use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Inspector\CollectionInspectorInterface;
use XmlSerializer\Serializer\XmlSerializerInterface;

class XmlSerializerManager
{
    protected XmlSerializerInterface $serializer;
    protected ElementCollectionFactory $collectionFactory;
    protected CollectionInspectorInterface $collectionInspector;

    public function __construct(
        XmlSerializerInterface $serializer,
        CollectionInspectorInterface $collectionInspector,
    ) {
        $this->collectionFactory = new ElementCollectionFactory();
        $this->serializer = $serializer;
        $this->collectionInspector = $collectionInspector;
    }

    public function getXmlFromArray(array $data): string
    {
        return $this->serializer->serialize($this->collectionFactory->createCollectionFromArray($data));
    }

    public function getArrayFromXml(string $xml): array
    {
        return $this->serializer->deserialize($xml)->toArray();
    }

    public function getXmlFromJson(string $json): string
    {
        return $this->serializer->serialize($this->collectionFactory->createCollectionFromJson($json));
    }

    public function getJsonFromXml(string $xml): string
    {
        return \json_encode($this->serializer->deserialize($xml)) ?: '';
    }

    public function getSerializer(): XmlSerializerInterface
    {
        return $this->serializer;
    }

    public function getCollectionFactory(): ElementCollectionFactory
    {
        return $this->collectionFactory;
    }

    public function getCollectionInspector(): CollectionInspectorInterface
    {
        return $this->collectionInspector;
    }
}
