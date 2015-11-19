<?php

	namespace AppBundle\Tests\Controller;

	use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

	class PlacesControllerTest extends WebTestCase{
		public function testIndex(){
			$client = static::createClient();
			$client->followRedirects(true);

			$crawler = $client->request('GET', '/');

			$this->assertEquals(200, $client->getResponse()->getStatusCode());
			$this->assertContains('Find Something To Eat', $crawler->filter('.twelvecol h1')->text());
		}
	}

?>