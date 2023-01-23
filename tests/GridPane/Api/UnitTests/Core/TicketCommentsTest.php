<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

/**
 * Ticket Comments test class
 */
class TicketCommentsTest extends BasicTest
{
    /**
     * Test findAll method
     */
    public function testAll()
    {
        $ticketId = 1234;
        $this->assertEndpointCalled(function () use ($ticketId) {
            $this->client->tickets($ticketId)->comments()->findAll();
        }, "tickets/{$ticketId}/comments.json");
    }

    /**
     * Test make private
     */
    public function testMakePrivate()
    {
        $ticketId = 12345;
        $commentId = 123;
        $this->assertEndpointCalled(function () use ($ticketId, $commentId) {
            $this->client->tickets($ticketId)->comments($commentId)->makePrivate();
        }, "tickets/{$ticketId}/comments/{$commentId}/make_private.json", 'PUT');
    }
}
