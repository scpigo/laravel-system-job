<?php
namespace Scpigo\SystemJob\Facades;

use Scpigo\SystemJob\Components\Interfaces\SystemJobSchedulerInterface;

class SystemJobManager {
    public function scheduler($name = null) {
        if (is_null($name)) $name = config('systemjob.default');

        return app()->makeWith(SystemJobSchedulerInterface::class, [
            'service' => app()->make($name)
        ]);
    }
}