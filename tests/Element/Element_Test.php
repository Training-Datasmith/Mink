<?php

declare(strict_types=1);

namespace Behat\Mink\Tests\Element;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Selector\SelectorsHandler;
use Behat\Mink\Session;

abstract class ElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Session.
     *
     * @var Session
     */
    protected $session;

    /**
     * @var DriverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $driver;

    /**
     * Selectors.
     *
     * @var SelectorsHandler|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $selectors;

    protected function setUp()
    {
        $this->driver = $this->getMockBuilder('Behat\Mink\Driver\DriverInterface')->getMock();
        $this->driver
            ->expects($this->once())
            ->method('setSession');

        $this->selectors = $this->getMockBuilder('Behat\Mink\Selector\SelectorsHandler')->getMock();
        $this->session = new Session($this->driver, $this->selectors);

        $this->selectors
            ->expects($this->any())
            ->method('xpathLiteral')
            ->will($this->returnArgument(0));
    }

    protected function mockNamedFinder($xpath, array $results, $locator, $times = 2)
    {
        if (!is_array($results[0])) {
            $results = [$results, []];
        }

        // In case of empty results, a second call will be done using the partial selector
        $processedResults = [];
        foreach ($results as $result) {
            $processedResults[] = $result;
            if (empty($result)) {
                $processedResults[] = $result;
                $times++;
            }
        }

        $returnValue = call_user_func_array([$this, 'onConsecutiveCalls'], $processedResults);

        $this->driver
            ->expects($this->exactly($times))
            ->method('find')
            ->with('//html' . $xpath)
            ->will($returnValue);

        $this->selectors
            ->expects($this->exactly($times))
            ->method('selectorToXpath')
            ->with($this->logicalOr('named_exact', 'named_partial'), $locator)
            ->will($this->returnValue($xpath));
    }
}
