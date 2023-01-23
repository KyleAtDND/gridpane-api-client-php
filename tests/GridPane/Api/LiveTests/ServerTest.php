<?php

namespace KyleWLawrence\GridPane\API\LiveTests;

use Faker\Factory;
use KyleWLawrence\GridPane\Api\Exceptions\ServerBuilding;

/**
 * Server test class
 */
class ServerTest extends BasicTest
{
    /**
     * Test creating of server
     */
    public function testCreateServer()
    {
        $faker = Factory::create();
        $provider = getenv('PROVIDER');
        $plans = $this->client->server()->getPlans($provider);

        $planId = 'micro_2_0';
        $planKey = array_search($planId, array_column($plans->plans, 'id'));
        $this->assertTrue(is_int($planKey));
        $plan = $plans->plans[$planKey];
        $region = reset($plan->regions)->id;

        $integrations = $this->client->user()->getCurrentIntegrations();
        $accessKey = getenv('AWS_ACCESS_KEY_ID');
        $intKey = array_search($accessKey, array_column($integrations->integrations, 'token'));
        $this->assertTrue(is_int($intKey));
        $integration = $integrations->integrations[$intKey];

        $serverParams = [
            'integration_id' => $integration->id,
            'name' => $faker->name(),
            'region_id' => $region,
            'plan_id' => $planId,
            'webserver' => 'nginx',
            'database' => 'percona',
            'enable_auto_backups' => false,
        ];

        $response = $this->client->server()->create($provider, $serverParams);

        foreach ($serverParams as $key => $val) {
            $this->assertEquals($val, $server->building->$key);
        }

        $this->assertNotNull($response->temp_pass);
        $this->assertNotNull($response->server_id);
        $this->assertNotNull($response->build_status);

        return $server;
    }

    /**
     * Tests if the client can call and build the servers endpoint with the proper sideloads
     *
     * @depends testCreateServer
     */
    public function testFindAll($server)
    {
        $response = $this->client->server()->getAll();
        $this->assertTrue(property_exists($response, 'data'));
        $this->assertGreaterThan(0, count($response->data));
    }

    /**
     * Tests if the client can call and build the find server endpoint
     *
     * @depends testCreateServer
     *
     * @retryAttempts 10
     *
     * @retryDelaySeconds 60
     *
     * @retryIfException GridPane\Api\Exceptions\ServerBuilding
     */
    public function testFindSingle($server)
    {
        $response = $this->client->server()->getProgress($server->id);

        foreach (['success', 'server_id', 'build_percentage', 'current_details', 'updated_at'] as $property) {
            $this->assertTrue(property_exists($response->server, $property));
        }

        if ($server->build_percentage < 100) {
            throw new ServerBuilding("Build Percentage: $server->build_percentage");
        }
    }

    /**
     * Tests if the client can update servers
     *
     * @depends testCreateServer
     */
    public function testUpdate($server)
    {
        $faker = factory::create();
        $serverParams = [
            'subject' => $faker->sentence(3),
            'priority' => 'high',
        ];
        $updatedServer = $this->client->server()->update($server->id, $serverParams);
        $this->assertEquals(
            $serverParams['subject'],
            $updatedServer->server->subject,
            'Should have updated server subject.'
        );
        $this->assertEquals(
            $serverParams['priority'],
            $updatedServer->server->priority,
            'Should have updated server priority.'
        );
    }

    /**
     * Tests if the client can call and build the related servers endpoint with the correct ID
     *
     * @depends testCreateServer
     */
    public function testRelated($server)
    {
        $response = $this->client->servers($server->id)->related();

        $properties = [
            'url',
            'topic_id',
            'jira_issue_ids',
            'followup_source_ids',
            'from_archive',
            'incidents',
        ];
        foreach ($properties as $property) {
            $this->assertTrue(
                property_exists($response->server_related, $property),
                'Should have property '.$property
            );
        }
    }

    /**
     * Tests if the client can call and build the server collaborators endpoint with the correct ID
     *
     * @depends testCreateServer
     */
    public function testCollaborators($server)
    {
        $collaborators = $this->client->server()->collaborators(['id' => $server->id]);
        $this->assertTrue(property_exists($collaborators, 'users'), 'Should find the collaborators on a server.');
    }

    /**
     * Tests if the client can call and build the servers incidents endpoint with the correct ID
     */
    public function testIncidents()
    {
        $problemServer = $this->createTestServer(['type' => 'problem']);
        $incidentServer = $this->createTestServer(['type' => 'incident', 'problem_id' => $problemServer->id]);
        $incidents = $this->client->servers($problemServer->id)->incidents();
        $this->assertTrue(
            property_exists($incidents, 'servers'),
            'Should find the incident servers associated to a problem server.'
        );

        $this->assertNotNull($incident = $incidents->servers[0]);
        $this->assertEquals($incidentServer->id, $incident->id);
        $this->assertEquals($incidentServer->subject, $incident->subject);
    }

    /**
     * Tests if the client can call and build the delete servers endpoint
     * This will throw an exception if it fails
     *
     * @depends testCreateWithAttachment
     */
    public function testDelete($server)
    {
        $this->client->servers($server->id)->delete();
        $this->assertEquals(204, $this->client->getDebug()->lastResponseCode);
        $this->assertNull($this->client->getDebug()->lastResponseError);
    }

    /**
     * Tests if the client can call and build the delete many servers endpoint with the correct IDs
     *
     * @depends testCreateServer
     */
    public function testDeleteMultiple($server)
    {
        $server2 = $this->createTestServer();

        $response = $this->client->server()->deleteMany([$server->id, $server2->id]);

        $this->assertTrue(property_exists($response, 'job_status'));
        $this->assertEquals(
            'queued',
            $response->job_status->status,
            'Should have queued the multiple delete task'
        );
    }

    /**
     * Create a test server
     *
     * @param  array  $extraParams
     * @return mixed
     */
    private function createTestServer($extraParams = [])
    {
        $faker = Factory::create();
        $serverParams = array_merge([
            'subject' => $faker->sentence(5),
            'comment' => [
                'body' => $faker->sentence(10),
            ],
            'priority' => 'low',
        ], $extraParams);
        $response = $this->client->server()->create($serverParams);

        return $response->server;
    }

    /**
     * Test we can handle api exceptions, by finding a non-existing server
     *
     * @expectedException GridPane\API\Exceptions\ApiResponseException
     *
     * @expectedExceptionMessage Not Found
     */
    public function testHandlesApiException()
    {
        $this->client->server()->find(99999999);
    }

    /**
     * Test if a server with a group_id is assigned to the correct group.
     */
    public function testAssignServerToGroup()
    {
        $faker = Factory::create();
        $group = $this->client->groups()->create(['name' => $faker->word])->group;

        $server = $this->createTestServer([
            'group_id' => $group->id,
            'type' => 'problem',
            'tags' => ['testing', 'api'],
        ]);

        $this->assertEquals($group->id, $server->group_id);

        $this->client->groups()->delete($group->id);
        $this->client->server()->delete($server->id);
    }

    /**
     * Test if tags are updated on server updated.
     *
     * @throws \GridPane\API\Exceptions\MissingParametersException
     */
    public function testTagsAdded()
    {
        $faker = Factory::create();

        $tags = $faker->words(10);

        $server = $this->createTestServer();
        $this->client->servers($server->id)->tags()->update(null, $tags);

        $updatedServer = $this->client->server()->find($server->id);

        $this->assertEmpty(array_diff($tags, $updatedServer->server->tags), 'Tags should be updated.');

        $this->client->server()->delete($server->id);
    }
}
