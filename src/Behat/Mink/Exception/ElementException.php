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

use Behat\Mink\Element\Element;
/**
 * A standard way for elements to re-throw exceptions
 *
 * @deprecated This exception class is not used anymore in Mink 1.6 and will be removed in 2.0
 *
 * @author Chris Worfolk <xmeltrut@gmail.com>
 */
class Element_Exception extends Exception
{
    private $element;
    /**
     * Initialises exception.
     *
     * @param Element    $element   optional message
     * @param \Exception $exception exception
     */
    public function __construct(Element $element, \Exception $exception)
    {
        $this->element = $element;
        parent::__construct(sprintf("Exception thrown by %s\n%s", $element->get_xpath(), $exception->get_message()));
    }
    /**
     * Override default toString so we don't send a full backtrace in verbose mode.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get_message();
    }
    /**
     * Get the element that caused the exception
     *
     * @return Element
     */
    public function get_element()
    {
        return $this->element;
    }
}