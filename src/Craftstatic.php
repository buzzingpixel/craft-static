<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic;

use Craft;
use yii\base\Event;
use craft\base\Plugin;
use craft\services\Elements;
use craft\utilities\ClearCaches;
use craft\events\RegisterCacheOptionsEvent;
use buzzingpixel\craftstatic\models\SettingsModel;
use craft\console\Application as ConsoleApplication;
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

        Event::on(
            Elements::class,
            Elements::EVENT_AFTER_SAVE_ELEMENT,
            function () {
                self::getStaticHandler()->clearCache();
            }
        );

        Event::on(
            ClearCaches::class,
            ClearCaches::EVENT_REGISTER_CACHE_OPTIONS,
            function (RegisterCacheOptionsEvent $event) {
                $event->options[] = [
                    'key' => 'craft-static-caches',
                    'label' => 'Static File Caches',
                    'action' => [self::$plugin->getStaticHandler(), 'clearCache']
                ];
            }
        );

        // Add in our console commands
        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'buzzingpixel\craftstatic\console\controllers';
        }
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
