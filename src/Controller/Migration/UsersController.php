<?php

namespace App\Controller\Migration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/migration/users", name="migration_users")
     */
    public function index()
    {
        return $this->render('migration/users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
