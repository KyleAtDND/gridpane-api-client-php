<?php

namespace GridPane\Api\Resources\Core;

use GridPane\API\Exceptions\MissingParametersException;
use GridPane\Api\Exceptions\ResponseException;
use GridPane\Api\Resources\ResourceAbstract;
use GridPane\Api\Traits\Resource\Defaults;
use GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The SystemUser class exposes key methods for reading and updating a server
 *
 * @method SystemUser systemUser()
 */
class SystemUser extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
    }

    /**
     *  Mandatory create server keys
     */
    public static $create_params = [
        'username',
        'password',
        'password_confirmation',
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
    }

    /**
     * Create a server
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
}
