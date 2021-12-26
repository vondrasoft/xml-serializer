<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Serializer\XmlSerializer;

class XmlSerializerTest extends TestCase
{
    protected ElementCollectionFactory $factory;
    protected XmlSerializer $serializer;

    public function testSerialize(): void
    {
        $input = [
            [
                'name' => 'test',
                'value' => [
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
                                'attributes' => [
                                    [
                                        'name' => 'param',
                                        'value' => '10',
                                    ],
                                ],
                                'value' => 'serializer',
                            ],
                            [
                                'name' => 'empty',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $expectedOutput = '<test><element param1="value1" param2="value2"><element1 param="10">serializer</element1><empty/></element></test>';

        $collection = $this->factory->createCollectionFromArray($input);
        $serializedXml = $this->serializer->serialize($collection);
        $this->assertSame($expectedOutput, $serializedXml);
        $deserializedCollection = $this->serializer->deserialize($serializedXml);
        $this->assertSame($collection->toArray(), $deserializedCollection->toArray());
    }

    public function testDeserialize(): void
    {
        $input = '<main><address><house number="2" onumber="3">TestHouse</house><parameter type="int">value</parameter></address></main>';

        $expectedOutput = [
            [
                'name' => 'main',
                'value' => [
                    [
                        'name' => 'address',
                        'value' => [
                            [
                                'name' => 'house',
                                'attributes' => [
                                    [
                                        'name' => 'number',
                                        'value' => '2',
                                    ],
                                    [
                                        'name' => 'onumber',
                                        'value' => '3',
                                    ],
                                ],
                                'value' => 'TestHouse',
                            ],
                            [
                                'name' => 'parameter',
                                'attributes' => [
                                    [
                                        'name' => 'type',
                                        'value' => 'int',
                                    ],
                                ],
                                'value' => 'value',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->assertSame($expectedOutput, $this->serializer->deserialize($input)->toArray());
    }

    protected function setUp(): void
    {
        $this->factory = new ElementCollectionFactory();
        $this->serializer = new XmlSerializer($this->factory);

        parent::setUp();
    }
}
