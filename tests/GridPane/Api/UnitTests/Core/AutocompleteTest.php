<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use GuzzleHttp\Psr7\Response;
use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

/**
 * Autocomplete test class
 */
class AutocompleteTest extends BasicTest
{
    /**
     * Test endpoint for autocompleting tags
     */
    public function testTags()
    {
        $this->mockAPIResponses([
            new Response(200, [], ''),
        ]);

        $queryParams = [
            'name' => 'att',
        ];

        $this->client->autocomplete()->tags($queryParams);

        $this->assertLastRequestIs(
            [
                'method' => 'GET',
                'endpoint' => 'autocomplete/tags.json',
                'queryParams' => $queryParams,
            ]
        );
    }
}
