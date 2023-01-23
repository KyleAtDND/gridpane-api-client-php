<?php

namespace KyleWLawrence\GridPane\Api\Resources\Core;

use KyleWLawrence\GridPane\API\Exceptions\MissingParametersException;
use KyleWLawrence\GridPane\Api\Exceptions\ResponseException;
use KyleWLawrence\GridPane\Api\Resources\ResourceAbstract;
use KyleWLawrence\GridPane\Api\Traits\Resource\Defaults;
use KyleWLawrence\GridPane\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Backups class exposes key methods for reading and updating backups
 *
 * @method Backups backups()
 */
class Backups extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
        get as traitGet;
        getAll as traitGetAll;
        update as traitUpdate;
        delete as traitDelete;
    }

    /**
     *  Mandatory create backup keys
     */
    public static $create_params = [
        'type',
    ];

    /**
     *  Mandatory refresh/check alternative keys
     */
    public static $check_params = [
        'alternative_server_id',
        'integration_id',
    ];

    /**
     *  Mandatory refresh/check alternative keys
     */
    public static $restore_params = [
        'backup_source',
        'source_type',
        'backup',
        'backup_type',
    ];

    /**
     *  Mandatory keys for toggling automatic backups
     */
    public static $automatic_params = [
        'type',
        'enable',
    ];

    /**
     *  Mandatory keys for toggling automatic backups
     */
    public static $update_schedule_params = [
        'id',
        'bup_schedule',
        'minute',
    ];

    /**
     *  Purge site backup params
     */
    public static $purge_params = [
        'backup',
        'backup_type',
        'backup_source',
    ];

    /**
     *  Purge site backup params
     */
    public static $purge_range_params = [
        'from_backup',
        'to_backup',
        'backup_source',
    ];

    /**
     *  Purge site backup params
     */
    public static $prune_schedule_params = [
        'backup_type',
        'retain_days',
        'prune_schedule',
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
            'refreshSiteAvailable' => 'backups/refresh/{id}',
            'getOriginalSource' => 'backups/original/{id}',
            'getAlternativeSources' => 'backups/alternative-source/{id}',
            'getAlternatives' => 'backups/alternative/{id}',
            'getIntegrations' => 'backups/integrations',
            'getSiteIntegrations' => 'backups/integrations/{id}',
            'getSchedules' => 'backups/schedules',
            'getServerSchedules' => 'backups/schedules/server/{id}',
            'getSiteSchedules' => 'backups/schedules/site/{id}',
            'getPruneSchedule' => 'backups/prune-schedule/{id}',
            'addIntegration' => 'backups/integrations/{id}',
            'create' => 'backups/{id}',
            'checkAlternatives' => 'backups/refresh-alternatives/{id}',
            'restore' => 'backups/restore/{id}',
            'updateAutomatic' => 'backups/automatic/{id}',
            'updateSchedule' => 'backups/schedule/site/{id}',
            'purge' => 'backups/purge/{id}',
            'purgeRange' => 'backups/purge-range/{id}',
            'updatePruneSchedule' => 'backups/prune-schedule/{id}',
            'removeIntegration' => 'backups/integrations/{id}',
        ]);
    }

    /**
     * Refresh site available backups
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function refreshSiteAvaialble(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Get original source available backups
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getOriginalSource(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Get alternative backups sources
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getAlternativeSources(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Get alternative source available backups
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getAlternatives(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Get alternative source available backups
     *
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getIntegrations(array $params)
    {
        return $this->traitGetAll($params, [], __FUNCTION__);
    }

    /**
     * Get site backup integrations
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getSiteIntegrations(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Get all sites backups schedules
     *
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getSchedules(array $params)
    {
        return $this->traitGetAll($params, [], __FUNCTION__);
    }

    /**
     * Get all sites on server backup schedules
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getServerSchedules(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Get site backup schedules
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getSiteSchedules(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Get site backups prune schedule
     *
     * @param  int  $id
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function getPruneSchedule(int $id)
    {
        return $this->traitGet($id, [], __FUNCTION__);
    }

    /**
     * Add remote backup integration to site
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
    public function addIntegration(int $id, array $params)
    {
        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        } elseif (! $this->hasKeys($params, ['integration_id'])) {
            throw new MissingParametersException(__METHOD__, ['integration_id']);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $id]);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Create a new backup
     *
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GridPane\Api\Exceptions\AuthException
     * @throws \GridPane\Api\Exceptions\ApiResponseException
     */
    public function create(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$create_params)) {
            throw new MissingParametersException(__METHOD__, self::$create_params);
        } elseif (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        } elseif ($params['type'] === 'remote' && ! $this->hasKeys($params, ['integration_id'])) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $id]);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Refresh/check available alternative backups
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
    public function checkAlternatives(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$check_params)) {
            throw new MissingParametersException(__METHOD__, self::$check_params);
        } elseif (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $id]);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Restore a backup
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
    public function restore(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$restore_params)) {
            throw new MissingParametersException(__METHOD__, self::$restore_params);
        } elseif (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $id]);

        return $this->client->post(
            $route,
            $params
        );
    }

    /**
     * Enable/Disable automatic backups for a site
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
    public function updateAutomatic(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$automatic_params)) {
            throw new MissingParametersException(__METHOD__, self::$automatic_params);
        }

        $this->traitUpdate($id, $params, __FUNCTION__);
    }

    /**
     * Update site backup schedule
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
    public function updateSchedule(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$update_schedule_params)) {
            throw new MissingParametersException(__METHOD__, self::$update_schedule_params);
        } elseif (in_array($params['bup_schedule'], ['daily', 'weekly', 'hourly'])) {
            throw new MissingParametersException(__METHOD__, ['hour']);
        } elseif (in_array($params['bup_schedule'], ['weekly', 'hourly'])) {
            throw new MissingParametersException(__METHOD__, ['day']);
        }

        $this->traitUpdate($id, $params, __FUNCTION__);
    }

    /**
     * Purge a site backup
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
    public function purge(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$purge_params)) {
            throw new MissingParametersException(__METHOD__, self::$purge_params);
        }

        $this->traitUpdate($id, $params, __FUNCTION__);
    }

    /**
     * Purge a range of site backups
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
    public function purgeRange(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$purge_range_params)) {
            throw new MissingParametersException(__METHOD__, self::$purge_range_params);
        }

        $this->traitUpdate($id, $params, __FUNCTION__);
    }

    /**
     * Update site backups prune schedule
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
    public function updatePruneSchedule(int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$prune_schedule_params)) {
            throw new MissingParametersException(__METHOD__, self::$prune_schedule_params);
        }

        $this->traitUpdate($id, $params, __FUNCTION__);
    }

    /**
     * Remove remote backup integration from site
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
    public function removeIntegration(int $id, array $params)
    {
        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        } elseif (! $this->hasKeys($params, ['integrations_id'])) {
            throw new MissingParametersException(__METHOD__, ['integrations_id']);
        }

        $route = $this->getRoute('delete', ['id' => $id]);

        return $this->client->delete($route, $params);
    }
}
