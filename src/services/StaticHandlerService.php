<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\services;

use craft\base\Component;
use craft\web\Request;
use FilesystemIterator;
use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use const DIRECTORY_SEPARATOR;
use function file_put_contents;
use function is_dir;
use function ltrim;
use function mkdir;
use function rmdir;
use function rtrim;
use function shell_exec;
use function sprintf;
use function str_replace;
use function unlink;

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
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct();

        foreach ($config as $key => $val) {
            $this->{$key} = $val;
        }

        if (! $this->cachePath) {
            return;
        }

        $sep = DIRECTORY_SEPARATOR;

        $this->cachePath = rtrim($this->cachePath, $sep) . $sep;
    }

    /**
     * Handles the incoming content
     */
    public function handleContent(string $content, bool $cache = true) : string
    {
        if (! $this->cachePath) {
            return $content;
        }

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

        file_put_contents($cachePath . '/index.html', $content);

        return $content;
    }

    /**
     * Clears the cache
     *
     * @throws LogicException
     */
    public function clearCache() : void
    {
        if (! $this->cachePath) {
            throw new LogicException('The cache path is not defined');
        }

        if ($this->nixBasedClearCache) {
            shell_exec(sprintf('rm -rf %s/*', $this->cachePath));

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
