<?php

namespace Gridpane\Api\Resources\Core;

use Gridpane\Api\Exceptions\ResponseException;
use Gridpane\Api\Resources\ResourceAbstract;
use Gridpane\Api\Traits\Resource\Defaults;
use Gridpane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Tickets class exposes key methods for reading and updating ticket data
 *
 * @method Server server()
 */
class Server extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
        update as traitUpdate;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
        ];
    }

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();
    }

    /**
     * Create a ticket
     *
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \Gridpane\Api\Exceptions\AuthException
     * @throws \Gridpane\Api\Exceptions\ApiResponseException
     */
    public function create(array $params)
    {
        $extraOptions = [];
        if (isset($params['async']) && ($params['async'] == true)) {
            $extraOptions = [
                'queryParams' => [
                    'async' => true,
                ],
            ];
        }

        $route = $this->getRoute(__FUNCTION__, $params);

        return $this->client->post(
            $route,
            [$this->objectName => $params],
            $extraOptions
        );
    }

    /**
     * Update a ticket or series of tickets
     *
     * @param  int  $id
     * @param  array  $updateResourceFields
     * @return null|\stdClass
     */
    public function update($id = null, array $updateResourceFields = [])
    {
        return $this->traitUpdate($id, $updateResourceFields);
    }
}
