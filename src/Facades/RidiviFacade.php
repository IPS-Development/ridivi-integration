<?php

namespace IPS\Integration\Ridivi\Facades;

use IPS\Common\Facaces\IntegrationServiceFacade;

class RidiviFacade extends IntegrationServiceFacade
{
    protected static function getFacadeAccessor() {
        return 'ridivi.'.parent::getFacadeAccessor();
    }
}