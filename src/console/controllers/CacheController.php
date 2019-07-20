<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\console\controllers;

use buzzingpixel\craftstatic\Craftstatic;
use Throwable;
use yii\console\Controller;
use yii\helpers\Console;
use const PHP_EOL;

/**
 * Static Cache Command
 */
class CacheController extends Controller
{
    /**
     * Purges all static cache
     *
     * @throws Throwable
     */
    public function actionPurge() : void
    {
        Craftstatic::$plugin->getStaticHandler()->clearCache();

        $this->stdout(
            'Static cache cleared successfully.' . PHP_EOL,
            Console::FG_GREEN
        );
    }

    /**
     * Checks tracked future and expiring entries for cache busting
     *
     * @throws Throwable
     */
    public function actionCheckTracking() : void
    {
        Craftstatic::$plugin->getCheckEntryTracking()->run();

        $this->stdout(
            'Entry tracking checked successfully.' . PHP_EOL,
            Console::FG_GREEN
        );
    }
}
