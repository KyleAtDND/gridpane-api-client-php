<?php

namespace GridPane\Api\Resources\Core;

use GridPane\API\Exceptions\MissingParametersException;
use GridPane\Api\Exceptions\ResponseException;
use GridPane\Api\Resources\ResourceAbstract;
use GridPane\Api\Traits\Resource\Defaults;
use GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Domain class exposes key methods for reading and updating a server
 *
 * @method Domain domain()
 */
class Domain extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
    }

    /**
     *  Mandatory create domain keys
     */
    public static $create_params = [
        'domain_url',
        'site_id',
        'server_id',
        'type',
        'dns_management',
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
            'getAll' => 'domain',
            'get' => 'domain/{id}',
            'create' => 'domain',
            'update' => 'domain/{id}',
            'delete' => 'domain/{id}',
        ]);
    }

    /**
     * Create a domain
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
        } elseif ($params['dns_management'] === 'none_none' && ! isset($params['dns_integration_id'])) {
            throw new MissingParametersException(__METHOD__, ['dns_integration_id']);
        }

        $route = $this->getRoute(__FUNCTION__);

        return $this->client->post(
            $route,
            $params
        );
    }
}
