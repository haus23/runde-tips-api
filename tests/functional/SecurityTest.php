<?php

namespace Functional\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function testMigrationFeatureIsSecured()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/migration');

        $this->assertResponseStatusCodeSame(401);
    }
}
