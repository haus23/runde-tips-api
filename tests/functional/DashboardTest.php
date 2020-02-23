<?php

namespace Functional\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardTest extends WebTestCase
{
    public function testDashboardOpens()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
