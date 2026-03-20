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

use Behat\Mink\Session;
/**
 * Exception thrown for failed expectations.
 *
 * Some specialized child classes are available to customize the error rendering.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Expectation_Exception extends Exception
{
    private $session;
    /**
     * Initializes exception.
     *
     * @param string     $message   optional message
     * @param Session    $session   session instance
     * @param \Exception $exception expectation exception
     */
    public function __construct($message, Session $session, \Exception $exception = null)
    {
        $this->session = $session;
        if (!$message && null !== $exception) {
            $message = $exception->get_message();
        }
        parent::__construct($message, 0, $exception);
    }
    /**
     * Returns exception message with additional context info.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $page_text = $this->pipe_string($this->trim_string($this->get_context()) . "\n");
            $string = sprintf("%s\n\n%s%s", $this->get_message(), $this->get_response_info(), $page_text);
        } catch (\Exception $e) {
            return $this->get_message();
        }
        return $string;
    }
    /**
     * Gets the context rendered for this exception
     *
     * @return string
     */
    protected function get_context()
    {
        return $this->trim_body($this->get_session()->get_page()->get_content());
    }
    /**
     * Returns exception session.
     *
     * @return Session
     */
    protected function get_session()
    {
        return $this->session;
    }
    /**
     * Prepends every line in a string with pipe (|).
     *
     * @param string $string
     *
     * @return string
     */
    protected function pipe_string($string)
    {
        return '|  ' . strtr($string, ["\n" => "\n|  "]);
    }
    /**
     * Removes response header/footer, letting only <body /> content.
     *
     * @param string $string response content
     *
     * @return string
     */
    protected function trim_body($string)
    {
        return preg_replace(['/^.*<body>/s', '/<\/body>.*$/s'], ['<body>', '</body>'], $string);
    }
    /**
     * Trims string to specified number of chars.
     *
     * @param string  $string response content
     * @param integer $count  trim count
     *
     * @return string
     */
    protected function trim_string($string, $count = 1000)
    {
        $string = trim($string);
        if ($count < mb_strlen($string)) {
            return mb_substr($string, 0, $count - 3) . '...';
        }
        return $string;
    }
    /**
     * Returns response information string.
     *
     * @return string
     */
    protected function get_response_info()
    {
        $driver = basename(str_replace('\\', '/', get_class($this->session->get_driver())));
        $info = '+--[ ';
        try {
            $info .= 'HTTP/1.1 ' . $this->session->get_status_code() . ' | ';
        } catch (Unsupported_Driver_Action_Exception $e) {
            // Ignore the status code when not supported
        }
        return $info . ($this->session->get_current_url() . ' | ' . $driver . " ]\n|\n");
    }
}