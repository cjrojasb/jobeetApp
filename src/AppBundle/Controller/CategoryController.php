<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
* Category controller.
*/

class CategoryController extends Controller
{        
  public function showAction($slug, $page)
  {
    $em = $this->getDoctrine()->getManager();
    $category = $em->getRepository('AppBundle:Category')->findOneBySlug($slug);
    if (!$category) {
      throw $this->createNotFoundException('Unable to find Category entity.');
    }
    $total_jobs = $em->getRepository('AppBundle:Job')->countActiveJobs($category->getId());
    $jobs_per_page = $this->container->getParameter('max_jobs_on_category');
    $last_page = ceil($total_jobs / $jobs_per_page);
    $previous_page = $page > 1 ? $page - 1 : 1;
    $next_page = $page < $last_page ? $page + 1 : $last_page;
    $category->setActiveJobs($em->getRepository('AppBundle:Job')->getActiveJobs($category->getId(), $jobs_per_page, ($page - 1) * $jobs_per_page));
    
    return $this->render('category/show.html.twig', array(
      'category' => $category,
      'last_page' => $last_page,
      'previous_page' => $previous_page,
      'current_page' => $page,
      'next_page' => $next_page,
      'total_jobs' => $total_jobs
    ));
  }
}

// <?php
// namespace AppBundle\Tests\Controller;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
 
// class CategoryControllerTest extends WebTestCase
// {
//   public function testShow()
//   {
//     // get the custom parameters from app config.yml
//     $kernel = static::createKernel();
//     $kernel->boot();
//     $max_jobs_on_homepage = $kernel->getContainer()->getParameter('max_jobs_on_homepage');
//     $max_jobs_on_category = $kernel->getContainer()->getParameter('max_jobs_on_category');
 
//     $client = static::createClient();
 
//     // categories on homepage are clickable
//     $crawler = $client->request('GET', '/');
//     $link = $crawler->selectLink('Programming')->link();
//     $crawler = $client->click($link);
//     $this->assertEquals('AppBundle\Controller\CategoryController::showAction', $client->getRequest()->attributes->get('_controller'));
//     $this->assertEquals('programming', $client->getRequest()->attributes->get('slug'));
 
//     // categories with more than $max_jobs_on_homepage jobs also have a "more" link
//     $crawler = $client->request('GET', '/');
//     $link = $crawler->selectLink('22')->link();
//     $crawler = $client->click($link);
//     $this->assertEquals('AppBundle\Controller\CategoryController::showAction', $client->getRequest()->attributes->get('_controller'));
//     $this->assertEquals('programming', $client->getRequest()->attributes->get('slug'));
 
//     // only $max_jobs_on_category jobs are listed
//     $this->assertTrue($crawler->filter('.jobs tr')->count() <= $max_jobs_on_category);
//     $this->assertRegExp('/32 jobs/', $crawler->filter('.pagination_desc')->text());
//     $this->assertRegExp('/page 1\/2/', $crawler->filter('.pagination_desc')->text());
 
//     $link = $crawler->selectLink('2')->link();
//     $crawler = $client->click($link);
//     $this->assertEquals(2, $client->getRequest()->attributes->get('page'));
//     $this->assertRegExp('/page 2\/2/', $crawler->filter('.pagination_desc')->text());
//   }
// }
