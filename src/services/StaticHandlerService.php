<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\services;

use Craft;
use craft\base\Component;
use craft\web\Request;

/**
 * Class StaticHandlerService
 */
class StaticHandlerService extends Component
{
    /** @var string $cachePath */
    private $cachePath;

    /** @var Request $requestService */
    private $requestService;

    /**
     * StaticHandlerService constructor
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct();

        foreach ($config as $key => $val) {
            $this->{$key} = $val;
        }

        $sep = DIRECTORY_SEPARATOR;

        $this->cachePath = rtrim($this->cachePath, $sep) . $sep;
        $this->cachePath .= ltrim(
            rtrim(
                $this->requestService->getFullPath(),
                $sep
            ),
            $sep
        );
    }

    /**
     * Handles the incoming content
     * @param string $content
     * @param bool $cache
     * @return string
     */
    public function handleContent(string $content, $cache = true) : string
    {
        $content = str_replace(
            [
                '<![CDATA[YII-BLOCK-HEAD]]>',
                '<![CDATA[YII-BLOCK-BODY-BEGIN]]>',
                '<![CDATA[YII-BLOCK-BODY-END]]>',
            ],
            '',
            $content
        );

        if (! $cache) {
            return $content;
        }

        if (! @mkdir($this->cachePath, 0777, true) &&
            ! is_dir($this->cachePath)
        ) {
            return $content;
        }

        file_put_contents("{$this->cachePath}/index.html", $content);

        return $content;
    }
}
