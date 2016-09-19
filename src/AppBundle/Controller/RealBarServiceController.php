<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RealBarServiceController extends Controller
{
    /**
     * @Route("/realbars", name="realBarService")
     */
    public function indexAction(Request $request)
    {
		$zip = $request->get('zip');
		if(!isset($zip) || !is_numeric($zip) && 5 === strlen($zip)) {
			throw new \InvalidArgumentException('Invalid zip code specified');
		}

		//Step 1: Translate the zip code to a latlon coordinate.

		$g = $this->get('geocoder'); //pull a geocoder from DI
		$loc = $g->getCoordForZip($zip);

		$s = $this->get('place-searcher'); //pull a searcher from DI
		$places = $s->search($loc, 1000, 'bar');

		$response = new Response(json_encode($places));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
    }
}
