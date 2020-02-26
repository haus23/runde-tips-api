<?php

namespace App\Controller\Migration;

use App\Model\MigrationUtility;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/migration", name="migration")
     *
     * @param MigrationUtility $migrationUtility
     * @return Response
     * @throws Exception
     */
    public function index(MigrationUtility $migrationUtility)
    {
        return $this->render('migration/default/index.html.twig', [
            'hasUserMigrations' => $migrationUtility->countUsersToMigrate() > 0,
            'hasChampionshipMigrations' => $migrationUtility->countChampionshipsToMigrate() > 0
        ]);
    }
}
