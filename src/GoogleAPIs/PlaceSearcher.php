<?php
namespace GoogleAPIs;

use \Coordinates\Coordinate;

class PlaceSearcher {
	protected $key;

	public function __construct($key) {
		$this->key = $key;
	}

	public function search($loc, $rad='800', $type='bar') {
		if($loc instanceof Coordinate && is_numeric($rad) && $rad < 5000) {
			$url = sprintf("https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=%s&location=%s&radius=%d&type=%s",
				$this->key,
				$loc,
				$rad,
				$type
			);
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);

			$result = curl_exec($ch);
			curl_close($ch);

			$resp = json_decode($result, true);

			$places = array();

			foreach($resp['results'] as $place) {
				$places[] = array(
					'name' => $place['name'],
					'x' => $place['geometry']['location']['lat'],
					'y' => $place['geometry']['location']['lng']
				);
			}

			return $places;

			//$loc = $resp['results'][0]['geometry']['location'];
			//return new Coordinate($loc['lat'], $loc['lng']);
		} else {
			return new \InvalidArgumentException('$loc is not a Coordinate');
		}
	}
}
