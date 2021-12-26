<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use XmlSerializer\Exception\CollectionException;
use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Inspector\CollectionInspector;
use XmlSerializer\Serializer\XmlSerializer;

class CollectionInspectorTest extends TestCase
{
    protected XmlSerializer $serializer;
    protected ElementCollectionFactory $factory;

    public function testGetElementByPath(): void
    {
        $inputData = [
            [
                'name' => 'element',
                'attributes' => [
                    [
                        'name' => 'param1',
                        'value' => 'value1',
                    ],
                    [
                        'name' => 'param2',
                        'value' => 'value2',
                    ],
                ],
                'value' => [
                    [
                        'name' => 'element1',
                        'value' => [
                            [
                                'name' => 'elementTom',
                                'attributes' => [
                                    [
                                        'name' => 'Mark',
                                        'value' => 'Tyler',
                                    ],
                                    [
                                        'name' => 'Dominik',
                                        'value' => 'Vondra',
                                    ],
                                ],
                                'value' => [
                                    [
                                        'name' => 'serializerElement',
                                        'value' => 'serializer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        
        $collectionInspector = new CollectionInspector($this->factory->createCollectionFromArray($inputData));
        $element = $collectionInspector->getElementByPath('element.element1.elementTom.serializerElement');
        
        $this->assertSame('serializerElement', $element->getName());
        $this->assertSame('serializer', $element->getValue());

        $element = $collectionInspector->getElementByPath('element.element1.elementTom');
        $this->assertSame('elementTom', $element->getName());
        $this->assertSame(2, \count($element->getAttributes()));
        $this->assertSame('Vondra', $element->getAttributes()->findItemByName('Dominik')->getValue());

        $inputXml = '
            <vehicle>
                <brand code="xx">Xexe</brand>
                <data code="dataset">
                    <model type="string">BestModel</model>
                    <risk>
                        <optional>Zero</optional>
                        <primary>First</primary>
                    </risk>
                </data>
            </vehicle>';

        $collection = $this->serializer->deserialize($inputXml);
        $element = $collectionInspector->setCollection($collection)->getElementByPath('vehicle.brand');
        $this->assertSame($collection, $collectionInspector->getCollection());

        $this->assertSame('Xexe', $element->getValue());
        $this->assertSame(1, \count($element->getAttributes()));
        $attribute = $element->getAttributes()->findItemByName('code');
        $this->assertSame(['code', 'xx'], [$attribute->getName(), $attribute->getValue()]);

        $element = $collectionInspector->getElementByPath('vehicle.data.risk.optional');
        $this->assertSame('Zero', $element->getValue());

        $this->assertNull($collectionInspector->getElementByPath('vehicle.data.risk.secondary'));
        $this->assertNull($collectionInspector->getElementByPath('vehicle.data.riskOne.secondary'));

        $collectionInspector = new CollectionInspector();
        $this->expectException(CollectionException::class);
        $collectionInspector->getElementByPath('element.element1.elementTom.serializerElement');
    }

    protected function setUp(): void
    {
        $this->factory = new ElementCollectionFactory();
        $this->serializer = new XmlSerializer($this->factory);

        parent::setUp();
    }
}
