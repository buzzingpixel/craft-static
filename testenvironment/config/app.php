<?php
declare(strict_types=1);

use buzzingpixel\testenvironment\CustomErrorHandler;
use buzzingpixel\testenvironment\TestEnvironmentModule\TestEnvironmentModule;

return [
    'modules' => [
        'test-environment-module' => TestEnvironmentModule::class,
    ],
    'bootstrap' => [
        'test-environment-module',
    ],
    'components' => [
        'errorHandler' => [
            'class' => CustomErrorHandler::class,
        ],
    ],
];
