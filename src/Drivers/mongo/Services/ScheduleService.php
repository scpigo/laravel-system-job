<?php

namespace Scpigo\SystemJob\Drivers\Mongo\Services;

use Scpigo\SystemJob\Helpers\SystemJobStatus;
use Scpigo\SystemJob\Services\Interfaces\ScheduleInterface;
use Scpigo\SystemJob\Dto\SystemJobSchedulerDto;

class ScheduleService implements ScheduleInterface {
    public function schedule(SystemJobSchedulerDto $dto)
    {
        $model = config('systemjob.schedulers.mongo.model');
        
        $systemJob = new $model;

        $systemJob->nextid();
        
        $systemJob->action = $dto->action;
        $systemJob->action_params = $dto->action_params;
        $systemJob->scheduled_at = $dto->scheduled_at;
        $systemJob->status = SystemJobStatus::SCHEDULED;

        $systemJob->save();

        if ($systemJob) {
            return $systemJob->id;
        }

        return false;
    }
}