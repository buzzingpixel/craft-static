<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\services;

use craft\web\Request;
use FilesystemIterator;
use craft\base\Component;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Class StaticHandlerService
 */
class StaticHandlerService extends Component
{
    /** @var string $cachePath */
    private $cachePath;

    /** @var bool $nixBasedClearCache */
    private $nixBasedClearCache;

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

        $sep = DIRECTORY_SEPARATOR;

        $cachePath = $this->cachePath . ltrim(
            rtrim(
                $this->requestService->getFullPath(),
                $sep
            ),
            $sep
        );

        if (! @mkdir($cachePath, 0777, true) &&
            ! is_dir($cachePath)
        ) {
            return $content;
        }

        file_put_contents("{$cachePath}/index.html", $content);

        return $content;
    }

    /**
     * Clears the cache
     */
    public function clearCache()
    {
        if ($this->nixBasedClearCache) {
            shell_exec("rm -rf {$this->cachePath}/*");
            return;
        }

        $di = new RecursiveDirectoryIterator(
            $this->cachePath,
            FilesystemIterator::SKIP_DOTS
        );

        $ri = new RecursiveIteratorIterator(
            $di,
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($ri as $file) {
            $file->isDir() ?  rmdir($file) : unlink($file);
        }
    }
}
