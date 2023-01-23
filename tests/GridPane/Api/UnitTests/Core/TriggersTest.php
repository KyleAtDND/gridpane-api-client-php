<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

/**
 * Triggers test class
 */
class TriggersTest extends BasicTest
{
    /**
     * Tests if the client can call and build the active triggers endpoint
     */
    public function testActive()
    {
        $this->assertEndpointCalled(function () {
            $this->client->triggers()->findActive();
        }, 'triggers/active.json');
    }
}
