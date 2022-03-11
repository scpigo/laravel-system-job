<?php

namespace Scpigo\SystemJob\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SystemJobAbstract implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobId;
    protected $jobParams;
    protected $Driver;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($jobId, $jobParams, $Driver)
    {
        $this->jobId = $jobId;
        $this->jobParams = $jobParams;
        $this->Driver = $Driver;
    }

    public function getDriver() {
        return $this->Driver;
    }
}