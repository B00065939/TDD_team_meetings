<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 09/02/2017
 * Time: 12:03
 */

namespace tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomepage() {
      //  $this->assertTrue(1==1);
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}