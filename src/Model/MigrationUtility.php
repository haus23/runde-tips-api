<?php

namespace App\Model;

use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Exception;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MigrationUtility constructor.
     * @param UserRepository $userRepository
     * @param Connection $legacyDB
     * @param LoggerInterface $logger
     */
    public function __construct(UserRepository $userRepository, Connection $legacyDB, \Psr\Log\LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->legacyDB = $legacyDB;
        $this->logger = $logger;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function countUsersToMigrate(): int
    {
        try {
            $countOfLegacyUsers = $this->legacyDB->fetchColumn('select count(id) from user');
            $countOfMigratedUsers = $this->userRepository
                ->createQueryBuilder('u')
                ->select('count(u.id)')
                ->getQuery()
                ->getSingleScalarResult();

            return $countOfLegacyUsers - $countOfMigratedUsers;
        } catch (Exception $ex) {
            $this->logger->error('Problem with user migrations.');
            throw new Exception('Problem with user migrations.');
        }
    }

    /**
     * @return int
     */
    public function countChampionshipsToMigrate(): int
    {
        return -1;
    }

    public function migrateUsers() {
        $championships = $this->legacyDB->fetchAll('select id from turnier order by `order`');

    }
}