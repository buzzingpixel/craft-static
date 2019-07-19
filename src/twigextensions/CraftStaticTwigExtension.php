<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\twigextensions;

use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class CraftStaticTwigExtension extends AbstractExtension
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
     *
     * @return TokenParserInterface[]
     */
    public function getTokenParsers() : array
    {
        return [new CraftStaticTokenParser()];
    }
}
