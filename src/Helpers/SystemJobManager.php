<?php
namespace Scpigo\SystemJob\Helpers;

class SystemJobManager {
    public function scheduler($name = null) {
        if (is_null($name)) $name = config('systemjob.default');
        return app()->make(config('systemjob.schedulers.' . $name . '.component'));
    }
}