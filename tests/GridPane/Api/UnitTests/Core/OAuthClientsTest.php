<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

/**
 * OAuthClients test class
 */
class OAuthClientsTest extends BasicTest
{
    /**
     * Test that the crud functions were included
     */
    public function testRoutes()
    {
        $this->assertTrue(method_exists($this->client->oauthClients(), 'create'));
        $this->assertTrue(method_exists($this->client->oauthClients(), 'delete'));
        $this->assertTrue(method_exists($this->client->oauthClients(), 'find'));
        $this->assertTrue(method_exists($this->client->oauthClients(), 'findAll'));
        $this->assertTrue(method_exists($this->client->oauthClients(), 'update'));
    }

    /**
     * Test findAllMine method
     */
    public function testFindAllMine()
    {
        $this->assertEndpointCalled(function () {
            $this->client->oauthClients()->findAllMine();
        }, 'users/me/oauth/clients.json');
    }
}
