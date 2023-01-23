<?php

namespace KyleWLawrence\GridPane\API\UnitTests;

use Faker\Factory;
use KyleWLawrence\GridPane\API\HttpClient;

/**
 * Class VersionTest
 */
class VersionTest extends BasicTest
{
    /**
     * Test that the versions used across the package are consistent
     */
    public function testVersionsAreConsistent()
    {
        $faker = Factory::create();

        $fileVersion = trim(file_get_contents(__DIR__.'/../../../../VERSION'));
        $client = new HttpClient($faker->word);

        $this->assertEquals("GridPaneAPI PHP {$fileVersion}", $client->getUserAgent());
    }
}
