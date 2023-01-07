<?php

namespace GridPane\API\Traits\Resource;

use GridPane\API\Exceptions\MissingParametersException;
use GridPane\API\Exceptions\RouteException;

trait Delete
{
    /**
     * Delete a resource
     *
     * @param  int  $id
     * @param  string  $routeKey
     * @return bool
     *
     * @throws MissingParametersException
     */
    public function delete($id = null, $routeKey = __FUNCTION__)
    {
        if (empty($id)) {
            $chainedParameters = $this->getChainedParameters();
            if (array_key_exists(get_class($this), $chainedParameters)) {
                $id = $chainedParameters[get_class($this)];
            }
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

        return $this->client->delete($route);
    }
}
