<?php

	namespace AppBundle\Entity;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;	

	class Place {
		protected $places = array();
		
		/**
		 * @Assert\NotBlank();
		 */
		protected $api_key;

		/**
		 * @Assert\NotBlank();
		 */
		protected $query;

		public function getApiKey(){
			return $this->api_key;
		}

		public function setApiKey($value){
			$this->api_key = $value;
		}

		public function getQuery(){
			return $this->query;
		}

		public function setQuery($value){
			$this->query = $value;
		}

		public function getPlaces(){
			$url = sprintf("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=53.55014,-113.46871&types=food|cafe|drink&keyword=%s&radius=5000&key=%s",
				$this->query,
				$this->api_key
				);
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPGET, true);

			$request = json_decode(curl_exec($ch));

			if($request && $request->status == 'OK'){
				$this->places = $request->results;
			}

			curl_close($ch);

			return $this->places;
		}

		public function setPlaces($value){
			return null;
		}
	}

?>