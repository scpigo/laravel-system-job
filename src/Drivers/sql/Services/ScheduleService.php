<?php

namespace Scpigo\SystemJob\Drivers\Sql\Services;

use Scpigo\SystemJob\Helpers\SystemJobStatus;
use Scpigo\SystemJob\Services\Interfaces\ScheduleInterface;
use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

class ScheduleService implements ScheduleInterface {
    public function schedule(SystemJobSchedulerDto $dto)
    {
        $model = config('systemjob.schedulers.sql.model');

        $systemJob = $model::create([
            'action' => $dto->action,
            'action_params' => $dto->action_params,
            'scheduled_at' => $dto->scheduled_at,
            'status' => SystemJobStatus::SCHEDULED
        ]);

        if ($systemJob) {
            return $systemJob->id;
        }

        return false;
    }
}