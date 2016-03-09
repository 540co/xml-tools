<?php
use FiveFortyCo\Xmltools;

class XmltoolsTest extends PHPUnit_Framework_TestCase
{
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
}
