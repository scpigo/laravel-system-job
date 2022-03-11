<?php

namespace Scpigo\SystemJob\Commands;

use Scpigo\SystemJob\Helpers\SystemJobStatus;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;

class PushJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'systemjob:push {--S|scheduler}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push system jobs in queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $schedulerName = $this->option('scheduler');

        if (!is_null($schedulerName)) {
            $schedulerName = config('systemjob.default');
        }

        try {
            $repository = app()->make('repo_'. $schedulerName);
        }
        catch (BindingResolutionException $e) {
            $this->error('This scheduler does not exist');
            return 0;
        }
        
        $jobs = $repository->findScheduledJobs();

        $this->push($jobs, $schedulerName);

        return 0;
    }

    private function push (object $jobs, string $schedulerName) {
        foreach ($jobs as $job) {
            $event_id = null;

            $task = app()->makeWith($job->action, [
                'jobId' => $job->id,
                'jobParams' => json_decode($job->action_params),
                'Driver' => $schedulerName
            ]);

            $task->onConnection(config('systemjob.schedulers.' . $schedulerName . 'queue_connection'));

            if ($task instanceof ShouldQueue) {
                $event_id = app(Dispatcher::class)->dispatch($task);
            }

            if ($event_id) {
                $jobUpdate = config('systemjob.schedulers.' . $schedulerName . '.model');
                $jobUpdate::where('id', $job->id)
                    ->update([
                        'event_id' => $event_id,
                        'status' => SystemJobStatus::PUSH
                    ]);
                
                $this->info('Job '.$job->id.': '.$job->action.' was pushed! ['.$event_id.']['.$task->getDriver().']');
            }
        }
    }
}
