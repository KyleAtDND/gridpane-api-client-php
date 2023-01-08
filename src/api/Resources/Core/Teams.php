<?php

namespace GridPane\Api\Resources\Core;

use GridPane\Api\Resources\ResourceAbstract;
use GridPane\Api\Traits\Resource\GetAll;
use GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Teams class exposes key methods for reading and updating a server
 *
 * @method Teams teams()
 */
class Teams extends ResourceAbstract
{
    use InstantiatorTrait;
    use GetAll;

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
            'getAll' => 'settings/teams',
            'getCurrent' => 'settings/teams/current',
        ]);
    }

    /**
     * Get the current team
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
