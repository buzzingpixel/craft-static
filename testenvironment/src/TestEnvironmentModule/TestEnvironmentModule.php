<?php

declare(strict_types=1);

namespace buzzingpixel\testenvironment\TestEnvironmentModule;

use Craft;
use yii\base\Module;

class TestEnvironmentModule extends Module
{
    public function init()
    {
        $this->setUp();
        parent::init();
    }

    public function setUp() : void
    {
        Craft::setAlias(
            '@buzzingpixel/testenvironment/TestEnvironmentModule/controllers',
            __DIR__ . '/controllers'
        );
    }
}
