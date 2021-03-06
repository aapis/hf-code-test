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
			$types = array(
				'food',
				'cafe',
				'drink',
				'establishment',
				);

			$url = sprintf("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=53.55014,-113.46871&types=%s&keyword=%s&radius=10000&key=%s",
				join($types, '|'),
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
				
				//get more details about each place by making a second Place API
				//request on each result
				for($i = 0; $i < sizeof($request->results); $i++){
					$details_url = sprintf("https://maps.googleapis.com/maps/api/place/details/json?placeid=%s&key=%s",
						$request->results[$i]->place_id,
						$this->api_key
						);

					$details_ch = curl_init($details_url);

					curl_setopt($details_ch, CURLOPT_HEADER, 0);
					curl_setopt($details_ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($details_ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($details_ch, CURLOPT_HTTPGET, true);

					$this->places[$i]->details = json_decode(curl_exec($details_ch))->result;

					curl_close($details_ch);
				}
			}

			curl_close($ch);

			return $this->places;
		}

		public function setPlaces($value){
			return null;
		}
	}

?>