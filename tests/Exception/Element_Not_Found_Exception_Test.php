<?php

declare(strict_types=1);

namespace Behat\Mink\Tests\Exception;

use Behat\Mink\Exception\ElementNotFoundException;

class ElementNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideExceptionMessage
     */
    public function testBuildMessage($message, $type, $selector = null, $locator = null)
    {
        $session = $this->getMockBuilder('Behat\Mink\Session')
            ->disableOriginalConstructor()
            ->getMock();

        $exception = new ElementNotFoundException($session, $type, $selector, $locator);

        $this->assertEquals($message, $exception->getMessage());
    }

    public function provideExceptionMessage()
    {
        return [
            ['Tag not found.', null],
            ['Field not found.', 'field'],
            ['Tag matching locator "foobar" not found.', null, null, 'foobar'],
            ['Tag matching css "foobar" not found.', null, 'css', 'foobar'],
            ['Field matching xpath "foobar" not found.', 'Field', 'xpath', 'foobar'],
            ['Tag with name "foobar" not found.', null, 'name', 'foobar'],
        ];
    }
}
