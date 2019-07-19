<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\console\controllers;

use buzzingpixel\craftstatic\Craftstatic;
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
     */
    public function actionPurge() : void
    {
        Craftstatic::$plugin->getStaticHandler()->clearCache();

        $this->stdout(
            'Static cache cleared successfully.' . PHP_EOL,
            Console::FG_GREEN
        );
    }
}
