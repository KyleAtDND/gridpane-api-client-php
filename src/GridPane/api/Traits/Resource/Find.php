<?php

namespace GridPane\Api\Traits\Resource;

use GridPane\Api\Exceptions\MissingParametersException;
use GridPane\Api\Exceptions\RouteException;

trait Find
{
    /**
     * Find a specific ticket by id or series of ids
     *
     * @param    $id
     * @param  array  $queryParams
     * @param  string  $routeKey
     * @return null|\stdClass
     *
     * @throws MissingParametersException
     */
    public function find($id = null, array $queryParams = [], $routeKey = __FUNCTION__)
    {
        if (empty($id)) {
            $id = $this->getChainedParameter(get_class($this));
        }

        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        try {
            $route = $this->getRoute($routeKey, ['id' => $id]);
        } catch (RouteException $e) {
            if (! isset($this->resourceName)) {
                $this->resourceName = $this->getResourceNameFromClass();
            }

            $this->setRoute(__FUNCTION__, $this->resourceName.'/{id}');
            $route = $this->resourceName.'/'.$id.'';
        }

        return $this->client->get(
            $route,
            $queryParams
        );
    }
}
