<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class UserDashboardControllerTest extends PantherTestCase
{
    public function testMenu()
    {
        $client = static::createPantherClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}