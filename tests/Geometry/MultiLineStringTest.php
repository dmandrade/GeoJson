<?php

namespace Dmandrade\GeoJson\Tests\Geometry;

use Dmandrade\GeoJson\GeoJson;
use Dmandrade\GeoJson\Geometry\LineString;
use Dmandrade\GeoJson\Geometry\MultiLineString;
use Dmandrade\GeoJson\Tests\BaseGeoJsonTest;

class MultiLineStringTest extends BaseGeoJsonTest
{
    public function createSubjectWithExtraArguments(array $extraArgs)
    {
        $class = new \ReflectionClass('Dmandrade\GeoJson\Geometry\MultiLineString');

        return $class->newInstanceArgs(array_merge(
            array(array(
                array(array(1, 1), array(2, 2)),
                array(array(3, 3), array(4, 4)),
            )),
            $extraArgs
        ));
    }

    public function testIsSubclassOfGeometry()
    {
        $this->assertTrue(is_subclass_of('Dmandrade\GeoJson\Geometry\MultiLineString', 'Dmandrade\GeoJson\Geometry\Geometry'));
    }

    public function testConstructionFromLineStringObjects()
    {
        $multiLineString1 = new MultiLineString(array(
            new LineString(array(array(1, 1), array(2, 2))),
            new LineString(array(array(3, 3), array(4, 4))),
        ));

        $multiLineString2 = new MultiLineString(array(
            array(array(1, 1), array(2, 2)),
            array(array(3, 3), array(4, 4)),
        ));

        $this->assertSame($multiLineString1->getCoordinates(), $multiLineString2->getCoordinates());
    }

    public function testSerialization()
    {
        $coordinates = array(
            array(array(1, 1), array(2, 2)),
            array(array(3, 3), array(4, 4)),
        );

        $multiLineString = new MultiLineString($coordinates);

        $expected = array(
            'type' => 'MultiLineString',
            'coordinates' => $coordinates,
        );

        $this->assertSame('MultiLineString', $multiLineString->getType());
        $this->assertSame($coordinates, $multiLineString->getCoordinates());
        $this->assertSame($expected, $multiLineString->jsonSerialize());
    }

    /**
     * @dataProvider provideJsonDecodeAssocOptions
     * @group functional
     */
    public function testUnserialization($assoc)
    {
        $json = <<<'JSON'
{
    "type": "MultiLineString",
    "coordinates": [
        [ [1, 1], [2, 2] ],
        [ [3, 3], [4, 4] ]
    ]
}
JSON;

        $json = json_decode($json, $assoc);
        $multiLineString = GeoJson::jsonUnserialize($json);

        $expectedCoordinates = array(
            array(array(1, 1), array(2, 2)),
            array(array(3, 3), array(4, 4)),
        );

        $this->assertInstanceOf('Dmandrade\GeoJson\Geometry\MultiLineString', $multiLineString);
        $this->assertSame('MultiLineString', $multiLineString->getType());
        $this->assertSame($expectedCoordinates, $multiLineString->getCoordinates());
    }

    public function provideJsonDecodeAssocOptions()
    {
        return array(
            'assoc=true' => array(true),
            'assoc=false' => array(false),
        );
    }
}
