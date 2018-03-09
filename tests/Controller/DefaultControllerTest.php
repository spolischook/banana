<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomeAction()
    {
        $client = static::createClient();

        $client->request('GET', '');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}