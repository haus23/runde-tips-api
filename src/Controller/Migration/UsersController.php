<?php

namespace App\Controller\Migration;

use App\Model\MigrationUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/migration/users", name="migration_users")
     *
     * @param MigrationUtility $migrationUtility
     * @return Response
     */
    public function index(MigrationUtility $migrationUtility)
    {
        if (!$migrationUtility->hasUserMigrations()) {

        }

        $migrationUtility->migrateUsers();

        return $this->render('migration/users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
