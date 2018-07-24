<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2018 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\console\controllers;

use yii\helpers\Console;
use yii\console\Controller;
use yii\db\Exception as DbException;
use buzzingpixel\craftstatic\Craftstatic;

/**
 * Static Cache Command
 */
class CacheController extends Controller
{
    /**
     * Purges all static cache
     * @throws DbException
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
