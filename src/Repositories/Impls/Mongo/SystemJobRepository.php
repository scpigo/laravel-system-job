<?php

namespace Scpigo\SystemJob\Repositories\Impls\Mongo;

use Scpigo\SystemJob\Repositories\Interfaces\SystemJobRepositoryInterface;
use Scpigo\SystemJob\Drivers\Mongo\Models\SystemJob;
use Scpigo\SystemJob\Helpers\SystemJobStatus;
use Illuminate\Support\Carbon;

class SystemJobRepository implements SystemJobRepositoryInterface
{
    public function findScheduledJobs(): object {
        $jobs = SystemJob::where('status', 'SCHEDULED')
            ->where('scheduled_at', '<', Carbon::createFromFormat('Y-m-d H:i:s', gmdate(now())))
            ->orderBy('id')
            ->get();
        
        return $jobs;
    }
}
