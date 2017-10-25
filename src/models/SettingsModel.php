<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\models;

use craft\base\Model;

/**
 * Class SettingsModel
 */
class SettingsModel extends Model
{
    /**
     * Initializes model
     */
    public function init()
    {
        if (! isset($_SERVER['DOCUMENT_ROOT']) || ! $_SERVER['DOCUMENT_ROOT']) {
            return;
        }

        $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);

        $this->cachePath =  "{$docRoot}/cache";
    }

    /** @var string $cachePath */
    public $cachePath;
}
