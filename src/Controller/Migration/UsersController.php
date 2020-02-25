<?php

namespace App\Controller\Migration;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/migration/users", name="migration_users")
     *
     * @param Connection $legacyDB
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $legacyDB)
    {
        $legacyUsers = $legacyDB->fetchAll('select * from user');
        dump($legacyUsers);
        
        return $this->render('migration/users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
