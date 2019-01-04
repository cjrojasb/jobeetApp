<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class JobController extends FOSRestController
{
  /**
  * @Rest\View
  */
  public function getJobsAction()
  {
    $em = $this->getDoctrine()->getManager();
    $jobs = $em->getRepository('AppBundle:Job')->getActiveJobs();
    $view = $this->view($jobs);
    return $this->handleView($view);
  }
}