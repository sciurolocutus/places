<?php
namespace GoogleAPIs;

use \Coordinates\Coordinate;

class Geocoder {
	protected $key;

	public function __construct($key) {
		$this->key = $key;
	}

	public function getCoordForZip($z) {
		if(is_numeric($z) && 5 == strlen($z)) {
			$url = sprintf("https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s",
				$z,
				$this->key
			);
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);

			$result = curl_exec($ch);
			curl_close($ch);

			$resp = json_decode($result, true);

			$loc = $resp['results'][0]['geometry']['location'];
			return new Coordinate($loc['lat'], $loc['lng']);
		} else {
			throw new \InvalidArgumentException('Zip code failed validation.');
		}
	}
}
