<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic;

use Craft;
use craft\base\Plugin;
use buzzingpixel\craftstatic\models\SettingsModel;
use buzzingpixel\craftstatic\services\StaticHandlerService;
use buzzingpixel\craftstatic\twigextensions\CraftStaticTwigExtension;

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

        Craft::$app->view->twig->addExtension(new CraftStaticTwigExtension());
    }

    /**
     * Creates the settings model
     * @return SettingsModel
     */
    protected function createSettingsModel() : SettingsModel
    {
        return new SettingsModel();
    }

    /**
     * Gets the static handler service
     * @return StaticHandlerService
     */
    public function getStaticHandler() : StaticHandlerService
    {
        /** @var SettingsModel $settings */
        $settings = $this->getSettings();
        return new StaticHandlerService([
            'cachePath' => $settings->cachePath,
            'nixBasedClearCache' => $settings->nixBasedClearCache === true,
            'requestService' => Craft::$app->getRequest(),
        ]);
    }
}
