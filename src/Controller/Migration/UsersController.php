<?php

namespace App\Controller\Migration;

use App\Model\MigrationUtility;
use Exception;
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
        $countOfUsersToMigrate = 0;
        $error = null;
        try {
            $countOfUsersToMigrate = $migrationUtility->countUsersToMigrate();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        $migrationUtility->migrateUsers();

        return $this->render('migration/users/index.html.twig', [
            'countOfUsers' => $countOfUsersToMigrate,
            'error' => $error
        ]);
    }
}
