<?php

return [

    'title' => 'API Health',

    'resources_location' => [
        'path' => config_path('health/resources'),
        'type' => PragmaRX\Health\Support\Constants::RESOURCES_TYPE_BOTH,
    ],

    'resources_enabled' => [
        'Health',
        'Database',
        'Cache',
        'S3',
        'Http',
        'Mail',
        'Php',
        'Queue',
    ],

    'sort_by' => 'slug',

    'cache' => [
        'minutes' => config('app.debug') ? false : true, // false = disabled

        'key' => 'health-resources',
    ],

    'cache_files_base_path' => $path = 'app/pragmarx/health',

    'notifications' => [
        'enabled' => false,

        'notify_on' => [
            'panel' => false,
            'check' => true,
            'string' => true,
            'resource' => false,
        ],

        'action-title' => 'View App Health',

        'action_message' => "The '%s' service is in trouble and needs attention%s",

        'from' => [
            'name' => 'Wizard API',

            'address' => 'healthchecker@trove.com',

            'icon_emoji' => ':anger:',
        ],

        'scheduler' => [
            'enabled' => true,

            'frequency' => 'everyMinute', // most methods on -- https://laravel.com/docs/5.3/scheduling#defining-schedules
        ],

        'users' => [
            'model' => App\Models\User::class,

            'emails' => [
                'devin@hyvemobile.com',
            ],
        ],

        'channels' => [
            'mail' => [
                'enabled' => true,
                'sender' => PragmaRX\Health\Notifications\Channels\Mail::class,
            ],

            'slack' => [
                'enabled' => false,
                'sender' => PragmaRX\Health\Notifications\Channels\Slack::class,
            ],

            'facebook' => [
                'enabled' => false,
            ],
        ],

        'notifier' => 'PragmaRX\Health\Notifications',
    ],

    'alert' => [
        'success' => [
            'message' => 'Everything is fine with this resource',
            'type' => 'success',
        ],

        'error' => [
            'type' => 'error',
        ],
    ],

    'style' => [
        'button_lines' => 'multi', // multi or single
        'multiplier' => 0.4,
    ],

    'views' => [
        'panel' => 'pragmarx/health::default.panel',
        'partials' => [
            'well' => 'pragmarx/health::default.partials.well',
        ],
    ],

    'string' => [
        'glue' => '-',
        'ok' => 'OK',
        'fail' => 'FAIL',
    ],

    'actions' => [
        'panel' => $action_panel = 'PragmaRX\Health\Http\Controllers\Health@panel',
        'check' => $action_check = 'PragmaRX\Health\Http\Controllers\Health@check',
        'string' => $action_string = 'PragmaRX\Health\Http\Controllers\Health@string',
        'resource' => $action_resource = 'PragmaRX\Health\Http\Controllers\Health@resource',
    ],

    'routes' => [
        'prefix' => $route_prefix = '/health',

        'suffixes' => [
            'panel' => $route_suffix_panel = '/panel',
            'check' => $route_suffix_check = '/check',
            'string' => $route_suffix_string = '/string',
            'resource' => $route_suffix_resource = '/resource',
        ],

        'notification' => 'pragmarx.health.panel',

        'list' => [
            [
                'uri' => $route_prefix.$route_suffix_panel,
                'name' => 'pragmarx.health.panel',
                'action' => $action_panel,
                'middleware' => [/*'auth.basic'*/],
            ],

            [
                'uri' => $route_prefix.$route_suffix_check,
                'name' => 'pragmarx.health.check',
                'action' => $action_check,
                'middleware' => [],
            ],

            [
                'uri' => $route_prefix.$route_suffix_string,
                'name' => 'pragmarx.health.string',
                'action' => $action_string,
                'middleware' => [],
            ],

            [
                'uri' => "{$route_prefix}.{$route_suffix_resource}/{name}",
                'name' => 'pragmarx.health.resource',
                'action' => $action_resource,
                'middleware' => [],
            ],
        ],
    ],

    'urls' => [
        'panel' => '/health/panel',
    ],

];
