<?php
namespace Coordinates;

use \Coordinates\Coordinate;

/**
* @class CoordinateFaker
* 
* @section description
* A "seedable" generator of @Coordinate objects.
* Takes a center @Coordinate and a double radius at construction,
* and can produce @Coordinate objects randomly centered around the center,
* within the radius.
*/
class CoordinateFaker {
	protected $center;
	protected $radius;

/*
* The constructor, taking the center and radius.
*/
	public function __construct(Coordinate $ctr, $rad) {
		$this->center = $ctr;
		$this->radius = $rad;
	}

/*
* Generator method
* @note: uses mt_rand to generate random numbers.
* @return a new @Coordinate randomly generated in a circular area,
*   centered at @center, with radius @radius.
*/
	public function generateCoordinate() {
		$x = $this->center->getLatitude() + (mt_rand(0, mt_getrandmax()) / mt_getrandmax()) * $this->radius;
		$y = $this->center->getLongitude() + (mt_rand(0, mt_getrandmax()) / mt_getrandmax()) * $this->radius;
		return new Coordinate($x, $y);
	}
}
