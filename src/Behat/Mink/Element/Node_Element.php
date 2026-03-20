<?php

declare (strict_types=1);
/*
 * This file is part of the Mink package.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Behat\Mink\Element;

use Behat\Mink\Exception\Element_Not_Found_Exception;
use Behat\Mink\Session;
/**
 * Page element node.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Node_Element extends Traversable_Element
{
    private $xpath;
    /**
     * Initializes node element.
     *
     * @param string  $xpath   element xpath
     * @param Session $session session instance
     */
    public function __construct($xpath, Session $session)
    {
        $this->xpath = $xpath;
        parent::__construct($session);
    }
    /**
     * Returns XPath for handled element.
     *
     * @return string
     */
    public function get_xpath()
    {
        return $this->xpath;
    }
    /**
     * Returns parent element to the current one.
     *
     * @return NodeElement
     */
    public function get_parent()
    {
        return $this->find('xpath', '..');
    }
    /**
     * Returns current node tag name.
     *
     * The value is always returned in lowercase to allow an easy comparison.
     *
     * @return string
     */
    public function get_tag_name()
    {
        return strtolower($this->get_driver()->get_tag_name($this->get_xpath()));
    }
    /**
     * Returns the value of the form field or option element.
     *
     * For checkbox fields, the value is a boolean indicating whether the checkbox is checked.
     * For radio buttons, the value is the value of the selected button in the radio group
     *      or null if no button is selected.
     * For single select boxes, the value is the value of the selected option.
     * For multiple select boxes, the value is an array of selected option values.
     * for file inputs, the return value is undefined given that browsers don't allow accessing
     *      the value of file inputs for security reasons. Some drivers may allow accessing the
     *      path of the file set in the field, but this is not required if it cannot be implemented.
     * For textarea elements and all textual fields, the value is the content of the field.
     * Form option elements, the value is the value of the option (the value attribute or the text
     *      content if the attribute is not set).
     *
     * Calling this method on other elements than form fields or option elements is not allowed.
     *
     * @return string|bool|array
     */
    public function get_value()
    {
        return $this->get_driver()->get_value($this->get_xpath());
    }
    /**
     * Sets the value of the form field.
     *
     * Calling this method on other elements than form fields is not allowed.
     *
     * @param string|bool|array $value
     *
     * @see NodeElement::getValue for the format of the value for each type of field
     */
    public function set_value($value)
    {
        $this->get_driver()->set_value($this->get_xpath(), $value);
    }
    /**
     * Checks whether element has attribute with specified name.
     *
     * @param string $name
     *
     * @return Boolean
     */
    public function has_attribute($name)
    {
        return null !== $this->get_driver()->get_attribute($this->get_xpath(), $name);
    }
    /**
     * Returns specified attribute value.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function get_attribute($name)
    {
        return $this->get_driver()->get_attribute($this->get_xpath(), $name);
    }
    /**
     * Checks whether an element has a named CSS class
     *
     * @param string $className Name of the class
     *
     * @return boolean
     */
    public function has_class($class_name)
    {
        if ($this->has_attribute('class')) {
            return in_array($class_name, explode(' ', $this->get_attribute('class')));
        }
        return false;
    }
    /**
     * Clicks current node.
     */
    public function click()
    {
        $this->get_driver()->click($this->get_xpath());
    }
    /**
     * Presses current button.
     */
    public function press()
    {
        $this->click();
    }
    /**
     * Double-clicks current node.
     */
    public function double_click()
    {
        $this->get_driver()->double_click($this->get_xpath());
    }
    /**
     * Right-clicks current node.
     */
    public function right_click()
    {
        $this->get_driver()->right_click($this->get_xpath());
    }
    /**
     * Checks current node if it's a checkbox field.
     */
    public function check()
    {
        $this->get_driver()->check($this->get_xpath());
    }
    /**
     * Unchecks current node if it's a checkbox field.
     */
    public function uncheck()
    {
        $this->get_driver()->uncheck($this->get_xpath());
    }
    /**
     * Checks whether current node is checked if it's a checkbox or radio field.
     *
     * Calling this method on any other elements is not allowed.
     *
     * @return Boolean
     */
    public function is_checked()
    {
        return (bool) $this->get_driver()->is_checked($this->get_xpath());
    }
    /**
     * Selects specified option for select field or specified radio button in the group
     *
     * If the current node is a select box, this selects the option found by its value or
     * its text.
     * If the current node is a radio button, this selects the radio button with the given
     * value in the radio button group of the current node.
     *
     * Calling this method on any other elements is not allowed.
     *
     * @param string  $option
     * @param Boolean $multiple whether the option should be added to the selection for multiple selects
     *
     * @throws ElementNotFoundException when the option is not found in the select box
     */
    public function select_option($option, $multiple = false)
    {
        if ('select' !== $this->get_tag_name()) {
            $this->get_driver()->select_option($this->get_xpath(), $option, $multiple);
            return;
        }
        $opt = $this->find('named', ['option', $this->get_selectors_handler()->xpath_literal($option)]);
        if (null === $opt) {
            throw $this->element_not_found('select option', 'value|text', $option);
        }
        $this->get_driver()->select_option($this->get_xpath(), $opt->get_value(), $multiple);
    }
    /**
     * Checks whether current node is selected if it's a option field.
     *
     * Calling this method on any other elements is not allowed.
     *
     * @return Boolean
     */
    public function is_selected()
    {
        return (bool) $this->get_driver()->is_selected($this->get_xpath());
    }
    /**
     * Attach file to current node if it's a file input.
     *
     * Calling this method on any other elements than file input is not allowed.
     *
     * @param string $path path to file (local)
     */
    public function attach_file($path)
    {
        $this->get_driver()->attach_file($this->get_xpath(), $path);
    }
    /**
     * Checks whether current node is visible on page.
     *
     * @return Boolean
     */
    public function is_visible()
    {
        return (bool) $this->get_driver()->is_visible($this->get_xpath());
    }
    /**
     * Simulates a mouse over on the element.
     */
    public function mouse_over()
    {
        $this->get_driver()->mouse_over($this->get_xpath());
    }
    /**
     * Drags current node onto other node.
     *
     * @param ElementInterface $destination other node
     */
    public function drag_to(Element_Interface $destination)
    {
        $this->get_driver()->drag_to($this->get_xpath(), $destination->get_xpath());
    }
    /**
     * Brings focus to element.
     */
    public function focus()
    {
        $this->get_driver()->focus($this->get_xpath());
    }
    /**
     * Removes focus from element.
     */
    public function blur()
    {
        $this->get_driver()->blur($this->get_xpath());
    }
    /**
     * Presses specific keyboard key.
     *
     * @param string|integer $char     could be either char ('b') or char-code (98)
     * @param string         $modifier keyboard modifier (could be 'ctrl', 'alt', 'shift' or 'meta')
     */
    public function key_press($char, $modifier = null)
    {
        $this->get_driver()->key_press($this->get_xpath(), $char, $modifier);
    }
    /**
     * Pressed down specific keyboard key.
     *
     * @param string|integer $char     could be either char ('b') or char-code (98)
     * @param string         $modifier keyboard modifier (could be 'ctrl', 'alt', 'shift' or 'meta')
     */
    public function key_down($char, $modifier = null)
    {
        $this->get_driver()->key_down($this->get_xpath(), $char, $modifier);
    }
    /**
     * Pressed up specific keyboard key.
     *
     * @param string|integer $char     could be either char ('b') or char-code (98)
     * @param string         $modifier keyboard modifier (could be 'ctrl', 'alt', 'shift' or 'meta')
     */
    public function key_up($char, $modifier = null)
    {
        $this->get_driver()->key_up($this->get_xpath(), $char, $modifier);
    }
    /**
     * Submits the form.
     *
     * Calling this method on anything else than form elements is not allowed.
     */
    public function submit()
    {
        $this->get_driver()->submit_form($this->get_xpath());
    }
}