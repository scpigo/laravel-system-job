<?php

namespace Scpigo\SystemJob\Components\Interfaces;

use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

interface SystemJobSchedulerInterface {
    public function schedule(SystemJobSchedulerDto $dto);
}