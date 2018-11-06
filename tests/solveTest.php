<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Acme\Solve;
final class UserTweetsRequestTest extends TestCase
{   
    public function testReadCitiesFile()
    {
        $solve = new Acme\Solve();
        $file = $solve->readCitiesFile();
        $this->assertInternalType('array', $file);
    }
    public function testCalcDist()
    {
        $solve = new Acme\Solve();
        $dist = $solve->calcDist("1.14", "103.55", "43.40", "-79.24");

        $this->assertInternalType('float', $dist);
    }

    public function testCompareCities()
    {
        $city = [
            'name' => 'Beijing',
            'lat' => $citiesList['Beijing']['lat'],
            'long' => $citiesList['Beijing']['long'],
        ];

        $citiesList['Moscow'] = [
            'lat' => '55.45',
            'long' => '37.36',
        ];
        $citiesList['SanJose'] = [
            'lat' => '9.55',
            'long' => '-84.02',
        ];
        $solve = new Acme\Solve();
        $closestCity = $solve->compareCities($city, $citiesList);

        $this->assertArrayHasKey('name', $closestCity);
        $this->assertArrayHasKey('dist', $closestCity);
        $this->assertArrayHasKey('lat', $closestCity);
        $this->assertArrayHasKey('long', $closestCity);
    }
}
