<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/backend", name="backend")
     */
    public function index()
    {
        return $this->render('backend/default/index.html.twig');
    }
}
