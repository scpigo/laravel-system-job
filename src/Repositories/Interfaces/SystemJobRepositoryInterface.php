<?php

namespace Scpigo\SystemJob\Repositories\Interfaces;

use Scpigo\SystemJob\Requests\SystemJobRequest;

interface SystemJobRepositoryInterface
{
    public function findScheduledJobs(): object;
}
