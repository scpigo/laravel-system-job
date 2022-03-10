<?php

namespace Scpigo\SystemJob\Components\Impls\sql;

use Scpigo\SystemJob\Components\Interfaces\SchedulerInterface;
use Scpigo\SystemJob\Drivers\sql\Services\ScheduleService;
use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

class SystemJobScheduler implements SchedulerInterface {
    public function schedule(SystemJobSchedulerDto $dto) {
        $service = new ScheduleService();
        return $service->schedule($dto);
    }
}