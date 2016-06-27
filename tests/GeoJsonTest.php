<?php

namespace Dmandrade\GeoJson\Tests;


use Dmandrade\GeoJson\GeoJson;

class GeoJsonTest extends \PHPUnit_Framework_TestCase
{
    public function testIsJsonSerializable()
    {
        $this->assertInstanceOf('JsonSerializable', $this->getMock('Dmandrade\GeoJson\GeoJson'));
    }

    public function testIsJsonUnserializable()
    {
        $this->assertInstanceOf('Dmandrade\GeoJson\JsonUnserializable', $this->getMock('Dmandrade\GeoJson\GeoJson'));
    }

    /**
     * @dataProvider provideJsonDecodeAssocOptions
     * @group functional
     */
    public function testUnserializationWithBoundingBox($assoc)
    {
        $json = <<<'JSON'
{
    "type": "Point",
    "coordinates": [1, 1],
    "bbox": [-180.0, -90.0, 180.0, 90.0]
}
JSON;

        $json = json_decode($json, $assoc);
        $point = GeoJson::jsonUnserialize($json);

        $this->assertInstanceOf('Dmandrade\GeoJson\Geometry\Point', $point);
        $this->assertSame('Point', $point->getType());
        $this->assertSame(array(1, 1), $point->getCoordinates());

        $boundingBox = $point->getBoundingBox();

        $this->assertInstanceOf('Dmandrade\GeoJson\BoundingBox', $boundingBox);
        $this->assertSame(array(-180.0, -90.0, 180.0, 90.0), $boundingBox->getBounds());
    }

    /**
     * @dataProvider provideJsonDecodeAssocOptions
     * @group functional
     */
    public function testUnserializationWithCrs($assoc)
    {
        $json = <<<'JSON'
{
    "type": "Point",
    "coordinates": [1, 1],
    "crs": {
        "type": "name",
        "properties": {
            "name": "urn:ogc:def:crs:OGC:1.3:CRS84"
        }
    }
}
JSON;

        $json = json_decode($json, $assoc);
        $point = GeoJson::jsonUnserialize($json);

        $this->assertInstanceOf('Dmandrade\GeoJson\Geometry\Point', $point);
        $this->assertSame('Point', $point->getType());
        $this->assertSame(array(1, 1), $point->getCoordinates());

        $crs = $point->getCrs();

        $expectedProperties = array('name' => 'urn:ogc:def:crs:OGC:1.3:CRS84');

        $this->assertInstanceOf('Dmandrade\GeoJson\CoordinateReferenceSystem\Named', $crs);
        $this->assertSame('name', $crs->getType());
        $this->assertSame($expectedProperties, $crs->getProperties());
    }

    public function provideJsonDecodeAssocOptions()
    {
        return array(
            'assoc=true' => array(true),
            'assoc=false' => array(false),
        );
    }
}
