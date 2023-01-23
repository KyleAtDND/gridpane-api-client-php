<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

/**
 * OrganizationFields test class
 */
class OrganizationFieldsTest extends BasicTest
{
    /**
     * Test reorder method
     */
    public function testReorder()
    {
        $postFields = ['organization_field_ids' => [14382, 14342]];

        $this->assertEndpointCalled(function () use ($postFields) {
            $this->client->organizationFields()->reorder($postFields);
        }, 'organization_fields/reorder.json', 'PUT', ['postFields' => $postFields]);
    }
}
