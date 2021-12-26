<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Inspector\CollectionInspector;
use XmlSerializer\Serializer\XmlSerializer;
use XmlSerializer\XmlSerializerManager;

class XmlSerializerManagerTest extends TestCase
{
    protected XmlSerializer $serializer;
    protected ElementCollectionFactory $factory;
    protected CollectionInspector $collectionInspector;
    protected XmlSerializerManager $manager;

    public function testGetters(): void
    {
        $this->assertSame($this->factory, $this->manager->getCollectionFactory());
        $this->assertSame($this->serializer, $this->manager->getSerializer());
        $this->assertSame($this->collectionInspector, $this->manager->getCollectionInspector());
    }

    public function testGetXmlFromArray(): void
    {
        $inputData = [
            [
                'name' => 'testElement',
                'attributes' => [
                    [
                        'name' => 'param1',
                        'value' => 'value1',
                    ],
                ],
                'value' => 'Hi',
            ],
        ];

        $expectedOutput = '<testElement param1="value1">Hi</testElement>';

        $this->assertSame($expectedOutput, $this->manager->getXmlFromArray($inputData));
    }

    public function testGetArrayFromXml(): void
    {
        $inputXml = '<array uno="presto">This is test.</array>';

        $expectedOutput = [
            [
                'name' => 'array',
                'attributes' => [
                    [
                        'name' => 'uno',
                        'value' => 'presto',
                    ],
                ],
                'value' => 'This is test.',
            ],
        ];

        $this->assertSame($expectedOutput, $this->manager->getArrayFromXml($inputXml));
    }

    public function testGetXmlFromJson(): void
    {
        $inputJson = '[
          {
            "name": "testElement",
            "attributes": [
              {
                "name": "param1",
                "value": "value1"
              }
            ],
            "value": "Hi"
          }
        ]';

        $expectedOutput = '<testElement param1="value1">Hi</testElement>';

        $this->assertSame($expectedOutput, $this->manager->getXmlFromJson($inputJson));
    }

    public function testGetJsonFromXml(): void
    {
        $inputXml = '<array uno="presto">This is test.</array>';

        $expectedOutput = '[
          {
            "name": "array",
            "attributes": [
              {
                "name": "uno",
                "value": "presto"
              }
            ],
            "value": "This is test."
          }
        ]';

        $this->assertJson($expectedOutput);
        $this->assertJsonStringEqualsJsonString($expectedOutput, $this->manager->getJsonFromXml($inputXml));
    }

    protected function setUp(): void
    {
        $this->factory = new ElementCollectionFactory();
        $this->serializer = new XmlSerializer($this->factory);
        $this->collectionInspector = new CollectionInspector();
        $this->manager = new XmlSerializerManager($this->serializer, $this->factory, $this->collectionInspector);

        parent::setUp();
    }
}
