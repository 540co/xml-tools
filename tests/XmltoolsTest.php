<?php
use FiveFortyCo\Xmltools;

class XmltoolsTest extends PHPUnit_Framework_TestCase
{
    public function testXmlArrays() {
      $input = [
        '//orders' => [
          'name' => '//orders',
          'relationships' => [
            [
              'element' => 'productsOrdered'
            ],
            [
              'element' => 'customerAddresses'
            ]
          ]
        ]
      ];

      $output = Xmltools::getXmlArrays($input);

      $this->assertEquals($output, [
        '//orders/productsOrdered',
        '//orders/customerAddresses'
      ]);
    }

    /**
     * Test to verify the basics of getXsdDetails are working
     */
    public function testXsdDetails()
    {
        $filename = dirname(__FILE__) . '/data/purchase-order.xsd';
        $xsdDetails = Xmltools::getXsdDetails($filename);

        //Verify it created item for PurchaseOrder element in schema
        $this->assertArrayHasKey('//PurchaseOrder', $xsdDetails);
        $this->assertEquals($xsdDetails['//PurchaseOrder']['name'], '//PurchaseOrder');

        //Verify that it added child elements/attributes of PurchaseOrder
        $this->assertArrayHasKey('columns', $xsdDetails['//PurchaseOrder']);
        $this->assertTrue(is_array($xsdDetails['//PurchaseOrder']['columns']));

        $index = 0;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['name'], '@OrderDate');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['schemaType'], 'date');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['sourceNodeType'], 'attribute');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['name'], 'BillTo/name');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['name'], 'BillTo/street');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['name'], 'BillTo/city');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['name'], 'BillTo/state');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['name'], 'BillTo/zip');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['schemaType'], 'integer');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['name'], 'BillTo/@country');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['schemaType'], 'NMTOKEN');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['columns'][$index]['sourceNodeType'], 'attribute');

        //Verify it created relationships for ShipTo as hasMany
        $this->assertArrayHasKey('relationships', $xsdDetails['//PurchaseOrder']);
        $this->assertTrue(is_array($xsdDetails['//PurchaseOrder']['relationships']));

        $index = 0;
        $this->assertEquals($xsdDetails['//PurchaseOrder']['relationships'][$index]['element'], 'ShipTo');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['relationships'][$index]['type'], 'hasMany');
        $this->assertEquals($xsdDetails['//PurchaseOrder']['relationships'][$index]['table'], '//PurchaseOrder/ShipTo');

        //Verify ShipTo element was processed
        $this->assertArrayHasKey('//PurchaseOrder/ShipTo', $xsdDetails);
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['name'], '//PurchaseOrder/ShipTo');

        //Verify that it added child elements/attributes of PurchaseOrder/ShipTo
        $this->assertArrayHasKey('columns', $xsdDetails['//PurchaseOrder/ShipTo']);
        $this->assertTrue(is_array($xsdDetails['//PurchaseOrder/ShipTo']['columns']));

        $index = 0;
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['name'], 'name');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['name'], 'street');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['name'], 'city');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['name'], 'state');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['schemaType'], 'string');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['name'], 'zip');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['schemaType'], 'integer');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['sourceNodeType'], 'tag');

        $index++;
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['name'], '@country');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['annotation'], '');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['schemaType'], 'NMTOKEN');
        $this->assertEquals($xsdDetails['//PurchaseOrder/ShipTo']['columns'][$index]['sourceNodeType'], 'attribute');
    }

    public function testRelationshipCreationMaxUnbounded() {
      $filename = dirname(__FILE__) . '/data/relationship-creation-max-unbounded.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      $this->assertArrayHasKey('relationships', $xsdDetails['//PurchaseOrder']);
      $this->assertTrue(is_array($xsdDetails['//PurchaseOrder']['relationships']));

      $index = 0;
      $this->assertEquals($xsdDetails['//PurchaseOrder']['relationships'][$index]['element'], 'ShipTo');
      $this->assertEquals($xsdDetails['//PurchaseOrder']['relationships'][$index]['type'], 'hasMany');
      $this->assertEquals($xsdDetails['//PurchaseOrder']['relationships'][$index]['table'], '//PurchaseOrder/ShipTo');
    }

    public function testRelationshipNotCreatedMax1() {
      $filename = dirname(__FILE__) . '/data/relationship-creation-max1.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      $this->assertArrayHasKey('relationships', $xsdDetails['//PurchaseOrder']);
      $this->assertTrue(is_array($xsdDetails['//PurchaseOrder']['relationships']));
      $this->assertEquals(count($xsdDetails['//PurchaseOrder']['relationships']), 0);
    }

    public function testMultipleRootElements() {
      $filename = dirname(__FILE__) . '/data/element-ref.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      //Should have book element
      $this->assertArrayHasKey('//book', $xsdDetails);

      //Should also have bookReview element
      $this->assertArrayHasKey('//bookReview', $xsdDetails);
    }

    public function testElementGroup() {
      $filename = dirname(__FILE__) . '/data/element-group.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      $this->assertArrayHasKey('//Person', $xsdDetails);

      $index = 0;
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['name'], 'first');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['name'], 'middle');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['name'], 'last');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['name'], '@age');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//Person']['columns'][$index]['sourceNodeType'], 'attribute');
    }

    public function testAttributeGroup() {
      $filename = dirname(__FILE__) . '/data/attribute-group.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      $this->assertArrayHasKey('//Date', $xsdDetails);

      $index = 0;
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['name'], '@month');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['sourceNodeType'], 'attribute');

      $index++;
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['name'], '@day');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['sourceNodeType'], 'attribute');

      $index++;
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['name'], '@year');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//Date']['columns'][$index]['sourceNodeType'], 'attribute');
    }

    public function testElementRef() {
      $filename = dirname(__FILE__) . '/data/element-ref.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      //Should have book element
      $this->assertArrayHasKey('//book', $xsdDetails);
      $index = 0;
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['name'], 'title');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['name'], 'publishYear');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['name'], 'author');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//book']['columns'][$index]['sourceNodeType'], 'tag');

      //Should also have bookReview element with book sub element which is ref to root book element
      $this->assertArrayHasKey('//bookReview', $xsdDetails);

      $index = 2;
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['name'], 'book/title');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['name'], 'book/publishYear');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['name'], 'book/author');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//bookReview']['columns'][$index]['sourceNodeType'], 'tag');
    }

    public function testArrayElementRef() {
      $filename = dirname(__FILE__) . '/data/element-array-ref.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      $this->assertArrayHasKey('//peopleList', $xsdDetails);

      $index = 0;
      $this->assertEquals($xsdDetails['//peopleList']['relationships'][$index]['element'], 'person');
      $this->assertEquals($xsdDetails['//peopleList']['relationships'][$index]['type'], 'hasMany');
      $this->assertEquals($xsdDetails['//peopleList']['relationships'][$index]['table'], '//peopleList/person');

      $this->assertArrayHasKey('//peopleList/person', $xsdDetails);

      $index = 0;
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['name'], 'name');
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['name'], 'age');
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//peopleList/person']['columns'][$index]['sourceNodeType'], 'tag');
    }

    public function testAttributeRef() {
      $filename = dirname(__FILE__) . '/data/attribute-ref.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      $this->assertArrayHasKey('//someElement', $xsdDetails);

      $index = 0;
      $this->assertEquals($xsdDetails['//someElement']['columns'][$index]['name'], '@code');
      $this->assertEquals($xsdDetails['//someElement']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//someElement']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//someElement']['columns'][$index]['sourceNodeType'], 'attribute');
    }

    public function testComplexTypeExtension() {
      $filename = dirname(__FILE__) . '/data/complex-type-extension.xsd';
      $xsdDetails = Xmltools::getXsdDetails($filename);

      $this->assertArrayHasKey('//car', $xsdDetails);

      $index = 0;
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['name'], 'numberOfDoors');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['name'], 'manufacturer');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['name'], 'model');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['schemaType'], 'string');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['sourceNodeType'], 'tag');

      $index++;
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['name'], 'year');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['annotation'], '');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['schemaType'], 'integer');
      $this->assertEquals($xsdDetails['//car']['columns'][$index]['sourceNodeType'], 'tag');
    }

    public function testSimpleContentExtension() {
      $this->markTestIncomplete(
         'This test has not been implemented yet.'
       );
    }

    public function testComplexTypeSimpleContentExtension() {
      $this->markTestIncomplete(
         'This test has not been implemented yet.'
       );
    }

    public function testComplexTypeSimpleContentRestriction() {
      $this->markTestIncomplete(
         'This test has not been implemented yet.'
       );
    }
}
