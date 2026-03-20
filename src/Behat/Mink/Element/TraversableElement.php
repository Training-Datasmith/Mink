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
/**
 * Traversable element.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
abstract class Traversable_Element extends Element
{
    /**
     * Finds element by its id.
     *
     * @param string $id element id
     *
     * @return NodeElement|null
     */
    public function find_by_id($id)
    {
        $id = $this->get_selectors_handler()->xpath_literal($id);
        return $this->find('named', ['id', $id]);
    }
    /**
     * Checks whether element has a link with specified locator.
     *
     * @param string $locator link id, title, text or image alt
     *
     * @return Boolean
     */
    public function has_link($locator)
    {
        return null !== $this->find_link($locator);
    }
    /**
     * Finds link with specified locator.
     *
     * @param string $locator link id, title, text or image alt
     *
     * @return NodeElement|null
     */
    public function find_link($locator)
    {
        return $this->find('named', ['link', $this->get_selectors_handler()->xpath_literal($locator)]);
    }
    /**
     * Clicks link with specified locator.
     *
     * @param string $locator link id, title, text or image alt
     *
     * @throws ElementNotFoundException
     */
    public function click_link($locator)
    {
        $link = $this->find_link($locator);
        if (null === $link) {
            throw $this->element_not_found('link', 'id|title|alt|text', $locator);
        }
        $link->click();
    }
    /**
     * Checks whether element has a button (input[type=submit|image|button|reset], button) with specified locator.
     *
     * @param string $locator button id, value or alt
     *
     * @return Boolean
     */
    public function has_button($locator)
    {
        return null !== $this->find_button($locator);
    }
    /**
     * Finds button (input[type=submit|image|button|reset], button) with specified locator.
     *
     * @param string $locator button id, value or alt
     *
     * @return NodeElement|null
     */
    public function find_button($locator)
    {
        return $this->find('named', ['button', $this->get_selectors_handler()->xpath_literal($locator)]);
    }
    /**
     * Presses button (input[type=submit|image|button|reset], button) with specified locator.
     *
     * @param string $locator button id, value or alt
     *
     * @throws ElementNotFoundException
     */
    public function press_button($locator)
    {
        $button = $this->find_button($locator);
        if (null === $button) {
            throw $this->element_not_found('button', 'id|name|title|alt|value', $locator);
        }
        $button->press();
    }
    /**
     * Checks whether element has a field (input, textarea, select) with specified locator.
     *
     * @param string $locator input id, name or label
     *
     * @return Boolean
     */
    public function has_field($locator)
    {
        return null !== $this->find_field($locator);
    }
    /**
     * Finds field (input, textarea, select) with specified locator.
     *
     * @param string $locator input id, name or label
     *
     * @return NodeElement|null
     */
    public function find_field($locator)
    {
        return $this->find('named', ['field', $this->get_selectors_handler()->xpath_literal($locator)]);
    }
    /**
     * Fills in field (input, textarea, select) with specified locator.
     *
     * @param string $locator input id, name or label
     * @param string $value   value
     *
     * @throws ElementNotFoundException
     *
     * @see NodeElement::setValue
     */
    public function fill_field($locator, $value)
    {
        $field = $this->find_field($locator);
        if (null === $field) {
            throw $this->element_not_found('form field', 'id|name|label|value', $locator);
        }
        $field->set_value($value);
    }
    /**
     * Checks whether element has a checkbox with specified locator, which is checked.
     *
     * @param string $locator input id, name or label
     *
     * @return Boolean
     *
     * @see NodeElement::isChecked
     */
    public function has_checked_field($locator)
    {
        $field = $this->find_field($locator);
        return null !== $field && $field->is_checked();
    }
    /**
     * Checks whether element has a checkbox with specified locator, which is unchecked.
     *
     * @param string $locator input id, name or label
     *
     * @return Boolean
     *
     * @see NodeElement::isChecked
     */
    public function has_unchecked_field($locator)
    {
        $field = $this->find_field($locator);
        return null !== $field && !$field->is_checked();
    }
    /**
     * Checks checkbox with specified locator.
     *
     * @param string $locator input id, name or label
     *
     * @throws ElementNotFoundException
     */
    public function check_field($locator)
    {
        $field = $this->find_field($locator);
        if (null === $field) {
            throw $this->element_not_found('form field', 'id|name|label|value', $locator);
        }
        $field->check();
    }
    /**
     * Unchecks checkbox with specified locator.
     *
     * @param string $locator input id, name or label
     *
     * @throws ElementNotFoundException
     */
    public function uncheck_field($locator)
    {
        $field = $this->find_field($locator);
        if (null === $field) {
            throw $this->element_not_found('form field', 'id|name|label|value', $locator);
        }
        $field->uncheck();
    }
    /**
     * Checks whether element has a select field with specified locator.
     *
     * @param string $locator select id, name or label
     *
     * @return Boolean
     */
    public function has_select($locator)
    {
        return $this->has('named', ['select', $this->get_selectors_handler()->xpath_literal($locator)]);
    }
    /**
     * Selects option from select field with specified locator.
     *
     * @param string  $locator  input id, name or label
     * @param string  $value    option value
     * @param Boolean $multiple select multiple options
     *
     * @throws ElementNotFoundException
     *
     * @see NodeElement::selectOption
     */
    public function select_field_option($locator, $value, $multiple = false)
    {
        $field = $this->find_field($locator);
        if (null === $field) {
            throw $this->element_not_found('form field', 'id|name|label|value', $locator);
        }
        $field->select_option($value, $multiple);
    }
    /**
     * Checks whether element has a table with specified locator.
     *
     * @param string $locator table id or caption
     *
     * @return Boolean
     */
    public function has_table($locator)
    {
        return $this->has('named', ['table', $this->get_selectors_handler()->xpath_literal($locator)]);
    }
    /**
     * Attach file to file field with specified locator.
     *
     * @param string $locator input id, name or label
     * @param string $path    path to file
     *
     * @throws ElementNotFoundException
     *
     * @see NodeElement::attachFile
     */
    public function attach_file_to_field($locator, $path)
    {
        $field = $this->find_field($locator);
        if (null === $field) {
            throw $this->element_not_found('form field', 'id|name|label|value', $locator);
        }
        $field->attach_file($path);
    }
}