<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic;

use craft\base\Plugin;

/**
 * Class Craftstatic
 */
class Craftstatic extends Plugin
{
    /** @var Craftstatic $plugin */
    public static $plugin;

    /**
     * Initializes plugin
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;
    }
}
