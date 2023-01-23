<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

/**
 * Sharing Agreements test class
 */
class SharingAgreementsTest extends BasicTest
{
    /**
     * Test that the findAll method was included
     */
    public function testRoutes()
    {
        $this->assertTrue(method_exists($this->client->sharingAgreements(), 'findAll'));
    }
}
