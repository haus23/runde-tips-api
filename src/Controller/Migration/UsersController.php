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
     * @Route("/migration/users", name="migration_users", methods={"GET"})
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

        return $this->render('migration/users/index.html.twig', [
            'countOfUsers' => $countOfUsersToMigrate,
            'error' => $error
        ]);
    }

    /**
     * @Route("/migration/users", methods={"POST"})
     *
     * @param MigrationUtility $migrationUtility
     * @return Response
     * @throws Exception
     */
    public function migrate(MigrationUtility $migrationUtility)
    {
        $numberOfMigratedUsers = $migrationUtility->migrateUsers();
        $this->addFlash('success', "$numberOfMigratedUsers User umgewandelt.");

        return $this->redirectToRoute('migration');
    }
}
