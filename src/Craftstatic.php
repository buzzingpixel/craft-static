<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic;

use buzzingpixel\craftstatic\models\SettingsModel;
use buzzingpixel\craftstatic\services\StaticHandlerService;
use Craft;
use craft\base\Plugin;
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
        return new StaticHandlerService([
            'cachePath' => $this->getSettings()->cachePath,
            'requestService' => Craft::$app->getRequest(),
        ]);
    }
}
