<?php

namespace Scpigo\SystemJob\Repositories\Implems;

use Scpigo\SystemJob\Repositories\Interfaces\SystemJobRepositoryInterface;
use Scpigo\SystemJob\Models\SystemJob;
use Scpigo\SystemJob\Requests\SystemJobRequest;

class SystemJobRepository implements SystemJobRepositoryInterface
{
    public function findModelsByFilter(SystemJobRequest $request) {
        return SystemJob::action($request->action)
            ->attempt($request->attempt)
            ->status($request->status)
            ->get();
    }

    public function findIds(SystemJobRequest $request) {
        return SystemJob::select('id')
            ->action($request->action)
            ->attempt($request->attempt)
            ->status($request->status)
            ->pluck('id');
    }
}
