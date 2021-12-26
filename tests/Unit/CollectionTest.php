<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use XmlSerializer\Collection\AttributeCollection;
use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Model\Attribute;
use XmlSerializer\Model\Element;

class CollectionTest extends TestCase
{
    public function testAttributeCollection(): void
    {
        $collection = new AttributeCollection();
        $this->assertSame(0, \count($collection));
        $attribute = new Attribute('test', 'code');
        $collection->addAttribute($attribute);
        $this->assertSame(1, \count($collection));
        $this->assertNull($collection->findItemByName('testOne'));
        $this->assertSame('test', $collection->findItemByName('test')->getName());
        $this->assertSame('code', $collection->findItemByName('test')->getValue());
        $attribute->setValue('newValue');
        $this->assertSame('newValue', $collection->findItemByName('test')->getValue());
        $attribute->setName('newTest');
        $this->assertSame('newValue', $collection->findItemByName('newTest')->getValue());
    }

    public function testElementCollection(): void
    {
        $attributes = (new AttributeCollection())->addAttribute(new Attribute('test', 'code'));
        $collection = new ElementCollection();
        $this->assertSame(0, \count($collection));
        $collection->addElement((new Element('elementOne'))->setAttributes($attributes));
        $this->assertSame(1, \count($collection));
        $this->assertNull($collection->findItemByName('elementTwo'));
        $this->assertSame('elementOne', $collection->findItemByName('elementOne')->getName());
        $attributes = $collection->findItemByName('elementOne')->getAttributes();
        $this->assertSame(1, \count($attributes));
        $this->assertSame('test', $attributes->findItemByName('test')->getName());
        $this->assertSame('code', $attributes->findItemByName('test')->getValue());
    }
}
