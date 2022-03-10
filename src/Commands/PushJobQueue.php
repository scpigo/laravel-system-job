<?php

namespace Scpigo\SystemJob\Commands;

use Scpigo\SystemJob\Helpers\SystemJobStatus;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;

class PushJobQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'systemjob:push {--S|scheduler=all}';

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
        $scheduler = $this->option('scheduler');

        if ($scheduler == 'all') {
            foreach (config('systemjob.schedulers') as $key => $value) {
                $jobs = Arr::get($value, 'model');
        
                $jobs = $jobs::where('status', SystemJobStatus::SCHEDULED)
                    ->where('scheduled_at', '<', gmdate('Y-m-d H:i:s'))
                    ->orderBy('id')
                    ->get();

                $this->push($jobs, $key);

                return 0;
            }
        }

        $jobs = config('systemjob.schedulers.' . $scheduler . '.model');
    
        if (!is_null($jobs)) {
            $jobs = $jobs::where('status', SystemJobStatus::SCHEDULED)
                ->where('scheduled_at', '<', gmdate('Y-m-d H:i:s'))
                ->orderBy('id')
                ->get();
            
            var_dump($jobs); exit;

            $this->push($jobs, $scheduler);
        } 
        else {
            $this->error('This scheduler does not exist');
        }

        return 0;
    }

    private function push ($jobs, $scheduler) {
        foreach ($jobs as $job) {
            $event_id = null;

            $task = app()->makeWith($job->action, [
                'jobId' => $job->id,
                'jobParams' => json_decode($job->action_params)
            ]);

            $task->onConnection(config('systemjob.schedulers.' . $scheduler . 'queue_connection'));

            if ($task instanceof ShouldQueue) {
                $event_id = app(Dispatcher::class)->dispatch($task);
            }

            if ($event_id) {
                $jobUpdate = config('systemjob.schedulers.' . $scheduler . '.model');
                $jobUpdate::where('id', $job->id)
                    ->update([
                        'event_id' => $event_id,
                        'status' => SystemJobStatus::PUSH
                    ]);
                
                $this->info('Job '.$job->id.': '.$job->action.' was pushed! ['.$event_id.']');
            }
        }
    }
}
