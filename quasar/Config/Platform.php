<?php

return array(

    /**
     * Debug Mode
     */
    'debug' => true, // When enabled the actual PHP errors will be shown.

    /**
     * The Website URL.
     */
    'url' => 'http://www.quasar.dev:' .SERVER_PORT .'/',

    /**
     * Website Name.
     */
    'name' => 'Quasar Push Server',

    /**
     * The default Timezone for your website.
     * http://www.php.net/manual/en/timezones.php
     */
    'timezone' => 'Europe/London',

    /**
     * The Platform's Middleware stack.
     */
    'middleware' => array(
        'Quasar\Http\Middleware\DispatchAssetFiles',
    ),

    /**
     * The Platform's route Middleware Groups.
     */
    'middlewareGroups' => array(
        'web' => array(
            'Quasar\Http\Middleware\StartSession',
        ),
        'api' => array(
            //'sample:60,1',
        ),
    ),

    /**
     * The Platform's route Middleware.
     */
    'routeMiddleware' => array(
        //'sample' => 'Quasar\Http\Middleware\Sample',
    ),

    /**
     * The registered Service Providers.
     */
    'providers' => array(
        'Quasar\Platform\Database\DatabaseServiceProvider',
        'Quasar\Platform\Session\SessionServiceProvider',
        'Quasar\Platform\View\ViewServiceProvider',
    ),

    'manifest' => storage_path(),

    /**
     * The registered Class Aliases.
     */
    'aliases' => array(
        'App'      => 'Quasar\Support\Facades\App',
        'Config'   => 'Quasar\Support\Facades\Config',
        'DB'       => 'Quasar\Support\Facades\DB',
        'Event'    => 'Quasar\Support\Facades\Event',
        'Redirect' => 'Quasar\Support\Facades\Redirect',
        'Response' => 'Quasar\Support\Facades\Response',
        'Session'  => 'Quasar\Support\Facades\Session',
        'View'     => 'Quasar\Support\Facades\View',
    ),
);
