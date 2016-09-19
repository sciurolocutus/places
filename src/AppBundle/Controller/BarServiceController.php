<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BarServiceController extends Controller
{
    /**
     * @Route("/bars", name="barService")
     */
    public function indexAction(Request $request)
    {
		$centerX = $request->get('x');
		if(!isset($centerX) || !is_numeric($centerX)) {
			$centerX = 3.0;
		}
		$centerY = $request->get('y');
		if(!isset($centerY) || !is_numeric($centerY)) {
			$centerY = 3.0;
		}
		$rad = $request->get('r');
		if(!isset($rad) || !is_numeric($rad)) {
			$rad = 3.0;
		}

		//n: number of 
		$n = $request->get('n');
		if(!isset($n) || !is_numeric($n) || intval($n, 10) > 20 || intval($n, 10) < 0) {
			$n = 5;
		}

		//Step 1: pull some bar locations
		// For now, we will fake these.

		$coordFaker = new \Coordinates\CoordinateFaker(new \Coordinates\Coordinate($centerX, $centerY), $rad);

		//$names = array("Stumpy's", "The Pit", "The Pendulum", "Blackout Murphy's", "Electroshock Therapy Ltd");

		$bars = array();
		for($i=0; $i<$n; $i++) {
			$coord = $coordFaker->generateCoordinate();
			$bars[] = array(
				'x' => $coord->getLatitude(),
				'y' => $coord->getLongitude()
			);
		}

		$response = new Response(json_encode($bars));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
    }
}
