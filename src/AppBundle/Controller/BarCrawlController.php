<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BarCrawlController extends Controller
{
    /**
     * @Route("/crawl", name="barCrawl")
     */
    public function indexAction(Request $request)
    {
		//Step 1: pull some bar locations
		// For now, we will fake these.

		$coordFaker = new \Coordinates\CoordinateFaker(new \Coordinates\Coordinate(3.0, 3.0), 2.0);

		$names = array("Stumpy's", "The Pit", "The Pendulum", "Blackout Murphy's", "Electroshock Therapy Ltd");

		$bars = array();
		foreach($names as $name) {
			$coord = $coordFaker->generateCoordinate();
			$bars[$name] = array(
				'x' => $coord->getLatitude(),
				'y' => $coord->getLongitude()
			);
		}
				

        return $this->render('default/pathviewer.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
			'bars' => json_encode($bars),
			'barData' => $bars
        ]);
    }
}
