<?php
declare(strict_types=1);

namespace XmlSerializer\Serializer;

use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Model\Attribute;
use XmlSerializer\Model\Element;

class XmlSerializer implements XmlSerializerInterface
{
    public const CDATA_OPEN_CODE = '<![CDATA[';
    public const CDATA_CLOSE_CODE = ']]>';
    public const CDATA_ID_CODE = 'cdata-sanitize';

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
                $elements = $this->serialize($item->getElements());
                $output .= $item->hasCdataValue()
                    ? (self::CDATA_OPEN_CODE . $elements . self::CDATA_CLOSE_CODE)
                    : $elements;
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
        $xml = $this->sanitizeCData($xml);
        $xml = \simplexml_load_string('<root>' . $xml . '</root>');
        $data = $xml ? $this->normalizeXml($xml) : [];

        return $this->factory->createCollectionFromArray($data);
    }

    protected function sanitizeCData(string $xml): string
    {
        $xml = \preg_replace('/(\v|\s)+/', ' ', $xml);

        while ($cDataPosition = \strpos((string) $xml, self::CDATA_OPEN_CODE)) {
            $xml = \substr_replace((string) $xml, ' ' . self::CDATA_ID_CODE . '="true"', $cDataPosition - 1, 0);
            $search = '/'.\preg_quote(self::CDATA_OPEN_CODE, '/').'/';
            $xml = \preg_replace($search, '', $xml, 1);
        }

        return \str_replace(self::CDATA_CLOSE_CODE, '', (string) $xml);
    }
    
    protected function normalizeXml(\SimpleXMLElement $node): array
    {
        $elements = [];

        foreach ($node as $element) {
            $elementData = ['name' => $element->getName()];

            $attributes = (array) $element->attributes();

            if (\count($attributes)) {
                foreach ($attributes['@attributes'] as $key => $value) {
                    if ($key === self::CDATA_ID_CODE) {
                        $elementData['cdata'] = true;
                    } else {
                        $elementData['attributes'][] = [
                            'name' => $key,
                            'value' => (string)$value,
                        ];
                    }
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
