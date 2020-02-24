<?php

namespace App\Controller\Migration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/migration", name="migration")
     */
    public function index()
    {
        return $this->render('migration/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
