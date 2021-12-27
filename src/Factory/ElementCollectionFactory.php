<?php
declare(strict_types=1);

namespace XmlSerializer\Factory;

use XmlSerializer\Collection\AttributeCollection;
use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Exception\MissingAttributeNameException;
use XmlSerializer\Model\Attribute;
use XmlSerializer\Model\Element;

class ElementCollectionFactory
{
    public function createCollectionFromArray(array $data): ElementCollection
    {
        $collection = new ElementCollection();

        foreach ($data as $item) {
            if (\is_array($item)) {
                $collection->addElement($this->createElement($item['name'], $item));
            }
        }

        return $collection;
    }

    public function createCollectionFromJson(string $json): ElementCollection
    {
        $data = \json_decode($json, true);

        if ($data) {
            return $this->createCollectionFromArray((array) $data);
        }

        throw new \JsonException('Invalid json string.');
    }
    
    protected function createElement(string $name, array $data): Element
    {
        $attributeCollection = new AttributeCollection();

        if (isset($data['attributes']) && \is_array($data['attributes'])) {
            foreach ($data['attributes'] as $item => $attribute) {
                if (empty($attribute['name'])) {
                    throw new MissingAttributeNameException(
                        \sprintf('Attribute name of id %d on element %s not exist, or not set.', $item, $name)
                    );
                }

                $attributeCollection->addAttribute(new Attribute($attribute['name'], $attribute['value'] ?? ''));
            }
        }

        $element = new Element($name);
        $element->setAttributes($attributeCollection);

        if (isset($data['value']) && \is_array($data['value'])) {
            $elementCollection = new ElementCollection();

            foreach ($data['value'] as $subElement) {
                $elementCollection->addElement($this->createElement($subElement['name'], $subElement));
            }

            $element->setElements($elementCollection);
        } else {
            $element->setValue($data['value'] ?? null);
        }

        if (!empty($data['cdata'])) {
            $element->setHasCdataValue(true);
        }

        return $element;
    }
}
