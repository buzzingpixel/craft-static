<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\twigextensions;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class CraftStaticTokenParser extends AbstractTokenParser
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
    public function parse(Token $token)
    {
        $attributes = ['cache' => true];

        $stream = $this->parser->getStream();

        if ($stream->test(Token::NAME_TYPE, 'cache')) {
            $stream->next();
            $attributes['cache'] = $stream->getCurrent()->getValue();
            $stream->next();
        }

        $stream->expect(Token::BLOCK_END_TYPE);
        $nodes['body'] = $this->parser->subparse([
            $this,
            'decideStaticEnd',
        ], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new CraftStaticNode(
            $nodes,
            $attributes,
            $token->getLine(),
            $this->getTag()
        );
    }

    public function decideStaticEnd(Token $token) : bool
    {
        return $token->test('endstatic');
    }
}
