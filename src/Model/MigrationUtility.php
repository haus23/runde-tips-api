<?php

namespace App\Model;

use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MigrationUtility
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Connection
     */
    private $legacyDB;

    /**
     * MigrationUtility constructor.
     * @param UserRepository $userRepository
     * @param Connection $legacyDB
     */
    public function __construct(UserRepository $userRepository, Connection $legacyDB)
    {
        $this->userRepository = $userRepository;
        $this->legacyDB = $legacyDB;
    }

    /**
     * @return bool
     */
    public function hasUserMigrations(): bool
    {
        try {
            $countOfLegacyUsers = $this->legacyDB->fetchColumn('select count(id) from user');
            $countOfMigratedUsers = $this->userRepository
                ->createQueryBuilder('u')
                ->select('count(u.id)')
                ->getQuery()
                ->getSingleScalarResult();

            return $countOfMigratedUsers < $countOfLegacyUsers;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function hasChampionshipMigrations(): bool
    {
        return false;
    }

    public function migrateUsers() {
        $championships = $this->legacyDB->fetchAll('select id from turnier order by `order`');
        dump($championships);

    }
}