<?php

namespace IPS\Integration\Ridivi\Facades;


use IPS\Common\Facades\IntegrationServiceFacade;

class RidiviFacade extends IntegrationServiceFacade
{
    protected static function getFacadeAccessor() {
        return 'ridivi.'.parent::getFacadeAccessor();
    }
}