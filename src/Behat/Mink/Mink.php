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
    private ?string $default_session_name = null;
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
    /**
     * Registers a new named session.
     *
     * The name is normalised to lowercase so 'Firefox' and 'firefox' refer to the same session.
     *
     * @param string  $name    Unique case-insensitive name for this session
     * @param Session $session Configured but not yet started session instance
     *
     * @return void
     */
    public function register_session(string $name, Session $session): void
    {
        $name = strtolower($name);
        $this->sessions[$name] = $session;
    }
    /**
     * Checks whether a session with the specified name is registered.
     *
     * @param string $name Case-insensitive session name to look up
     *
     * @return bool True if the session is registered, false otherwise
     */
    public function has_session(string $name): bool
    {
        return isset($this->sessions[strtolower($name)]);
    }
    /**
     * Sets the default session name used when no name is passed to get_session().
     *
     * @param string $name Case-insensitive name of an already-registered session
     *
     * @return void
     *
     * @throws \InvalidArgumentException If no session with the given name has been registered
     */
    public function set_default_session_name(string $name): void
    {
        $name = strtolower($name);
        if (!isset($this->sessions[$name])) {
            throw new \InvalidArgumentException(sprintf('Session "%s" is not registered.', $name));
        }
        $this->default_session_name = $name;
    }
    /**
     * Returns the default session name or null if none has been set.
     *
     * @return string|null The default session name in lowercase, or null if unset
     */
    public function get_default_session_name(): ?string
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
    /**
     * Returns the registered session by name (or the default session), starting it if not yet started.
     *
     * @param string|null $name Case-insensitive session name, or null to use the default session
     *
     * @return Session The started session instance
     *
     * @throws \InvalidArgumentException If no name is given and no default is set, or the name is not registered
     */
    public function get_session(?string $name = null): Session
    {
        $session = $this->locate_session($name);
        // start session if needed
        if (!$session->is_started()) {
            $session->start();
        }
        return $session;
    }
    /**
     * Checks whether a named session (or the default session) has already been started.
     *
     * @param string|null $name Case-insensitive session name, or null to check the default session
     *
     * @return bool True if the session has been started, false otherwise
     *
     * @throws \InvalidArgumentException If the named session is not registered
     */
    public function is_session_started(?string $name = null): bool
    {
        $session = $this->locate_session($name);
        return $session->is_started();
    }
    /**
     * Returns a Web_Assert helper for the given session (starts it if needed).
     *
     * @param Session|string|null $session Session instance, case-insensitive name, or null for the default session
     *
     * @return Web_Assert An assertion helper bound to the resolved session
     */
    public function assert_session(Session|string|null $session = null): Web_Assert
    {
        if (!$session instanceof Session) {
            $session = $this->get_session($session);
        }
        return new Web_Assert($session);
    }
    /**
     * Resets all started sessions.
     */
    /**
     * Resets all started sessions to their initial state without stopping them.
     *
     * @return void
     */
    public function reset_sessions(): void
    {
        foreach ($this->sessions as $session) {
            if ($session->is_started()) {
                $session->reset();
            }
        }
    }
    /**
     * Restarts all started sessions (stops then starts each).
     *
     * @return void
     */
    public function restart_sessions(): void
    {
        foreach ($this->sessions as $session) {
            if ($session->is_started()) {
                $session->restart();
            }
        }
    }
    /**
     * Stops all started sessions, releasing browser resources.
     *
     * @return void
     */
    public function stop_sessions(): void
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
     * @param string|null $name Case-insensitive session name, or null to use the default session
     *
     * @return Session The session instance (not yet started if it has not been started)
     *
     * @throws \InvalidArgumentException If no name is given and no default is set, or the name is not registered
     */
    protected function locate_session(?string $name = null): Session
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