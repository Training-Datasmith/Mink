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

use Behat\Mink\Driver\Driver_Interface;
/**
 * Exception thrown by drivers when they don't support the requested action.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Unsupported_Driver_Action_Exception extends Driver_Exception
{
    /**
     * Initializes exception.
     *
     * @param string          $template what is unsupported?
     * @param DriverInterface $driver   driver instance
     * @param \Exception      $previous previous exception
     */
    public function __construct($template, Driver_Interface $driver, \Exception $previous = null)
    {
        $message = sprintf($template, get_class($driver));
        parent::__construct($message, 0, $previous);
    }
}