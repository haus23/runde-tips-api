<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/backend/default", name="backend_default")
     */
    public function index()
    {
        return $this->render('backend/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
