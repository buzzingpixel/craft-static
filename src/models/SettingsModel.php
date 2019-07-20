<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\models;

use Craft;
use craft\base\Model;
use craft\console\Application as ConsoleApplication;
use const DIRECTORY_SEPARATOR;
use function rtrim;

class SettingsModel extends Model
{
    /**
     * Initializes model
     */
    public function init() : void
    {
        if (! isset($_SERVER['DOCUMENT_ROOT']) ||
            ! $_SERVER['DOCUMENT_ROOT'] ||
            Craft::$app instanceof ConsoleApplication
        ) {
            return;
        }

        $sep = DIRECTORY_SEPARATOR;

        $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);

        $this->cachePath = $docRoot . $sep . 'cache';
    }

    /** @var string|bool $cachePath */
    public $cachePath = false;

    /** @var bool $nixBasedClearCache */
    public $nixBasedClearCache = true;
}
