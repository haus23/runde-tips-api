<?php

namespace Functional\Tests\Migration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MigrationsTest extends WebTestCase
{
    public function testHasMigrateUsersButton()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'pa$$word'
        ]);
        $crawler = $client->request('GET', '/migration');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html a.list-group-item:contains("Migrate Users")')->count()
        );
    }
}
