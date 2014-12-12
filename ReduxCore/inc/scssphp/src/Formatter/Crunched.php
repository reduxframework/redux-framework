<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2014 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/gpl-license GPL-3.0
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.net/scssphp
 */

namespace Leafo\ScssPhp\Formatter;

use Leafo\ScssPhp\Formatter;

/**
 * SCSS crunched formatter
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class Crunched extends Formatter
{
    public function __construct()
    {
        $this->indentLevel = 0;
        $this->indentChar = '  ';
        $this->break = '';
        $this->open = '{';
        $this->close = '}';
        $this->tagSeparator = ',';
        $this->assignSeparator = ':';
    }

    public function indentStr($n = 0)
    {
        return '';
    }

    public function blockLines($inner, $block)
    {
        $glue = $this->break.$inner;

        foreach ($block->lines as $index => $line) {
            if (substr($line, 0, 2) === '/*') {
                unset($block->lines[$index]);
            }
        }

        echo $inner . implode($glue, $block->lines);

        if (!empty($block->children)) {
            echo $this->break;
        }
    }
}
