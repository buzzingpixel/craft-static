<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic;

use buzzingpixel\craftstatic\factories\QueryFactory;
use buzzingpixel\craftstatic\models\SettingsModel;
use buzzingpixel\craftstatic\services\CheckEntryTracking;
use buzzingpixel\craftstatic\services\ProcessEntryTracking;
use buzzingpixel\craftstatic\services\StaticHandlerService;
use buzzingpixel\craftstatic\twigextensions\CraftStaticTwigExtension;
use Craft;
use craft\base\Plugin;
use craft\console\Application as ConsoleApplication;
use craft\elements\Entry;
use craft\events\ElementEvent;
use craft\events\RegisterCacheOptionsEvent;
use craft\services\Elements;
use craft\utilities\ClearCaches;
use Throwable;
use yii\base\Event;
use function method_exists;

class Craftstatic extends Plugin
{
    public const MYSQL_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public const DATE_TIME_PRECISION_FORMAT = 'Y-m-d\TH:i:s.uP';

    /** @var Craftstatic $plugin */
    public static $plugin;

    /**
     * @throws Throwable
     */
    public function init() : void
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new CraftStaticTwigExtension());

        Event::on(
            Elements::class,
            Elements::EVENT_AFTER_SAVE_ELEMENT,
            static function (ElementEvent $elementEvent) : void {
                if (! $elementEvent->element) {
                    return;
                }

                if (method_exists($elementEvent->element, 'getIsDraft') &&
                    $elementEvent->element->getIsDraft()
                ) {
                    return;
                }

                self::$plugin->getStaticHandler()->clearCache();

                if (! $elementEvent->element instanceof Entry) {
                    return;
                }

                self::$plugin->getProcessEntryTracking()->process($elementEvent->element);
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

    protected function createSettingsModel() : SettingsModel
    {
        return new SettingsModel();
    }

    /**
     * @throws Throwable
     */
    public function getStaticHandler() : StaticHandlerService
    {
        /** @var SettingsModel $settings */
        $settings = $this->getSettings();

        return new StaticHandlerService([
            'cachePath' => $settings->cachePath,
            'nixBasedClearCache' => $settings->nixBasedClearCache === true,
            'requestService' => Craft::$app->getRequest(),
            'dbConnection' => Craft::$app->getDb(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function getProcessEntryTracking() : ProcessEntryTracking
    {
        return new ProcessEntryTracking([
            'dbConnection' => Craft::$app->getDb(),
            'queryFactory' => new QueryFactory(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function getCheckEntryTracking() : CheckEntryTracking
    {
        return new CheckEntryTracking([
            'queryFactory' => new QueryFactory(),
            'staticHandler' => $this->getStaticHandler(),
        ]);
    }
}
