<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

/**
 * Translations test class
 */
class TranslationsTest extends BasicTest
{
    /**
     * Test manifest method
     */
    public function testFindManifest()
    {
        $this->assertEndpointCalled(function () {
            $this->client->translations()->manifest();
        }, 'translations/slack-auth-service/manifest.json');
    }
}
