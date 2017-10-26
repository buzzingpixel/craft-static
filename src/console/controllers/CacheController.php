<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\console\controllers;

use yii\helpers\Console;
use yii\console\Controller;
use buzzingpixel\craftstatic\Craftstatic;

/**
 * Cache command
 */
class CacheController extends Controller
{
    /**
     * Purges all static cache
     */
    public function actionPurge()
    {
        Craftstatic::$plugin->getStaticHandler()->clearCache();

        $this->stdout(
            'Static cache cleared successfully.' . PHP_EOL,
            Console::FG_GREEN
        );
    }
}