<?php

namespace Scpigo\SystemJob\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class SystemJobManagerFacade extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'systemJob';
    }
}