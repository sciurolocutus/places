<?php

require_once(__DIR__ . '/autoloader.php');

use \Coordinates\CoordinateFaker;
use \Coordinates\Coordinate;

$faker = new CoordinateFaker(
	new Coordinate(
		41.248936,
		-96.008865
	),
	0.07
);

$dots = array();
for($i=0; $i<10; $i++) {
	$dots[] = $faker->generateCoordinate();
}

//var_export($dots);

echo json_encode($dots), "\n";
