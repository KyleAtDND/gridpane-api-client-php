<?php

namespace GridPane\Api\Traits\Resource;

use GridPane\Api\Exceptions\RouteException;

trait FindAll
{
    /**
     * List all of this resource
     *
     * @param  array  $params
     * @param  string  $routeKey
     * @return \stdClass | null
     *
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function findAll(array $params = [], $routeKey = __FUNCTION__)
    {
        try {
            $route = $this->getRoute($routeKey, $params);
        } catch (RouteException $e) {
            if (! isset($this->resourceName)) {
                $this->resourceName = $this->getResourceNameFromClass();
            }

            $route = $this->resourceName.'';
            $this->setRoute(__FUNCTION__, $route);
        }

        return $this->client->get(
            $route,
            $params
        );
    }
}
