<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2018 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\twigextensions;

use Twig_Token;

/**
 * Class CraftStaticTokenParser
 */
class CraftStaticTokenParser extends \Twig_TokenParser
{
    /**
     * @inheritdoc
     */
    public function getTag() : string
    {
        return 'static';
    }

    /**
     * @inheritdoc
     */
    public function parse(Twig_Token $token)
    {
        $attributes = [
            'cache' => true
        ];

        $stream = $this->parser->getStream();

        if ($stream->test(Twig_Token::NAME_TYPE, 'cache')) {
            $stream->next();
            $attributes['cache'] = $stream->getCurrent()->getValue();
            $stream->next();
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $nodes['body'] = $this->parser->subparse([
            $this,
            'decideStaticEnd'
        ], true);
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new CraftStaticNode(
            $nodes,
            $attributes,
            $token->getLine(),
            $this->getTag()
        );
    }

    /**
     * @param \Twig_Token $token
     * @return bool
     */
    public function decideStaticEnd(Twig_Token $token) : bool
    {
        return $token->test('endstatic');
    }
}
