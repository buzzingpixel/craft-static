<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\twigextensions;

use buzzingpixel\craftstatic\Craftstatic;
use Twig\Compiler;
use Twig\Node\Node;
use function is_bool;

class CraftStaticNode extends Node
{
    public function compile(Compiler $compiler) : void
    {
        $cache = $this->getAttribute('cache');

        if (is_bool($cache)) {
            $cache = $cache ? 'true' : 'false';
            $compiler->write("\$cache = {$cache};\n");
        } elseif ($cache === 0 || $cache === 1) {
            $cache = $cache === 1 ? 'true' : 'false';
            $compiler->write("\$cache = {$cache};\n");
        } elseif ($cache === 'true' || $cache === 'false') {
            $cache = $cache === 'true' ? 'true' : 'false';
            $compiler->write("\$cache = {$cache};\n");
        } else {
            $compiler->write("\$cache = isset(\$context['{$cache}']) && \$context['{$cache}'];\n");
        }

        $compiler->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("\$compiledBody = ob_get_clean();\n")
            ->write(
                'echo ' .
                Craftstatic::class .
                "::\$plugin->getStaticHandler()->handleContent(\$compiledBody, \$cache);\n"
            );
    }
}
