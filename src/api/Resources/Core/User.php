<?php

namespace GridPane\Api\Resources\Core;

use GridPane\API\Exceptions\MissingParametersException;
use GridPane\Api\Exceptions\ResponseException;
use GridPane\Api\Resources\ResourceAbstract;
use GridPane\Api\Traits\Resource\Create;
use GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The User class exposes key methods for reading or creating a user
 *
 * @method User user()
 */
class User extends ResourceAbstract
{
    use InstantiatorTrait;
    use Create;

    /**
     *  Mandatory create user keys
     */
    public static $create_params = [
        'name',
        'email',
        'teamId',
        'role',
    ];

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

        $this->setRoutes([
            'getCurrentIntegrations' => 'user/integrations',
            'getCurrent' => 'user',
            'create' => 'user',
        ]);
    }

    /**
     * Get integrations available to user
     *
     * @return \stdClass | null
     *
     * @throws MissingParametersException
     * @throws ResponseException
     * @throws \Exception
     */
    public function getCurrentIntegrations()
    {
        $route = $this->getRoute(__FUNCTION__);

        return $this->client->get(
            $route
        );
    }

    /**
     * Create a User
     *
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function create(array $params)
    {
        if (! $this->hasKeys($params, self::$create_params)) {
            throw new MissingParametersException(__METHOD__, self::$create_params);
        }

        $route = $this->getRoute(__FUNCTION__);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Get the current user
     *
     * @return null|\stdClass
     */
    public function getCurrent()
    {
        $route = $this->getRoute(__FUNCTION__);

        return $this->client->get(
            $route
        );
    }
}
