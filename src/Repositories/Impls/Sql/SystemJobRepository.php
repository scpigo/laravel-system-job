<?php

namespace Scpigo\SystemJob\Repositories\Impls\Sql;

use Scpigo\SystemJob\Repositories\Interfaces\SystemJobRepositoryInterface;
use Scpigo\SystemJob\Drivers\Sql\Models\SystemJob;
use Scpigo\SystemJob\Helpers\SystemJobStatus;

class SystemJobRepository implements SystemJobRepositoryInterface
{
    public function findScheduledJobs(): object {
        $jobs = SystemJob::where('status', SystemJobStatus::SCHEDULED)
            ->where('scheduled_at', '<', gmdate('Y-m-d H:i:s'))
            ->orderBy('id')
            ->get();
        
        return $jobs;
    }
}
