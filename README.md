XmlSerializer v1.0.1
============================

This package serialize xml to collection (arrays, json) and back for your apis.

It is compatible (and tested) with PHP 8.0+.
There is 100% unit test coverage with codesniffer and phpstan at maximum level.


Installation
------------

Add [`vondrasoft/xml-serializer`](https://packagist.org/packages/vondrasoft/xml-serializer)
to your `composer.json` file or:

    composer require vondrasoft/xml-serializer

Usage
------------

Serialize array to xml
-----
```php 
<?php

use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Inspector\CollectionInspector;
use XmlSerializer\Serializer\XmlSerializer;
use XmlSerializer\XmlSerializerManager;

$input = [
    [
        'name' => 'test',
        'value' => [
            [
                'cdata' => true,
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
                ],
            ],
        ],
    ],
];

$manager = new XmlSerializerManager(new XmlSerializer(), new CollectionInspector());

echo $manager->getXmlFromArray($input);
```
   
###### Output
```xml
<test>
  <element param1="value1" param2="value2">
    <![CDATA[<element1 param="10">serializer</element1>]]>
  </element>
</test>
```
    
Deserialize XML to collection
----
```php
<?php

use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Serializer\XmlSerializer;

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

$serializer = new XmlSerializer();

$collection = $serializer->deserialize($inputXml);

// collections implements JsonSerializable interface, so you can transform to json them easily
echo json_encode($collection);
```
    
###### Output
```json
[
  {
    "name": "vehicle",
    "value": [
      {
        "name": "brand",
        "attributes": [
          {
            "name": "code",
            "value": "xx"
          }
        ],
        "value": "Xexe"
      },
      {
        "name": "data",
        "attributes": [
          {
            "name": "code",
            "value": "dataset"
          }
        ],
        "value": [
          {
            "name": "model",
            "attributes": [
              {
                "name": "type",
                "value": "string"
              }
            ],
            "value": "BestModel"
          },
          {
            "name": "risk",
            "value": [
              {
                "name": "optional",
                "value": "Zero"
              },
              {
                "name": "primary",
                "value": "First"
              }
            ]
          }
        ]
      }
    ]
  }
]
```
    

Create collection manually
----
 ```php
<?php

use XmlSerializer\Collection\ElementCollection;
use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Model\Element;
use XmlSerializer\Serializer\XmlSerializer;

$collection = new ElementCollection();

$firstElement = (new Element('firstElement'))->setValue('firstValue');
$secondElement = (new Element('secondElement'))->setValue('secondValue');

$collection
    ->addElement($firstElement)
    ->addElement($secondElement);

$xmlCollection = new ElementCollection();
$rootElement = (new Element('main'))->setElements($collection);
$xmlCollection->addElement($rootElement);

$serializer = new XmlSerializer();

$output = $serializer->serialize($xmlCollection);

echo $output;
```
    
###### Output
```xml
<main>
  <firstElement>firstValue</firstElement>
  <secondElement>secondValue</secondElement>
</main>
```
    
Collection inspector
----
```php
<?php

use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Inspector\CollectionInspector;
use XmlSerializer\Serializer\XmlSerializer;

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

$serializer = new XmlSerializer();

$collection = $serializer->deserialize($inputXml);

$inspector = new CollectionInspector($collection);

// will print "BestModel"
echo $inspector->getElementByPath('vehicle.data.model')->getValue();
```
    
There is problem about elements with same name. So you can specify element by an index.

```php
<?php

use XmlSerializer\Factory\ElementCollectionFactory;
use XmlSerializer\Inspector\CollectionInspector;
use XmlSerializer\Serializer\XmlSerializer;

$inputXml = '
    <notepad>
        <param>first</param>
        <param>second</param>
        <param>
            <note>one</note>
            <note>two</note>
        </param>
    </notepad>
';

$serializer = new XmlSerializer();

$collection = $serializer->deserialize($inputXml);

$inspector = new CollectionInspector($collection);

// will print "first"
echo $inspector->getElementByPath('notepad.param[0]')->getValue();

// will print "second"
echo $inspector->getElementByPath('notepad.param[1]')->getValue();

// will print "two"
echo $inspector->getElementByPath('notepad.param[2].note[1]')->getValue();
```

XmlSerializerManager
----
This is main class from the package, you can inject it to your services by dependency injection and simple use it.

```php
<?php

use XmlSerializer\Inspector\CollectionInspector;
use XmlSerializer\Serializer\XmlSerializer;
use XmlSerializer\XmlSerializerManager;

$inputXml = '
    <notepad>
        <param>first</param>
        <param>second</param>
        <param>
            <note>one</note>
            <note>two</note>
        </param>
    </notepad>
';

$manager = new XmlSerializerManager(new XmlSerializer(), new CollectionInspector());

// you can call serializer methods by getSerializer ...
$collection = $manager->getSerializer()->deserialize($inputXml);

// but you can use it directly on the manager level
$array = $manager->getArrayFromXml($inputXml);

// will print the same array, like calling $collection->toArray();
var_dump($array);

// you can get json from xml and etc.. getXmlFromArray, getXmlFromJson, getArrayFromXml
$json = $manager->getJsonFromXml($inputXml);

//you can use inspector from manager by call
$inspector = $manager->getCollectionInspector();

// you must set the collection if you are using dependency injection without inject collection to constructor
$inspector->setCollection($collection);

$element = $inspector->getElementByPath('....');
```
    
