<?php

declare(strict_types=1);

namespace Behat\Mink\Tests\Driver\Js;

use Behat\Mink\Tests\Driver\TestCase;

class ChangeEventTest extends TestCase
{
    /**
     * 'change' event should be fired after selecting an <option> in a <select>
     *
     * TODO check whether this test is redundant with other change event tests.
     */
    public function testIssue255()
    {
        $session = $this->getSession();
        $session->visit($this->pathTo('/issue255.html'));

        $session->getPage()->selectFieldOption('foo_select', 'Option 3');

        $session->wait(2000, '$("#output_foo_select").text() != ""');
        $this->assertEquals('onChangeSelect', $session->getPage()->find('css', '#output_foo_select')->getText());
    }

    public function testIssue178()
    {
        $session = $this->getSession();
        $session->visit($this->pathTo('/issue178.html'));

        $this->findById('source')->setValue('foo');
        $this->assertEquals('foo', $this->findById('target')->getText());
    }

    /**
     * @dataProvider setValueChangeEventDataProvider
     * @group change-event-detector
     */
    public function testSetValueChangeEvent($elementId, $valueForEmpty, $valueForFilled = '')
    {
        $this->getSession()->visit($this->pathTo('/element_change_detector.html'));
        $page = $this->getSession()->getPage();

        $input = $this->findById($elementId);
        $this->assertNull($page->findById($elementId.'-result'));

        // Verify setting value, when control is initially empty.
        $input->setValue($valueForEmpty);
        $this->assertElementChangeCount($elementId, 'initial value setting triggers change event');

        if ($valueForFilled) {
            // Verify setting value, when control already has a value.
            $this->findById('results')->click();
            $input->setValue($valueForFilled);
            $this->assertElementChangeCount($elementId, 'value change triggers change event');
        }
    }

    public function setValueChangeEventDataProvider()
    {
        return [
            'input default' => ['the-input-default', 'from empty', 'from existing'],
            'input text' => ['the-input-text', 'from empty', 'from existing'],
            'input email' => ['the-email', 'from empty', 'from existing'],
            'textarea' => ['the-textarea', 'from empty', 'from existing'],
            'file' => ['the-file', 'from empty', 'from existing'],
            'select' => ['the-select', '30'],
            'radio' => ['the-radio-m', 'm'],
        ];
    }

    /**
     * @dataProvider selectOptionChangeEventDataProvider
     * @group change-event-detector
     */
    public function testSelectOptionChangeEvent($elementId, $elementValue)
    {
        $this->getSession()->visit($this->pathTo('/element_change_detector.html'));
        $page = $this->getSession()->getPage();

        $input = $this->findById($elementId);
        $this->assertNull($page->findById($elementId.'-result'));

        $input->selectOption($elementValue);
        $this->assertElementChangeCount($elementId);
    }

    public function selectOptionChangeEventDataProvider()
    {
        return [
            'select' => ['the-select', '30'],
            'radio' => ['the-radio-m', 'm'],
        ];
    }

    /**
     * @dataProvider checkboxTestWayDataProvider
     * @group change-event-detector
     */
    public function testCheckChangeEvent($useSetValue)
    {
        $this->getSession()->visit($this->pathTo('/element_change_detector.html'));
        $page = $this->getSession()->getPage();

        $checkbox = $this->findById('the-unchecked-checkbox');
        $this->assertNull($page->findById('the-unchecked-checkbox-result'));

        if ($useSetValue) {
            $checkbox->setValue(true);
        } else {
            $checkbox->check();
        }

        $this->assertElementChangeCount('the-unchecked-checkbox');
    }

    /**
     * @dataProvider checkboxTestWayDataProvider
     * @group change-event-detector
     */
    public function testUncheckChangeEvent($useSetValue)
    {
        $this->getSession()->visit($this->pathTo('/element_change_detector.html'));
        $page = $this->getSession()->getPage();

        $checkbox = $this->findById('the-checked-checkbox');
        $this->assertNull($page->findById('the-checked-checkbox-result'));

        if ($useSetValue) {
            $checkbox->setValue(false);
        } else {
            $checkbox->uncheck();
        }

        $this->assertElementChangeCount('the-checked-checkbox');
    }

    public function checkboxTestWayDataProvider()
    {
        return [
            [true],
            [false],
        ];
    }

    private function assertElementChangeCount($elementId, $message = '')
    {
        $counterElement = $this->getSession()->getPage()->findById($elementId.'-result');
        $actualCount = null === $counterElement ? 0 : $counterElement->getText();

        $this->assertEquals('1', $actualCount, $message);
    }
}
