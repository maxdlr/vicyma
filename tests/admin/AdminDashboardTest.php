<?php

namespace App\Tests\admin;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class AdminDashboardTest extends PantherTestCase
{
//        https://symfony.com/doc/current/testing.html#write-your-first-application-test
    private Client $client;

    public function testReservationNotificationSeeAllButton()
    {
//        $reservationRepository = self::getContainer()->get('App\Repository\ReservationRepository');

        self::assertSelectorTextContains('[data-symfony--ux-vue--vue-component-value="admin/AdminDashboard"]', 'See all');
    }

    protected function setUp(): void
    {
        $this->client = static::createPantherClient([
            'port' => 9999
        ]);
        $this->client->request('GET', '/admin');
        $this->client->submitForm('Sign in', ['_username' => 'contact@maxdlr.com', '_password' => 'password']);
    }
}