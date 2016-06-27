<?php

namespace Dmandrade\GeoJson\Tests\Geometry;

class GeometryTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSubclassOfGeoJson()
    {
        $this->assertTrue(is_subclass_of('Dmandrade\GeoJson\Geometry\Geometry', 'Dmandrade\GeoJson\GeoJson'));
    }
}
