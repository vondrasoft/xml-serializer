<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use XmlSerializer\Exception\MissingAttributeNameException;
use XmlSerializer\Factory\ElementCollectionFactory;

class ElementCollectionFactoryTest extends TestCase
{
    protected ElementCollectionFactory $factory;

    public function testCreateCollectionFromArray(): void
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
                        'attributes' => [
                            [
                                'name' => 'xxl',
                                'value' => 'test_xxl',
                            ],
                        ],
                        'value' => 'serializer',
                    ],
                ],
            ],
        ];

        $collection = $this->factory->createCollectionFromArray($inputData);
        $this->assertSame(1, \count($collection));
        $this->assertSame($inputData, $collection->toArray());

        $inputData = [
            [
                'name' => 'element',
                'attributes' => [
                    [
                        'name' => '',
                        'value' => 'value1',
                    ],
                    [
                        'name' => '',
                        'value' => 'value2',
                    ],
                ],
            ],
        ];

        $this->expectException(MissingAttributeNameException::class);
        $this->factory->createCollectionFromArray($inputData);
    }

    public function testCreateCollectionFromJson(): void
    {
        $inputJson = '[
              {
                "name": "element",
                "attributes": [
                  {
                    "name": "param1",
                    "value": "value1"
                  },
                  {
                    "name": "param2",
                    "value": "value2"
                  }
                ],
                "value": [
                  {
                    "name": "element1",
                    "attributes": [
                      {
                        "name": "xxl",
                        "value": "test_xxl"
                      }
                    ],
                    "value": "serializer"
                  }
                ]
              }
            ]';
        
        $this->assertJson($inputJson);
        $collection = $this->factory->createCollectionFromJson($inputJson);
        $this->assertSame(1, \count($collection));
        $this->assertJsonStringEqualsJsonString($inputJson, \json_encode($collection->toArray()));
        
        $this->expectException(\JsonException::class);
        $this->factory->createCollectionFromJson('-');
    }

    protected function setUp(): void
    {
        $this->factory = new ElementCollectionFactory();

        parent::setUp();
    }
}
