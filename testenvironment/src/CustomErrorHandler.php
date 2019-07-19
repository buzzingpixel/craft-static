<?php

declare(strict_types=1);

namespace buzzingpixel\testenvironment;

use lucidtaz\yii2whoops\ErrorHandler;

class CustomErrorHandler extends ErrorHandler
{
    /**
     * If this isn't here, Yii gets cranky
     *
     * @var mixed
     */
    public $errorAction;
}
