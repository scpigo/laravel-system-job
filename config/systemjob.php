<?php

return [
    'default' => 'sql',

    'schedulers' => [
        'sql' => [
            'queue_connection' => 'rabbitmq_jobs',
            'model' => 'Scpigo\SystemJob\Drivers\sql\Models\SystemJob',
            'component' => 'Scpigo\SystemJob\Components\Impls\sql\SystemJobScheduler'
        ],

        'mongo' => [
            'queue_connection' => 'rabbitmq_jobs',
            'model' => 'Scpigo\SystemJob\Drivers\mongo\Models\SystemJob',
            'component' => 'Scpigo\SystemJob\Components\Impls\mongo\SystemJobScheduler'
        ]
    ]
];