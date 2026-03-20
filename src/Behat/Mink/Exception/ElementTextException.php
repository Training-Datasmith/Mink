<?php

declare (strict_types=1);
/*
 * This file is part of the Mink package.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Behat\Mink\Exception;

/**
 * Exception thrown when an expectation on the text of an element fails.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Element_Text_Exception extends Element_Html_Exception
{
    protected function get_context()
    {
        return $this->element->get_text();
    }
}