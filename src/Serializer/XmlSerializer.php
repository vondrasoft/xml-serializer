<?php
declare(strict_types=1);

namespace XmlSerializer\Serializer;

use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Model\Attribute;
use XmlSerializer\Model\Element;

class XmlSerializer implements XmlSerializerInterface
{
    protected ElementCollectionFactory $factory;

    public function __construct(ElementCollectionFactory $factory)
    {
        $this->factory = $factory;
    }

    public function serialize(ElementCollection $collection): string
    {
        $output = '';

        /** @var Element $item */
        foreach ($collection->getItems() as $item) {
            $isEmpty = \is_null($item->getValue()) && !\count($item->getElements());
            $output .= '<' . $item->getName();

            if (\count($item->getAttributes())) {
                /** @var Attribute $attribute */
                foreach ($item->getAttributes()->getItems() as $attribute) {
                    $output .= ' ' . $attribute->getName() . '="' . $attribute->getValue() . '"';
                }
            }

            $output .= $isEmpty ? '/>' : '>';

            if (\is_null($item->getValue()) && \count($item->getElements())) {
                $output .= $this->serialize($item->getElements());
            } else {
                $output .= $item->getValue();
            }

            if (!$isEmpty) {
                $output .= '</' . $item->getName() . '>';
            }
        }

        return $output;
    }

    public function deserialize(string $xml): ElementCollection
    {
        $xml = \simplexml_load_string('<root>' . $xml . '</root>');
        $data = $xml ? $this->normalizeXml($xml) : [];

        return $this->factory->createCollectionFromArray($data);
    }

    /**
     * @throws \ReflectionException
     */
    protected function normalizeXml(\SimpleXMLElement $node): array
    {
        $elements = [];

        foreach ($node as $element) {
            $elementData = ['name' => $element->getName()];

            $attributes = (array) $element->attributes();

            if (\count($attributes)) {
                foreach ($attributes['@attributes'] as $key => $value) {
                    $elementData['attributes'][] = [
                        'name' => $key,
                        'value' => (string) $value,
                    ];
                }
            }

            $elementData['value'] = $this->normalizeXml($element);

            if (!\count($elementData['value'])) {
                $elementValue = (string) $element;
                $elementData['value'] = !empty($elementValue) ? $elementValue : null;
            }

            $elements[] = $elementData;
        }

        return $elements;
    }
}
