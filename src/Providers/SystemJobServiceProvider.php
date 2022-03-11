<?php 

namespace Scpigo\SystemJob\Providers;

use Scpigo\SystemJob\Commands\PushJobs;
use Scpigo\SystemJob\Helpers\SystemJobStatus;
use Scpigo\SystemJob\Repositories\Interfaces\SystemJobRepositoryInterface;
use Scpigo\SystemJob\Repositories\Impls\Mongo\SystemJobRepository as MongoSystemJobRepository;
use Scpigo\SystemJob\Repositories\Impls\Sql\SystemJobRepository as SqlSystemJobRepository;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Scpigo\SystemJob\Components\Impls\SystemJobScheduler;
use Scpigo\SystemJob\Components\Interfaces\SystemJobSchedulerInterface;
use Scpigo\SystemJob\Drivers\Mongo\Services\ScheduleService as ServicesScheduleService;
use Scpigo\SystemJob\Drivers\Sql\Services\ScheduleService;
use Scpigo\SystemJob\Facades\SystemJobManager;
use Illuminate\Support\Carbon;

class SystemJobServiceProvider extends \Illuminate\Support\ServiceProvider { 
    public $bindings = [
        SystemJobSchedulerInterface::class => SystemJobScheduler::class,
        SystemJobRepositoryInterface::class => SystemJobRepository::class,
    ];

    public function boot() { 
        if(is_dir(__DIR__.'/../Drivers/Sql/Migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../Drivers/Sql/Migrations');
        }

        if(is_dir(__DIR__.'/../Drivers/Mongo/Migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../Drivers/Mongo/Migrations');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                PushJobs::class,
            ]);
        }

        $this->mergeConfigFrom(__DIR__.'/../../config/systemjob.php', 'systemjob');

        $this->publishes([
            __DIR__.'/../../config/systemjob.php' => config_path('systemjob.php'),
        ]);

        Queue::before(function (JobProcessing $event) {
            $job = $this->getPayloadJob($event->job->payload());
            
            $queue = $this->getJobQueue($job);

            $queue::where('event_id', $event->job->getJobId())
                ->update([
                    'status' => SystemJobStatus::QUEUED
                ]);
        });

        Queue::after(function (JobProcessed $event) {
            $job = $this->getPayloadJob($event->job->payload());

            $queue = $this->getJobQueue($job);

            $date = Carbon::createFromFormat('Y-m-d H:i:s', gmdate(now()));

            if ($job->getDriver() == 'mongo') {
                $date = $queue::fromDateTime($date);
            }

            $queue::where('event_id', $event->job->getJobId())
                ->update([
                    'executed_at' => $date,
                    'attempt' => $event->job->attempts(),
                    'status' => SystemJobStatus::EXECUTED
                ]);
        });

        Queue::failing(function (JobFailed $event) {
            $job = $this->getPayloadJob($event->job->payload());

            $queue = $this->getJobQueue($job);

            $queue::where('event_id', $event->job->getJobId())
                ->update([
                    'status' => SystemJobStatus::FAILED
                ]);
        });
    }

    public function register() 
    {
        $this->app->singleton('systemJob', function() {
            return new SystemJobManager();
        });

        $this->app->alias(ScheduleService::class, 'sql');
        $this->app->alias(ServicesScheduleService::class, 'mongo');

        $this->app->alias(SqlSystemJobRepository::class, 'repo_sql');
        $this->app->alias(MongoSystemJobRepository::class, 'repo_mongo');
    }

    private function getPayloadJob($payload) {
        $job = unserialize($payload['data']['command']);
        return $job;
    }

    private function getJobQueue($job) {
        $queue = config('systemjob.schedulers.'. $job->getDriver() .'.model');
        return $queue;
    }
}