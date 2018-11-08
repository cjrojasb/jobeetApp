<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
  public function testShow()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/category/programming');
    $this->assertEquals('AppBundle\Controller\CategoryController::showAction', $client->getRequest()->attributes->get('_controller'));
    $this->assertTrue(200 === $client->getResponse()->getStatusCode());
  }
}