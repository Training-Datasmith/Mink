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

/**
 * Mink sessions manager.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Mink
{
    private $default_session_name;
    /**
     * Sessions.
     *
     * @var Session[]
     */
    private $sessions = [];
    /**
     * Initializes manager.
     *
     * @param Session[] $sessions
     */
    public function __construct(array $sessions = [])
    {
        foreach ($sessions as $name => $session) {
            $this->register_session($name, $session);
        }
    }
    /**
     * Stops all started sessions.
     */
    public function __destruct()
    {
        $this->stop_sessions();
    }
    /**
     * Registers new session.
     *
     * @param string  $name
     */
    public function register_session($name, Session $session)
    {
        $name = strtolower($name);
        $this->sessions[$name] = $session;
    }
    /**
     * Checks whether session with specified name is registered.
     *
     * @param string $name
     *
     * @return Boolean
     */
    public function has_session($name)
    {
        return isset($this->sessions[strtolower($name)]);
    }
    /**
     * Sets default session name to use.
     *
     * @param string $name name of the registered session
     *
     * @throws \InvalidArgumentException
     */
    public function set_default_session_name($name)
    {
        $name = strtolower($name);
        if (!isset($this->sessions[$name])) {
            throw new \InvalidArgumentException(sprintf('Session "%s" is not registered.', $name));
        }
        $this->default_session_name = $name;
    }
    /**
     * Returns default session name or null if none.
     *
     * @return null|string
     */
    public function get_default_session_name()
    {
        return $this->default_session_name;
    }
    /**
     * Returns registered session by it's name or active one and automatically starts it if required.
     *
     * @param string $name session name
     *
     * @return Session
     *
     * @throws \InvalidArgumentException If the named session is not registered
     */
    public function get_session($name = null)
    {
        $session = $this->locate_session($name);
        // start session if needed
        if (!$session->is_started()) {
            $session->start();
        }
        return $session;
    }
    /**
     * Checks whether a named session (or the default session) has already been started
     *
     * @param string $name session name - if null then the default session will be checked
     *
     * @return bool whether the session has been started
     *
     * @throws \InvalidArgumentException If the named session is not registered
     */
    public function is_session_started($name = null)
    {
        $session = $this->locate_session($name);
        return $session->is_started();
    }
    /**
     * Returns session asserter.
     *
     * @param Session|string $session session object or name
     *
     * @return WebAssert
     */
    public function assert_session($session = null)
    {
        if (!$session instanceof Session) {
            $session = $this->get_session($session);
        }
        return new Web_Assert($session);
    }
    /**
     * Resets all started sessions.
     */
    public function reset_sessions()
    {
        foreach ($this->sessions as $session) {
            if ($session->is_started()) {
                $session->reset();
            }
        }
    }
    /**
     * Restarts all started sessions.
     */
    public function restart_sessions()
    {
        foreach ($this->sessions as $session) {
            if ($session->is_started()) {
                $session->restart();
            }
        }
    }
    /**
     * Stops all started sessions.
     */
    public function stop_sessions()
    {
        foreach ($this->sessions as $session) {
            if ($session->is_started()) {
                $session->stop();
            }
        }
    }
    /**
     * Returns the named or default session without starting it.
     *
     * @param string $name session name
     *
     * @return Session
     *
     * @throws \InvalidArgumentException If the named session is not registered
     */
    protected function locate_session($name = null)
    {
        $name = strtolower($name) ?: $this->default_session_name;
        if (null === $name) {
            throw new \InvalidArgumentException('Specify session name to get');
        }
        if (!isset($this->sessions[$name])) {
            throw new \InvalidArgumentException(sprintf('Session "%s" is not registered.', $name));
        }
        return $this->sessions[$name];
    }
}