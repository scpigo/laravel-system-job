<?php

namespace Scpigo\SystemJob\Repositories\Interfaces;

use Scpigo\SystemJob\Requests\SystemJobRequest;

interface SystemJobRepositoryInterface
{
    public function findModelsByFilter(SystemJobRequest $request);
    public function findIds(SystemJobRequest $request);
}
