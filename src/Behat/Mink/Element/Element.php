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

use Behat\Mink\Driver\Driver_Interface;
use Behat\Mink\Exception\Element_Not_Found_Exception;
use Behat\Mink\Selector\Selectors_Handler;
use Behat\Mink\Selector\Xpath\Manipulator;
use Behat\Mink\Session;
/**
 * Base element.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
abstract class Element implements Element_Interface
{
    /**
     * @var Session
     */
    private $session;
    /**
     * Driver.
     *
     * @var DriverInterface
     */
    private $driver;
    /**
     * @var SelectorsHandler
     */
    private $selectors_handler;
    /**
     * @var Manipulator
     */
    private $xpath_manipulator;
    /**
     * Initialize element.
     */
    public function __construct(Session $session)
    {
        $this->xpath_manipulator = new Manipulator();
        $this->session = $session;
        $this->driver = $session->get_driver();
        $this->selectors_handler = $session->get_selectors_handler();
    }
    /**
     * Returns element session.
     *
     * @return Session
     *
     * @deprecated Accessing the session from the element is deprecated as of 1.6 and will be impossible in 2.0.
     */
    public function get_session()
    {
        return $this->session;
    }
    /**
     * Returns element's driver.
     *
     * @return DriverInterface
     */
    protected function get_driver()
    {
        return $this->driver;
    }
    /**
     * Returns selectors handler.
     *
     * @return SelectorsHandler
     */
    protected function get_selectors_handler()
    {
        return $this->selectors_handler;
    }
    /**
     * {@inheritdoc}
     */
    public function has($selector, $locator)
    {
        return null !== $this->find($selector, $locator);
    }
    /**
     * {@inheritdoc}
     */
    public function is_valid()
    {
        return 1 === count($this->get_driver()->find($this->get_xpath()));
    }
    /**
     * {@inheritdoc}
     */
    public function wait_for($timeout, $callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Given callback is not a valid callable');
        }
        $start = microtime(true);
        $end = $start + $timeout;
        do {
            $result = call_user_func($callback, $this);
            if ($result) {
                break;
            }
            usleep(100000);
        } while (microtime(true) < $end);
        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function find($selector, $locator)
    {
        $items = $this->find_all($selector, $locator);
        return count($items) ? current($items) : null;
    }
    /**
     * {@inheritdoc}
     */
    public function find_all($selector, $locator)
    {
        if ('named' === $selector) {
            $items = $this->find_all('named_exact', $locator);
            if (empty($items)) {
                return $this->find_all('named_partial', $locator);
            }
            return $items;
        }
        $xpath = $this->get_selectors_handler()->selector_to_xpath($selector, $locator);
        $xpath = $this->xpath_manipulator->prepend($xpath, $this->get_xpath());
        return $this->get_driver()->find($xpath);
    }
    /**
     * {@inheritdoc}
     */
    public function get_text()
    {
        return $this->get_driver()->get_text($this->get_xpath());
    }
    /**
     * {@inheritdoc}
     */
    public function get_html()
    {
        return $this->get_driver()->get_html($this->get_xpath());
    }
    /**
     * Returns element outer html.
     *
     * @return string
     */
    public function get_outer_html()
    {
        return $this->get_driver()->get_outer_html($this->get_xpath());
    }
    /**
     * Builds an ElementNotFoundException
     *
     * This is an helper to build the ElementNotFoundException without
     * needing to use the deprecated getSession accessor in child classes.
     *
     * @param string      $type
     * @param string|null $selector
     * @param string|null $locator
     *
     * @return ElementNotFoundException
     */
    protected function element_not_found($type, $selector = null, $locator = null)
    {
        return new Element_Not_Found_Exception($this->session, $type, $selector, $locator);
    }
}