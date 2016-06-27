<?php

namespace Dmandrade\GeoJson\Tests\CoordinateReferenceSystem;

use Dmandrade\GeoJson\CoordinateReferenceSystem\CoordinateReferenceSystem;

class CoordinateReferenceSystemTest extends \PHPUnit_Framework_TestCase
{
    public function testIsJsonSerializable()
    {
        $this->assertInstanceOf(
            'JsonSerializable',
            $this->getMock('Dmandrade\GeoJson\CoordinateReferenceSystem\CoordinateReferenceSystem')
        );
    }

    public function testIsJsonUnserializable()
    {
        $this->assertInstanceOf(
            'Dmandrade\GeoJson\JsonUnserializable',
            $this->getMock('Dmandrade\GeoJson\CoordinateReferenceSystem\CoordinateReferenceSystem')
        );
    }

    /**
     * @expectedException \Dmandrade\GeoJson\Exception\UnserializationException
     * @expectedExceptionMessage CRS expected value of type array or object
     */
    public function testUnserializationShouldRequireArrayOrObject()
    {
        CoordinateReferenceSystem::jsonUnserialize(null);
    }

    /**
     * @expectedException \Dmandrade\GeoJson\Exception\UnserializationException
     * @expectedExceptionMessage CRS expected "type" property of type string, none given
     */
    public function testUnserializationShouldRequireTypeField()
    {
        CoordinateReferenceSystem::jsonUnserialize(array('properties' => array()));
    }

    /**
     * @expectedException \Dmandrade\GeoJson\Exception\UnserializationException
     * @expectedExceptionMessage CRS expected "properties" property of type array or object, none given
     */
    public function testUnserializationShouldRequirePropertiesField()
    {
        CoordinateReferenceSystem::jsonUnserialize(array('type' => 'foo'));
    }

    /**
     * @expectedException \Dmandrade\GeoJson\Exception\UnserializationException
     * @expectedExceptionMessage Invalid CRS type "foo"
     */
    public function testUnserializationShouldRequireValidType()
    {
        CoordinateReferenceSystem::jsonUnserialize(array('type' => 'foo', 'properties' => array()));
    }
}
