<?php

class RidiviFacade extends IntegrationServiceFacade
{
    protected static function getFacadeAccessor() {
        return 'ridivi.'.parent::getFacadeAccessor();
    }
}