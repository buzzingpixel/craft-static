<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic;

use buzzingpixel\craftstatic\models\SettingsModel;
use buzzingpixel\craftstatic\services\StaticHandlerService;
use buzzingpixel\craftstatic\twigextensions\CraftStaticTwigExtension;
use Craft;
use craft\base\Plugin;
use craft\console\Application as ConsoleApplication;
use craft\events\RegisterCacheOptionsEvent;
use craft\services\Elements;
use craft\utilities\ClearCaches;
use LogicException;
use yii\base\Event;

class Craftstatic extends Plugin
{
    /** @var Craftstatic $plugin */
    public static $plugin;

    /**
     * Initializes plugin
     *
     * @throws LogicException
     */
    public function init() : void
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new CraftStaticTwigExtension());

        Event::on(
            Elements::class,
            Elements::EVENT_AFTER_SAVE_ELEMENT,
            static function () : void {
                self::getStaticHandler()->clearCache();
            }
        );

        Event::on(
            ClearCaches::class,
            ClearCaches::EVENT_REGISTER_CACHE_OPTIONS,
            static function (RegisterCacheOptionsEvent $event) : void {
                $event->options[] = [
                    'key' => 'craft-static-caches',
                    'label' => 'Static File Caches',
                    'action' => [self::$plugin->getStaticHandler(), 'clearCache'],
                ];
            }
        );

        // Add in our console commands
        if (! (Craft::$app instanceof ConsoleApplication)) {
            return;
        }

        $this->controllerNamespace = 'buzzingpixel\craftstatic\console\controllers';
    }

    /**
     * Creates the settings model
     */
    protected function createSettingsModel() : SettingsModel
    {
        return new SettingsModel();
    }

    /**
     * Gets the static handler service
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
