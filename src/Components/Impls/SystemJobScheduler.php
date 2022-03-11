<?php

namespace Scpigo\SystemJob\Components\Impls;

use Scpigo\SystemJob\Components\Interfaces\SystemJobSchedulerInterface;
use Scpigo\SystemJob\Services\Interfaces\ScheduleInterface;
use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

class SystemJobScheduler implements SystemJobSchedulerInterface {
    private $service;

    public function __construct(ScheduleInterface $service)
    {
        $this->service = $service;
    }
    public function schedule(SystemJobSchedulerDto $dto) {
        return $this->service->schedule($dto);
    }
}