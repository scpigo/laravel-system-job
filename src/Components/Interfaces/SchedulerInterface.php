<?php

namespace Scpigo\SystemJob\Components\Interfaces;

use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

interface SchedulerInterface {
    public function schedule(SystemJobSchedulerDto $dto);
}