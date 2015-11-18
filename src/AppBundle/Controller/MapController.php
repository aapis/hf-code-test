<?php

	namespace AppBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Symfony\Component\HttpFoundation\Response;

	class MapController extends Controller {

		/**
     * @Route("/map")
     */
		public function mapAction(){
			$x = rand(0, 100);

			return new Response("<p>test - ". $x ."</p>");
		}
	}

?>