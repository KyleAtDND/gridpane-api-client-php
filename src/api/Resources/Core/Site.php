<?php

namespace GridPane\Api\Resources\Core;

use GridPane\API\Exceptions\MissingParametersException;
use GridPane\Api\Exceptions\ResponseException;
use GridPane\Api\Resources\ResourceAbstract;
use GridPane\Api\Traits\Resource\Defaults;
use GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Site class exposes key methods for reading, updating and deleting a site
 *
 * @method Site site()
 */
class Site extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
        delete as traitDelete;
    }

    /**
     *  Mandatory create site keys
     */
    public static $create_params = [
        'url',
        'server_id',
        'php_version',
        'pm',
        'system_user_id',
        'waf',
        // 'nginx_caching',
    ];

    /**
     *  Mandatory create wp user on site keys
     */
    public static $create_wp_user_params = [
        'username',
        'email',
        'password',
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
            'delete' => 'site/{id}',
            'runCLICommand' => 'site/run-wp-cli/{id}',
            'addWPUser' => 'site/add-wp-user/{id}',
        ]);
    }

    /**
     * Deletes a site, include the serverId to avoid mistakes
     *
     * @param  int  $siteId
     * @param  int  $serverId
     * @return \stdClass | null
     *
     * @throws MissingParametersException
     * @throws ResponseException
     * @throws \Exception
     */
    public function delete(int $siteId = null, int $serverId = null)
    {
        if (empty($siteId)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        $params = (empty($serverId)) ? [] : ['site_id' => $siteId, 'server_id' => $serverId];

        $route = $this->getRoute('delete', ['id' => $siteId]);

        return $this->client->delete($route, $params);
    }

    /**
     * Create a site
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
     * Create a WP User
     *
     * @param  int  $siteId
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function addWPUser(int $siteId, array $params)
    {
        if (empty($siteId)) {
            throw new MissingParametersException(__METHOD__, ['siteId']);
        } elseif (! $this->hasKeys($params, self::$create_params)) {
            throw new MissingParametersException(__METHOD__, self::$create_wp_user_params);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $siteId]);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Run WP-CLI Commands on a Site
     *
     * @param  int  $siteId
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function runCLICommand(int $siteId, array $params)
    {
        if (empty($siteId)) {
            throw new MissingParametersException(__METHOD__, ['siteId']);
        } elseif (! isset($params['wp']) && empty($params['wp'])) {
            throw new MissingParametersException(__METHOD__, ['wp']);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $siteId]);

        return $this->client->put(
            $route,
            $params
        );
    }
}
