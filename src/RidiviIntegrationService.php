<?php

namespace IPS\Ridivi\Integration;

use IPS\Common\Services\BusinessPartnerService;

class RidiviIntegrationService extends BusinessPartnerService
{

    private $api_client;

    public function __construct()
    {
        parent::__construct("ridivi");
        //init api client
        $settings = self::getSettings();
    }

    public function apiClient()
    {
        return $this->api_client;
    }
}