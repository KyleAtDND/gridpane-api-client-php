<?php

namespace GridPane\Api\Resources\Core;

use GridPane\API\Exceptions\MissingParametersException;
use GridPane\Api\Exceptions\ResponseException;
use GridPane\Api\Resources\ResourceAbstract;
use GridPane\Api\Traits\Resource\Defaults;
use GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Bundle class exposes key methods for reading and updating a bundle
 *
 * @method Bundle bundle()
 */
class Bundle extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
    }

    /**
     *  Mandatory create bundle keys
     */
    public static $create_params = [
        'bundle_name',
        'plugins',
        'themes',
    ];

    /**
     *  Mandatory add to bundle keys
     */
    public static $add_params = [
        'name',
        'is_active',
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
            'addTheme' => 'bundle/{id}/theme',
            'addPlugin' => 'bundle/{id}/plugin',
            'removeTheme' => 'bundle/{id}/theme/{subjectId}',
            'removePlugin' => 'bundle/{id}/plugin/{subjectId}',
            'updateTheme' => 'bundle/{id}/theme/{subjectId}',
            'updatePlugin' => 'bundle/{id}/plugin/{subjectId}',
        ]);
    }

    /**
     * Add a plugin to a bundle
     *
     * @param  int  $id
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function addPlugin(int $id, array $params)
    {
        return $this->addPluginOrTheme($id, $params, __FUNCTION__);
    }

    /**
     * Add a theme to a bundle
     *
     * @param  int  $id
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function addTheme(int $id, array $params)
    {
        return $this->addPluginOrTheme($id, $params, __FUNCTION__);
    }

    /**
     * Add a plugin or theme to a bundle
     *
     * @param  int  $id
     * @param  array  $params
     * @param  string  $route
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    protected function addPluginOrTheme(int $id, array $params, string $route)
    {
        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        } elseif (! $this->hasKeys($params, self::$add_params)) {
            throw new MissingParametersException(__METHOD__, self::$add_params);
        }

        $route = $this->getRoute($route, ['id' => $id]);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Remove a plugin from a bundle
     *
     * @param  int  $bundleId
     * @param  int  $pluginId
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function removePlugin(int $bundleId, int $pluginId)
    {
        return $this->removePluginOrTheme($bundleId, $pluginId, __FUNCTION__);
    }

    /**
     * Remove a theme from a bundle
     *
     * @param  int  $bundleId
     * @param  int  $themeId
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function removeTheme(int $bundleId, int $themeId)
    {
        return $this->removePluginOrTheme($bundleId, $themeId, __FUNCTION__);
    }

    /**
     * Remove a plugin or theme from a bundle
     *
     * @param  int  $bundleId
     * @param  int  $pluginId
     * @param  string  $route
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    protected function removePluginOrTheme(int $bundleId, int $pluginOrThemeId, string $route)
    {
        if (empty($bundleId)) {
            throw new MissingParametersException(__METHOD__, ['bundleId']);
        } elseif (empty($pluginOrThemeId)) {
            throw new MissingParametersException(__METHOD__, ['pluginOrThemeId']);
        }

        $route = $this->getRoute($route, ['bundleId' => $bundleId, 'id' => $pluginOrThemeId]);

        return $this->client->delete(
            $route
        );
    }

    /**
     * Update a plugin on a bundle
     *
     * @param  int  $bundleId
     * @param  int  $pluginId
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function updatePlugin(int $bundleId, int $pluginId, array $params)
    {
        return $this->updatePluginOrTheme($bundleId, $pluginId, $params, __FUNCTION__);
    }

    /**
     * Update a theme on a bundle
     *
     * @param  int  $bundleId
     * @param  int  $pluginId
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function updateTheme(int $bundleId, int $themeId, array $params)
    {
        return $this->updatePluginOrTheme($bundleId, $themeId, $params, __FUNCTION__);
    }

    /**
     * Update a plugin or theme on a bundle
     *
     * @param  int  $bundleId
     * @param  int  $pluginOrThemeId
     * @param  array  $updateResourceFields
     * @param  string  $route
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    protected function updatePluginOrTheme(int $bundleId, int $pluginOrThemeId, array $updateResourceFields, string $route)
    {
        if (empty($bundleId)) {
            throw new MissingParametersException(__METHOD__, ['bundleId']);
        } elseif (empty($pluginOrThemeId)) {
            throw new MissingParametersException(__METHOD__, ['pluginOrThemeId']);
        }

        $this->setAddtionalRouteParams(['subjectId' => $pluginOrThemeId]);

        $this->update($bundleId, $updateResourceFields, $route);
    }

    /**
     * Create a bundle
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
