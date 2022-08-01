<?php

namespace IPS\Integration\Ridivi\Exceptions;

class RidiviException extends \Exception
{

    private $option;

    public function __construct($option, $message, Exception $previous = null) {

        $this->option = $option;
        parent::__construct($message, 0, $previous);
    }

    public function getOption(){
        return $this->option;
    }

}