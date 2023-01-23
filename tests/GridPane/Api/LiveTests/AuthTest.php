<?php

namespace KyleWLawrence\GridPane\API\LiveTests;

/**
 * Auth test class
 */
class AuthTest extends BasicTest
{
    /**
     * Test the use of basic test
     */
    public function testBasicAuth()
    {
        $this->client->setAuth('bearer', ['bearer' => $this->bearer]);
        $servers = $this->client->server()->getAll();
        $this->assertTrue(isset($servers->data), 'Should return a valid user object.');
    }
}
