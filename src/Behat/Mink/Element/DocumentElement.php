<?php

declare (strict_types=1);
/*
 * This file is part of the Mink package.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Behat\Mink\Element;

/**
 * Document element.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Document_Element extends Traversable_Element
{
    /**
     * Returns XPath for handled element.
     *
     * @return string
     */
    public function get_xpath()
    {
        return '//html';
    }
    /**
     * Returns document content.
     *
     * @return string
     */
    public function get_content()
    {
        return trim($this->get_driver()->get_content());
    }
    /**
     * Check whether document has specified content.
     *
     * @param string $content
     *
     * @return Boolean
     */
    public function has_content($content)
    {
        return $this->has('named', ['content', $this->get_selectors_handler()->xpath_literal($content)]);
    }
}