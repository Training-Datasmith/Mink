<?php

declare (strict_types=1);
/*
 * This file is part of the Mink package.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Behat\Mink;

use Behat\Mink\Driver\Driver_Interface;
use Behat\Mink\Element\Document_Element;
use Behat\Mink\Selector\Selectors_Handler;
/**
 * Mink session.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Session
{
    private $driver;
    private $page;
    private $selectors_handler;
    /**
     * Initializes session.
     */
    public function __construct(Driver_Interface $driver, Selectors_Handler $selectors_handler = null)
    {
        $driver->set_session($this);
        if (null === $selectors_handler) {
            $selectors_handler = new Selectors_Handler();
        }
        $this->driver = $driver;
        $this->selectors_handler = $selectors_handler;
        $this->page = new Document_Element($this);
    }
    /**
     * Checks whether session (driver) was started.
     *
     * @return Boolean
     */
    public function is_started()
    {
        return $this->driver->is_started();
    }
    /**
     * Starts session driver.
     *
     * Calling any action before visiting a page is an undefined behavior.
     * The only supported method calls on a fresh driver are
     * - visit()
     * - setRequestHeader()
     * - setBasicAuth()
     * - reset()
     * - stop()
     */
    public function start()
    {
        $this->driver->start();
    }
    /**
     * Stops session driver.
     */
    public function stop()
    {
        $this->driver->stop();
    }
    /**
     * Restart session driver.
     */
    public function restart()
    {
        $this->driver->stop();
        $this->driver->start();
    }
    /**
     * Reset session driver state.
     *
     * Calling any action before visiting a page is an undefined behavior.
     * The only supported method calls on a fresh driver are
     * - visit()
     * - setRequestHeader()
     * - setBasicAuth()
     * - reset()
     * - stop()
     */
    public function reset()
    {
        $this->driver->reset();
    }
    /**
     * Returns session driver.
     *
     * @return DriverInterface
     */
    public function get_driver()
    {
        return $this->driver;
    }
    /**
     * Returns page element.
     *
     * @return DocumentElement
     */
    public function get_page()
    {
        return $this->page;
    }
    /**
     * Returns selectors handler.
     *
     * @return SelectorsHandler
     */
    public function get_selectors_handler()
    {
        return $this->selectors_handler;
    }
    /**
     * Visit specified URL.
     *
     * @param string $url url of the page
     */
    public function visit($url)
    {
        $this->driver->visit($url);
    }
    /**
     * Sets HTTP Basic authentication parameters
     *
     * @param string|Boolean $user     user name or false to disable authentication
     * @param string         $password password
     */
    public function set_basic_auth($user, $password = '')
    {
        $this->driver->set_basic_auth($user, $password);
    }
    /**
     * Sets specific request header.
     *
     * @param string $name
     * @param string $value
     */
    public function set_request_header($name, $value)
    {
        $this->driver->set_request_header($name, $value);
    }
    /**
     * Returns all response headers.
     *
     * @return array
     */
    public function get_response_headers()
    {
        return $this->driver->get_response_headers();
    }
    /**
     * Sets cookie.
     *
     * @param string $name
     * @param string $value
     */
    public function set_cookie($name, $value = null)
    {
        $this->driver->set_cookie($name, $value);
    }
    /**
     * Returns cookie by name.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function get_cookie($name)
    {
        return $this->driver->get_cookie($name);
    }
    /**
     * Returns response status code.
     *
     * @return integer
     */
    public function get_status_code()
    {
        return $this->driver->get_status_code();
    }
    /**
     * Returns current URL address.
     *
     * @return string
     */
    public function get_current_url()
    {
        return $this->driver->get_current_url();
    }
    /**
     * Capture a screenshot of the current window.
     *
     * @return string screenshot of MIME type image/* depending
     *                on driver (e.g., image/png, image/jpeg)
     */
    public function get_screenshot()
    {
        return $this->driver->get_screenshot();
    }
    /**
     * Return the names of all open windows
     *
     * @return array Array of all open window's names.
     */
    public function get_window_names()
    {
        return $this->driver->get_window_names();
    }
    /**
     * Return the name of the currently active window
     *
     * @return string The name of the current window.
     */
    public function get_window_name()
    {
        return $this->driver->get_window_name();
    }
    /**
     * Reloads current session page.
     */
    public function reload()
    {
        $this->driver->reload();
    }
    /**
     * Moves backward 1 page in history.
     */
    public function back()
    {
        $this->driver->back();
    }
    /**
     * Moves forward 1 page in history.
     */
    public function forward()
    {
        $this->driver->forward();
    }
    /**
     * Switches to specific browser window.
     *
     * @param string $name window name (null for switching back to main window)
     */
    public function switch_to_window($name = null)
    {
        $this->driver->switch_to_window($name);
    }
    /**
     * Switches to specific iFrame.
     *
     * @param string $name iframe name (null for switching back)
     */
    public function switch_to_i_frame($name = null)
    {
        $this->driver->switch_to_i_frame($name);
    }
    /**
     * Execute JS in browser.
     *
     * @param string $script javascript
     */
    public function execute_script($script)
    {
        $this->driver->execute_script($script);
    }
    /**
     * Execute JS in browser and return it's response.
     *
     * @param string $script javascript
     *
     * @return string
     */
    public function evaluate_script($script)
    {
        return $this->driver->evaluate_script($script);
    }
    /**
     * Waits some time or until JS condition turns true.
     *
     * @param integer $time      time in milliseconds
     * @param string  $condition JS condition
     *
     * @return boolean
     */
    public function wait($time, $condition = 'false')
    {
        return $this->driver->wait($time, $condition);
    }
    /**
     * Set the dimensions of the window.
     *
     * @param integer $width  set the window width, measured in pixels
     * @param integer $height set the window height, measured in pixels
     * @param string  $name   window name (null for the main window)
     */
    public function resize_window($width, $height, $name = null)
    {
        $this->driver->resize_window($width, $height, $name);
    }
    /**
     * Maximize the window if it is not maximized already
     *
     * @param string $name window name (null for the main window)
     */
    public function maximize_window($name = null)
    {
        $this->driver->maximize_window($name);
    }
}