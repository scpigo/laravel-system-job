<?php

namespace Scpigo\SystemJob\Components\Impls\mongo;

use Scpigo\SystemJob\Components\Interfaces\SchedulerInterface;
use Scpigo\SystemJob\Drivers\mongo\Services\ScheduleService;
use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

class SystemJobScheduler implements SchedulerInterface {
    public function schedule(SystemJobSchedulerDto $dto) {
        $service = new ScheduleService();
        return $service->schedule($dto);
    }
}