<?php

declare (strict_types=1);
/*
 * This file is part of the Mink package.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Behat\Mink\Driver;

use Behat\Mink\Exception\Unsupported_Driver_Action_Exception;
use Behat\Mink\Session;
/**
 * Core driver.
 * All other drivers should extend this class for future compatibility.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
abstract class Core_Driver implements Driver_Interface
{
    /**
     * {@inheritdoc}
     */
    public function set_session(Session $session)
    {
        throw new Unsupported_Driver_Action_Exception('Setting the session is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function start()
    {
        throw new Unsupported_Driver_Action_Exception('Starting the driver is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function is_started()
    {
        throw new Unsupported_Driver_Action_Exception('Checking the driver state is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        throw new Unsupported_Driver_Action_Exception('Stopping the driver is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        throw new Unsupported_Driver_Action_Exception('Resetting the driver is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function visit($url)
    {
        throw new Unsupported_Driver_Action_Exception('Visiting an url is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_current_url()
    {
        throw new Unsupported_Driver_Action_Exception('Getting the current url is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_content()
    {
        throw new Unsupported_Driver_Action_Exception('Getting the page content is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function find($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Finding elements is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_tag_name($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Getting the tag name is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_text($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Getting the element text is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_html($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Getting the element inner HTML is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_outer_html($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Getting the element outer HTML is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_attribute($xpath, $name)
    {
        throw new Unsupported_Driver_Action_Exception('Getting the element attribute is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_value($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Getting the field value is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function set_value($xpath, $value)
    {
        throw new Unsupported_Driver_Action_Exception('Setting the field value is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function check($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Checking a checkbox is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function uncheck($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Unchecking a checkbox is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function is_checked($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Getting the state of a checkbox is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function select_option($xpath, $value, $multiple = false)
    {
        throw new Unsupported_Driver_Action_Exception('Selecting an option is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function click($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Clicking on an element is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function attach_file($xpath, $path)
    {
        throw new Unsupported_Driver_Action_Exception('Attaching a file in an input is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function reload()
    {
        throw new Unsupported_Driver_Action_Exception('Page reloading is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function forward()
    {
        throw new Unsupported_Driver_Action_Exception('Forward action is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function back()
    {
        throw new Unsupported_Driver_Action_Exception('Backward action is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function set_basic_auth($user, $password)
    {
        throw new Unsupported_Driver_Action_Exception('Basic auth setup is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function switch_to_window($name = null)
    {
        throw new Unsupported_Driver_Action_Exception('Windows management is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function switch_to_i_frame($name = null)
    {
        throw new Unsupported_Driver_Action_Exception('iFrames management is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function set_request_header($name, $value)
    {
        throw new Unsupported_Driver_Action_Exception('Request headers manipulation is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_response_headers()
    {
        throw new Unsupported_Driver_Action_Exception('Response headers are not available from %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function set_cookie($name, $value = null)
    {
        throw new Unsupported_Driver_Action_Exception('Cookies manipulation is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_cookie($name)
    {
        throw new Unsupported_Driver_Action_Exception('Cookies are not available from %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_status_code()
    {
        throw new Unsupported_Driver_Action_Exception('Status code is not available from %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_screenshot()
    {
        throw new Unsupported_Driver_Action_Exception('Screenshots are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_window_names()
    {
        throw new Unsupported_Driver_Action_Exception('Listing all window names is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function get_window_name()
    {
        throw new Unsupported_Driver_Action_Exception('Listing this window name is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function double_click($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Double-clicking is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function right_click($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Right-clicking is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function is_visible($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Element visibility check is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function is_selected($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Element selection check is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function mouse_over($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Mouse manipulations are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function focus($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Mouse manipulations are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function blur($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Mouse manipulations are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function key_press($xpath, $char, $modifier = null)
    {
        throw new Unsupported_Driver_Action_Exception('Keyboard manipulations are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function key_down($xpath, $char, $modifier = null)
    {
        throw new Unsupported_Driver_Action_Exception('Keyboard manipulations are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function key_up($xpath, $char, $modifier = null)
    {
        throw new Unsupported_Driver_Action_Exception('Keyboard manipulations are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function drag_to($source_xpath, $destination_xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Mouse manipulations are not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function execute_script($script)
    {
        throw new Unsupported_Driver_Action_Exception('JS is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function evaluate_script($script)
    {
        throw new Unsupported_Driver_Action_Exception('JS is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function wait($timeout, $condition)
    {
        throw new Unsupported_Driver_Action_Exception('JS is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function resize_window($width, $height, $name = null)
    {
        throw new Unsupported_Driver_Action_Exception('Window resizing is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function maximize_window($name = null)
    {
        throw new Unsupported_Driver_Action_Exception('Window maximize is not supported by %s', $this);
    }
    /**
     * {@inheritdoc}
     */
    public function submit_form($xpath)
    {
        throw new Unsupported_Driver_Action_Exception('Form submission is not supported by %s', $this);
    }
}