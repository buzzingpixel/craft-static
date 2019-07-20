<?php

declare(strict_types=1);

use Whoops\Handler\PlainTextHandler as WhoopsPlainTextHandler;
use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;
use Whoops\Run as WhoopsRun;

define('CRAFT_BASE_PATH', dirname(__DIR__));
define('CRAFT_VENDOR_PATH', dirname(CRAFT_BASE_PATH) . '/vendor');

require_once CRAFT_VENDOR_PATH . '/autoload.php';

define('CRAFT_ENVIRONMENT', 'dev');

$whoops = new WhoopsRun();

$whoops->register();

require dirname(__DIR__) . '/config/devMode.php';

if (PHP_SAPI === 'cli') {
    $whoops->prependHandler(new WhoopsPlainTextHandler());

    $app      = include CRAFT_VENDOR_PATH . '/craftcms/cms/bootstrap/console.php';
    $exitCode = $app->run();
    exit($exitCode);
}

$whoops->prependHandler(new WhoopsPrettyPageHandler());

$app = include CRAFT_VENDOR_PATH . '/craftcms/cms/bootstrap/web.php';
$app->run();
