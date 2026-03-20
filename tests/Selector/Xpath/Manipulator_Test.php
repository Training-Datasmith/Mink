<?php

declare(strict_types=1);

namespace Behat\Mink\Tests\Selector\Xpath;

use Behat\Mink\Selector\Xpath\Manipulator;

class ManipulatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getPrependedXpath
     */
    public function testPrepend($prefix, $xpath, $expectedXpath)
    {
        $manipulator = new Manipulator();

        $this->assertEquals($expectedXpath, $manipulator->prepend($xpath, $prefix));
    }

    public function getPrependedXpath()
    {
        return [
            'simple' => [
                'some_xpath',
                'some_tag1',
                'some_xpath/some_tag1',
            ],
            'with slash' => [
                'some_xpath',
                '/some_tag1',
                'some_xpath/some_tag1',
            ],
            'union' => [
                'some_xpath',
                'some_tag1 | some_tag2',
                'some_xpath/some_tag1 | some_xpath/some_tag2',
            ],
            'wrapped union' => [
                'some_xpath',
                '(some_tag1 | some_tag2)/some_child',
                '(some_xpath/some_tag1 | some_xpath/some_tag2)/some_child',
            ],
            'multiple wrapped union' => [
                'some_xpath',
                '( ( some_tag1 | some_tag2)/some_child | some_tag3)/leaf',
                '( ( some_xpath/some_tag1 | some_xpath/some_tag2)/some_child | some_xpath/some_tag3)/leaf',
            ],
            'parent union' => [
                'some_xpath | another_xpath',
                'some_tag1 | some_tag2',
                '(some_xpath | another_xpath)/some_tag1 | (some_xpath | another_xpath)/some_tag2',
            ],
            'complex condition' => [
                'some_xpath',
                'some_tag1 | some_tag2[@foo = "bar|"] | some_tag3[foo | bar]',
                'some_xpath/some_tag1 | some_xpath/some_tag2[@foo = "bar|"] | some_xpath/some_tag3[foo | bar]',
            ],
            'multiline' => [
                'some_xpath',
                "some_tag1 | some_tag2[@foo =\n 'bar|'']\n | some_tag3[foo | bar]",
                "some_xpath/some_tag1 | some_xpath/some_tag2[@foo =\n 'bar|''] | some_xpath/some_tag3[foo | bar]",
            ],
        ];
    }
}
