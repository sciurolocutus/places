<?php
namespace Coordinates;

/**
* Coordinate
* 
* A lat-lon coordinate, immutable after construction.
* Mostly a plain-old PHP object, though it implements JsonSerializable for convenient conversion to JSON via json_encode.
*/

class Coordinate implements \JsonSerializable {
	protected $lat;
	protected $lon;

/**
* The constructor, taking latitude and longitude.
* @param lat double the latitude.
* @param lon double the longitude.
*/
	public function __construct($lat, $lon) {
		$this->lat = $lat;
		$this->lon = $lon;
	}

/**
getter: latitude
*/
	public function getLatitude() { return $this->lat; }
/**
getter: longitude
*/
	public function getLongitude() { return $this->lon; }


/**
* JSON serialization function
*
* satisfies JsonSerializable interface
* This is called when json_encode operates on an object of this type.
* @return a native PHP data type (array) which json_encode already knows how to JSONify.
*/
	public function jsonSerialize() {
		return array(
			'lat' => $this->lat,
			'lon' => $this->lon
		);
	}

/**
* @override
* toString override
* 
* Convenience function for treating it as a string.
*/
	public function __toString() {
		return join([$this->lat, $this->lon], ',');
	}
}
