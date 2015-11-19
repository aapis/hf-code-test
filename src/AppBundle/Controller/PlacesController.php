<?php

	namespace AppBundle\Controller;

	use AppBundle\Entity\Place;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Symfony\Component\HttpFoundation\Session\Session;

	class PlacesController extends Controller {
		/**
		 * @Route("/")
		 */
		public function places(Request $request){
			$place = new Place();

			$form = $this->createFormBuilder($place)
					->add('api_key', 'text', array('label' => 'API Key', 'data' => 'AIzaSyCiMs7T_ANR_qVNQIt6w0dAav13PMizuAw'))
					->add('query', 'text', array('label' => "Type of food you're looking for (i.e. vegetarian, pho, italian)"))
					->add('save', 'submit', array('label' => 'Get Info'))
					->getForm();

			$form->handleRequest($request);

			if($form->isValid()){
				$session = $request->getSession();
				$session->set('places', $place->getPlaces());
				$session->set('query', $form->getData()->getQuery());

				return $this->redirectToRoute('result');
			}

			return $this->render('places/place.html.twig', array('form' => $form->createView()));
		}

		/**
		 * @Route("/result")
		 */
		public function result(Request $request){
			$session = $request->getSession();

			$query_results = $session->get('places', array());

			if(sizeof($query_results) > 0 && !$session->get('error')){
				return $this->render('places/result.html.twig',
					array(
						'places' => $query_results,
						'num_results' => sizeof($query_results),
						'query' => $session->get('query')
						)
					);
			}else {
				return $this->render('places/result_error.html.twig',
					array(
						'query' => $session->get('query')
						)
					);
			}
		}
	}

?>