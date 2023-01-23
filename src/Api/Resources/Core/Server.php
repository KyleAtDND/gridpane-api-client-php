<?php

namespace KyleWLawrence\GridPane\Api\Resources\Core;

use KyleWLawrence\GridPane\API\Exceptions\InvalidParametersException;
use KyleWLawrence\GridPane\API\Exceptions\MissingParametersException;
use KyleWLawrence\GridPane\Api\Exceptions\ResponseException;
use KyleWLawrence\GridPane\Api\Resources\ResourceAbstract;
use KyleWLawrence\GridPane\Api\Traits\Resource\Defaults;
use KyleWLawrence\GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Server class exposes key methods for reading and updating a server
 *
 * @method Server server()
 */
class Server extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
    }

    /**
     * Valid server providers
     */
    public static $providers = [
        'vultr',
        'linode',
        'aws-lightsail',
        'digitalocean',
        'upcloud',
    ];

    /**
     *  Mandatory create server keys
     */
    public static $create_params = [
        'servername',
        'ip',
        'datacenter',
        'webserver',
        'database',
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
            'getAll' => 'server',
            'get' => 'server/{id}',
            'getPlans' => 'server/{id}/plans',
            'getProgress' => 'server/build-progress/{id}',
            'clone' => 'server/{id}',
            'update' => 'server/{id}',
            'create' => 'server/{id}',
            'delete' => 'server/{id}',
        ]);
    }

    /**
     * Show build progress on server
     *
     * @param  int  $provider
     * @return \stdClass | null
     *
     * @throws MissingParametersException
     * @throws ResponseException
     * @throws \Exception
     */
    public function getProgress(int $id = null)
    {
        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        return $this->get($id, [], __FUNCTION__);
    }

    /**
     * List plans from provider
     *
     * @param  string  $provider
     * @return \stdClass | null
     *
     * @throws MissingParametersException
     * @throws ResponseException
     * @throws \Exception
     */
    public function getPlans(string $provider = null)
    {
        if (empty($provider)) {
            throw new MissingParametersException(__METHOD__, ['provider']);
        } elseif (! in_array($provider, self::$providers)) {
            throw new InvalidParametersException(__METHOD__, ['provider'], self::$providers);
        }

        return $this->get($provider, [], __FUNCTION__);
    }

    /**
     * Create a server
     *
     * @param  string  $provider
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function create(string $provider, array $params)
    {
        $providerList = array_merge(self::$providers, ['custom']);
        if (empty($provider)) {
            throw new MissingParametersException(__METHOD__, ['provider']);
        } elseif (! in_array($provider, $providerList)) {
            throw new InvalidParametersException(__METHOD__, ['provider'], $providerList);
        } elseif (! $this->hasKeys($params, self::$create_params)) {
            throw new MissingParametersException(__METHOD__, self::$create_params);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $provider]);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Clone all sites on one server to another
     *
     * @param  int  $id
     * @param  array  $params
     * @return null|\stdClass
     */
    public function clone($id = null, array $params = [])
    {
        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        } elseif (! $this->hasKeys($params, ['clone_to_server'])) {
            throw new MissingParametersException(__METHOD__, ['clone_to_server']);
        }

        return $this->update($id, $params);
    }
}
