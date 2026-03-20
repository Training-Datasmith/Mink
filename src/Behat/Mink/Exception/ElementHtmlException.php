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
use Behat\Mink\Session;
/**
 * Exception thrown when an expectation on the HTML of an element fails.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Element_Html_Exception extends Expectation_Exception
{
    /**
     * Element instance.
     *
     * @var Element
     */
    protected $element;
    /**
     * Initializes exception.
     *
     * @param string     $message   optional message
     * @param Session    $session   session instance
     * @param Element    $element   element
     * @param \Exception $exception expectation exception
     */
    public function __construct($message, Session $session, Element $element, \Exception $exception = null)
    {
        $this->element = $element;
        parent::__construct($message, $session, $exception);
    }
    protected function get_context()
    {
        return $this->element->get_outer_html();
    }
}