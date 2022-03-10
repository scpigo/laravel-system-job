<?php

namespace Scpigo\SystemJob\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

interface ScheduleInterface {
    public function schedule(SystemJobSchedulerDto $dto);
}