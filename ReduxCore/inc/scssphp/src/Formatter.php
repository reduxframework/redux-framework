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

namespace Leafo\ScssPhp;

/**
 * SCSS base formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
abstract class Formatter
{
    /**
     * @var integer
     */
    public $indentLevel;

    /**
     * @var string
     */
    public $indentChar;

    /**
     * @var string
     */
    public $break;

    /**
     * @var string
     */
    public $open;

    /**
     * @var string
     */
    public $close;

    /**
     * @var string
     */
    public $tagSeparator;

    /**
     * @var string
     */
    public $assignSeparator;

    /**
     * Return indentation (whitespace)
     *
     * @param integer $n
     * @return string
     */
    protected function indentStr($n = 0)
    {
        return str_repeat($this->indentChar, max($this->indentLevel + $n, 0));
    }

    /**
     * Return property assignment
     *
     * @param string $name
     * @param mixed  $value
     * @return string
     */
    public function property($name, $value)
    {
        return $name . $this->assignSeparator . $value . ';';
    }

    /**
     * Output lines inside a block
     *
     * @param string    $inner
     * @param \stdClass $block
     */
    protected function blockLines($inner, $block)
    {
        $glue = $this->break.$inner;
        echo $inner . implode($glue, $block->lines);

        if (! empty($block->children)) {
            echo $this->break;
        }
    }

    /**
     * Output non-empty block
     *
     * @param \stdClass $block
     */
    protected function block($block)
    {
        if (empty($block->lines) && empty($block->children)) {
            return;
        }

        $inner = $pre = $this->indentStr();

        if (! empty($block->selectors)) {
            echo $pre
                . implode($this->tagSeparator, $block->selectors)
                . $this->open . $this->break;

            $this->indentLevel++;

            $inner = $this->indentStr();
        }

        if (! empty($block->lines)) {
            $this->blockLines($inner, $block);
        }

        foreach ($block->children as $child) {
            $this->block($child);
        }

        if (! empty($block->selectors)) {
            $this->indentLevel--;

            if (empty($block->children)) {
                echo $this->break;
            }

            echo $pre . $this->close . $this->break;
        }
    }

    /**
     * Entry point to formatting a block
     *
     * @param \stdClass $block An abstract syntax tree
     * @return string
     */
    public function format($block)
    {
        ob_start();

        $this->block($block);

        $out = ob_get_clean();

        return $out;
    }
}
