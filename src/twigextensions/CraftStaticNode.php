<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace buzzingpixel\craftstatic\twigextensions;

use buzzingpixel\craftstatic\Craftstatic;
use Twig_Compiler;

/**
 * Class CraftStaticNode
 */
class CraftStaticNode extends \Twig_Node
{
    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
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
                Craftstatic::className() .
                "::\$plugin->getStaticHandler()->handleContent(\$compiledBody, \$cache);\n"
            );
    }
}
