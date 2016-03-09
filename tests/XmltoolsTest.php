<?php
use FiveFortyCo\Xmltools;

class XmltoolsTest extends PHPUnit_Framework_TestCase
{
    public function testXsdDetails()
    {
        $filename = dirname(__FILE__) . '/data/purchase-order.xsd';
        $xsdDetails = Xmltools::getXsdDetails($filename);
        
        $this->assertArrayHasKey("//PurchaseOrder", $xsdDetails);
        $this->assertArrayHasKey("//PurchaseOrder/ShipTo", $xsdDetails);
    }
}
