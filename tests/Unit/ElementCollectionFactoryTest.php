<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use XmlSerializer\Factory\ElementCollectionFactory;

class ElementCollectionFactoryTest extends TestCase
{
    protected ElementCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new ElementCollectionFactory();
        parent::setUp();
    }

    public function testCreateCollectionFromArray(): void
    {
        $inputData = [
            [
                'name' => 'element',
                'attributes' => [
                    [
                        'name' => 'param1',
                        'value' => 'value1'
                    ],
                    [
                        'name' => 'param2',
                        'value' => 'value2'
                    ]
                ],
                'value' => [
                    [
                        'name' => 'element1',
                        'attributes' => [
                            [
                                'name' => 'xxl',
                                'value' => 'test_xxl'
                            ]
                        ],
                        'value' => 'serializer'
                    ]
                ]
            ]
        ];

        $collection = $this->factory->createCollectionFromArray($inputData);
        $this->assertSame(1, \count($collection));
        $this->assertSame($inputData, $collection->toArray());
    }
}
