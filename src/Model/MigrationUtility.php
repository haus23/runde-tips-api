<?php

namespace App\Model;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class MigrationUtility
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

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
     * @param EntityManagerInterface $em
     * @param Connection $legacyDB
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $em, Connection $legacyDB, \Psr\Log\LoggerInterface $logger)
    {
        $this->em = $em;
        $this->userRepository = $em->getRepository(User::class);
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

    /**
     * @return int Number of migrated users
     * @throws Exception
     */
    public function migrateUsers(): int {

        $numberOfMigratedUsers = 0;

        try {
            $championships = $this->legacyDB->fetchAll('select id from turnier order by `order` limit 6');

            $migratedUsers = $this->userRepository->findAll();
            $legacyIds = array_map(function(User $u) { return $u->getLegacyId(); }, $migratedUsers);

            $usersToMigrateQuery = 'select u.id, u.name from user u, spieler s where s.turnier_id = ? and u.id = s.user_id';

            foreach ($championships as $championship) {
                $championshipId = $championship['id'];
                $usersToMigrate = $this->legacyDB->executeQuery($usersToMigrateQuery, [$championshipId])->fetchAll();

                foreach ($usersToMigrate as $u) {

                    if (array_search($u['id'], $legacyIds) === false) {
                        $legacyIds[] = $u['id'];

                        $user = new User();
                        $user->setName($u['name']);
                        $user->setLegacyId(($u['id']));
                        $this->em->persist($user);

                        ++$numberOfMigratedUsers;
                    }
                }
            }

            $this->em->flush();
        } catch (Exception $ex) {
            $this->logger->error('Problem with user migrations.');
            throw new Exception('Problem with user migrations.');
        }

        return $numberOfMigratedUsers;
    }
}