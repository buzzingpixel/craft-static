<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2018 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\twigextensions;

use Twig_Extension;

/**
 * Class CraftStaticTwigExtension
 */
class CraftStaticTwigExtension extends Twig_Extension
{
    /**
     * @inheritdoc
     */
    public function getName() : string
    {
        return 'static';
    }

    /**
     * Returns the token parser instances to add to the existing list.
     * @return array An array of Twig_TokenParserInterface or
     * Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers() : array
    {
        return [
            new CraftStaticTokenParser()
        ];
    }
}
