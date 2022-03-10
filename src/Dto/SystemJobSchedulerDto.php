<?php

namespace Scpigo\SystemJob\Dto;

use Illuminate\Support\Carbon;

class SystemJobSchedulerDto {
    public string $action;
    public string $action_params;
    public string $scheduled_at;
}