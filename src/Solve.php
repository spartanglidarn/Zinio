<?php
require __DIR__ . '/../vendor/autoload.php';

class Solve 
{

    public function runSolve()
    {
        $citiesList = $this->readCitiesFile();
        $nextCity = [
            'name' => 'Beijing',
            'lat' => $citiesList['Beijing']['lat'],
            'long' => $citiesList['Beijing']['long'],
        ];
        unset($citiesList[$nextCity['name']]);
        $travelPlan[] = $nextCity['name'];
        while (count($citiesList) !== 1) {
            $nextCity = $this->compareCities($nextCity, $citiesList);
            $travelPlan[] = $nextCity['name'];
            unset($citiesList[$nextCity['name']]);
        }
        foreach ($citiesList as $cityName => $_){
            $travelPlan[] = $cityName;
        }
        
        foreach ($travelPlan as $printCity) {
            echo $printCity . PHP_EOL;
        }
    }

    public function readCitiesFile () 
    {
        $handle = fopen("cities.txt", "r");
        if ($handle) {
           $citiesArray = []; 
            while (($line = fgets($handle)) !== false) {
                $lineSplit = explode("/t", $line);
                if (count($lineSplit) > 1){
                    $citiesArray[$lineSplit[0]] = [
                        'lat' => trim($lineSplit[1]),
                        'long' => trim($lineSplit[2]), 
                    ];
                }
            }
            fclose($handle);
        } else {
            echo "Error opening the file";
        }
    
        return($citiesArray);
    }
    
    public function calcDist ($lat1, $lon1, $lat2, $lon2, $unit = "K")
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
    
    public function compareCities ($city, $citiesList) {
        foreach ($citiesList as $city2 => $position2) {
            if ($city !== $city2) {
                $distance = $this->calcDist($city['lat'], $city['long'], $position2['lat'], $position2['long']);
                if (isset($closestCity) && $closestCity['dist'] > $distance) {
                    $closestCity = [
                        'name' => $city2,
                        'dist' => $distance,
                        'lat' => $position2['lat'],
                        'long' => $position2['long'],
                    ];
                } elseif (!isset($closestCity)) {
                    $closestCity = [
                        'name' => $city2,
                        'dist' => $distance,
                        'lat' => $position2['lat'],
                        'long' => $position2['long'],
                    ];
                }
            }
        }
        return $closestCity;
    }
}
