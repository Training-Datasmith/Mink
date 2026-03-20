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

use Behat\Mink\Element\Element;
use Behat\Mink\Element\Element_Interface;
use Behat\Mink\Element\Node_Element;
use Behat\Mink\Element\Traversable_Element;
use Behat\Mink\Exception\Element_Html_Exception;
use Behat\Mink\Exception\Element_Not_Found_Exception;
use Behat\Mink\Exception\Element_Text_Exception;
use Behat\Mink\Exception\Expectation_Exception;
use Behat\Mink\Exception\Response_Text_Exception;
/**
 * Mink web assertions tool.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Web_Assert
{
    protected $session;
    /**
     * Initializes assertion engine.
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    /**
     * Checks that current session address is equals to provided one.
     *
     * @param string $page
     *
     * @throws ExpectationException
     */
    public function address_equals($page)
    {
        $expected = $this->clean_url($page);
        $actual = $this->get_current_url_path();
        $this->assert($actual === $expected, sprintf('Current page is "%s", but "%s" expected.', $actual, $expected));
    }
    /**
     * Checks that current session address is not equals to provided one.
     *
     * @param string $page
     *
     * @throws ExpectationException
     */
    public function address_not_equals($page)
    {
        $expected = $this->clean_url($page);
        $actual = $this->get_current_url_path();
        $this->assert($actual !== $expected, sprintf('Current page is "%s", but should not be.', $actual));
    }
    /**
     * Checks that current session address matches regex.
     *
     * @param string $regex
     *
     * @throws ExpectationException
     */
    public function address_matches($regex)
    {
        $actual = $this->get_current_url_path();
        $message = sprintf('Current page "%s" does not match the regex "%s".', $actual, $regex);
        $this->assert((bool) preg_match($regex, $actual), $message);
    }
    /**
     * Checks that specified cookie exists and its value equals to a given one
     *
     * @param string $name  cookie name
     * @param string $value cookie value
     *
     * @throws ExpectationException
     */
    public function cookie_equals(string $name, $value)
    {
        $this->cookie_exists($name);
        $actual_value = $this->session->get_cookie($name);
        $message = sprintf('Cookie "%s" value is "%s", but should be "%s".', $name, $actual_value, $value);
        $this->assert($actual_value == $value, $message);
    }
    /**
     * Checks that specified cookie exists
     *
     * @param string $name cookie name
     *
     * @throws ExpectationException
     */
    public function cookie_exists(string $name)
    {
        $message = sprintf('Cookie "%s" is not set, but should be.', $name);
        $this->assert($this->session->get_cookie($name) !== null, $message);
    }
    /**
     * Checks that current response code equals to provided one.
     *
     * @param integer $code
     *
     * @throws ExpectationException
     */
    public function status_code_equals($code)
    {
        $actual = $this->session->get_status_code();
        $message = sprintf('Current response status code is %d, but %d expected.', $actual, $code);
        $this->assert(intval($code) === intval($actual), $message);
    }
    /**
     * Checks that current response code not equals to provided one.
     *
     * @param integer $code
     *
     * @throws ExpectationException
     */
    public function status_code_not_equals($code)
    {
        $actual = $this->session->get_status_code();
        $message = sprintf('Current response status code is %d, but should not be.', $actual);
        $this->assert(intval($code) !== intval($actual), $message);
    }
    /**
     * Checks that current page contains text.
     *
     *
     * @throws ResponseTextException
     */
    public function page_text_contains(string $text)
    {
        $actual = $this->session->get_page()->get_text();
        $actual = preg_replace('/\s+/u', ' ', $actual);
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The text "%s" was not found anywhere in the text of the current page.', $text);
        $this->assert_response_text((bool) preg_match($regex, $actual), $message);
    }
    /**
     * Checks that current page does not contains text.
     *
     *
     * @throws ResponseTextException
     */
    public function page_text_not_contains(string $text)
    {
        $actual = $this->session->get_page()->get_text();
        $actual = preg_replace('/\s+/u', ' ', $actual);
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The text "%s" appears in the text of this page, but it should not.', $text);
        $this->assert_response_text(!preg_match($regex, $actual), $message);
    }
    /**
     * Checks that current page text matches regex.
     *
     *
     * @throws ResponseTextException
     */
    public function page_text_matches(string $regex)
    {
        $actual = $this->session->get_page()->get_text();
        $message = sprintf('The pattern %s was not found anywhere in the text of the current page.', $regex);
        $this->assert_response_text((bool) preg_match($regex, $actual), $message);
    }
    /**
     * Checks that current page text does not matches regex.
     *
     *
     * @throws ResponseTextException
     */
    public function page_text_not_matches(string $regex)
    {
        $actual = $this->session->get_page()->get_text();
        $message = sprintf('The pattern %s was found in the text of the current page, but it should not.', $regex);
        $this->assert_response_text(!preg_match($regex, $actual), $message);
    }
    /**
     * Checks that page HTML (response content) contains text.
     *
     *
     * @throws ExpectationException
     */
    public function response_contains(string $text)
    {
        $actual = $this->session->get_page()->get_content();
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The string "%s" was not found anywhere in the HTML response of the current page.', $text);
        $this->assert((bool) preg_match($regex, $actual), $message);
    }
    /**
     * Checks that page HTML (response content) does not contains text.
     *
     *
     * @throws ExpectationException
     */
    public function response_not_contains(string $text)
    {
        $actual = $this->session->get_page()->get_content();
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The string "%s" appears in the HTML response of this page, but it should not.', $text);
        $this->assert(!preg_match($regex, $actual), $message);
    }
    /**
     * Checks that page HTML (response content) matches regex.
     *
     *
     * @throws ExpectationException
     */
    public function response_matches(string $regex)
    {
        $actual = $this->session->get_page()->get_content();
        $message = sprintf('The pattern %s was not found anywhere in the HTML response of the page.', $regex);
        $this->assert((bool) preg_match($regex, $actual), $message);
    }
    /**
     * Checks that page HTML (response content) does not matches regex.
     *
     * @param $regex
     *
     * @throws ExpectationException
     */
    public function response_not_matches(string $regex)
    {
        $actual = $this->session->get_page()->get_content();
        $message = sprintf('The pattern %s was found in the HTML response of the page, but it should not.', $regex);
        $this->assert(!preg_match($regex, $actual), $message);
    }
    /**
     * Checks that there is specified number of specific elements on the page.
     *
     * @param string           $selectorType element selector type (css, xpath)
     * @param string|array     $selector     element selector
     * @param integer          $count        expected count
     * @param ElementInterface $container    document to check against
     *
     * @throws ExpectationException
     */
    public function elements_count($selector_type, $selector, $count, Element_Interface $container = null)
    {
        $container = $container ?: $this->session->get_page();
        $nodes = $container->find_all($selector_type, $selector);
        $message = sprintf('%d %s found on the page, but should be %d.', count($nodes), $this->get_matching_element_representation($selector_type, $selector, count($nodes) !== 1), $count);
        $this->assert(intval($count) === count($nodes), $message);
    }
    /**
     * Checks that specific element exists on the current page.
     *
     * @param string           $selectorType element selector type (css, xpath)
     * @param string|array     $selector     element selector
     * @param ElementInterface $container    document to check against
     *
     * @return NodeElement
     *
     * @throws ElementNotFoundException
     */
    public function element_exists($selector_type, $selector, Element_Interface $container = null)
    {
        $container = $container ?: $this->session->get_page();
        $node = $container->find($selector_type, $selector);
        if (null === $node) {
            if (is_array($selector)) {
                $selector = implode(' ', $selector);
            }
            throw new Element_Not_Found_Exception($this->session, 'element', $selector_type, $selector);
        }
        return $node;
    }
    /**
     * Checks that specific element does not exists on the current page.
     *
     * @param string           $selectorType element selector type (css, xpath)
     * @param string|array     $selector     element selector
     * @param ElementInterface $container    document to check against
     *
     * @throws ExpectationException
     */
    public function element_not_exists($selector_type, $selector, Element_Interface $container = null)
    {
        $container = $container ?: $this->session->get_page();
        $node = $container->find($selector_type, $selector);
        $message = sprintf('An %s appears on this page, but it should not.', $this->get_matching_element_representation($selector_type, $selector));
        $this->assert(null === $node, $message);
    }
    /**
     * Checks that specific element contains text.
     *
     * @param string       $selectorType element selector type (css, xpath)
     * @param string|array $selector     element selector
     * @param string       $text         expected text
     *
     * @throws ElementTextException
     */
    public function element_text_contains($selector_type, $selector, string $text)
    {
        $element = $this->element_exists($selector_type, $selector);
        $actual = $element->get_text();
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The text "%s" was not found in the text of the %s.', $text, $this->get_matching_element_representation($selector_type, $selector));
        $this->assert_element_text((bool) preg_match($regex, $actual), $message, $element);
    }
    /**
     * Checks that specific element does not contains text.
     *
     * @param string       $selectorType element selector type (css, xpath)
     * @param string|array $selector     element selector
     * @param string       $text         expected text
     *
     * @throws ElementTextException
     */
    public function element_text_not_contains($selector_type, $selector, string $text)
    {
        $element = $this->element_exists($selector_type, $selector);
        $actual = $element->get_text();
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The text "%s" appears in the text of the %s, but it should not.', $text, $this->get_matching_element_representation($selector_type, $selector));
        $this->assert_element_text(!preg_match($regex, $actual), $message, $element);
    }
    /**
     * Checks that specific element contains HTML.
     *
     * @param string       $selectorType element selector type (css, xpath)
     * @param string|array $selector     element selector
     * @param string       $html         expected text
     *
     * @throws ElementHtmlException
     */
    public function element_contains($selector_type, $selector, string $html)
    {
        $element = $this->element_exists($selector_type, $selector);
        $actual = $element->get_html();
        $regex = '/' . preg_quote($html, '/') . '/ui';
        $message = sprintf('The string "%s" was not found in the HTML of the %s.', $html, $this->get_matching_element_representation($selector_type, $selector));
        $this->assert_element((bool) preg_match($regex, $actual), $message, $element);
    }
    /**
     * Checks that specific element does not contains HTML.
     *
     * @param string       $selectorType element selector type (css, xpath)
     * @param string|array $selector     element selector
     * @param string       $html         expected text
     *
     * @throws ElementHtmlException
     */
    public function element_not_contains($selector_type, $selector, string $html)
    {
        $element = $this->element_exists($selector_type, $selector);
        $actual = $element->get_html();
        $regex = '/' . preg_quote($html, '/') . '/ui';
        $message = sprintf('The string "%s" appears in the HTML of the %s, but it should not.', $html, $this->get_matching_element_representation($selector_type, $selector));
        $this->assert_element(!preg_match($regex, $actual), $message, $element);
    }
    /**
     * Checks that an attribute exists in an element.
     *
     * @param string       $selectorType
     * @param string|array $selector
     *
     * @return NodeElement
     * @throws ElementHtmlException
     */
    public function element_attribute_exists($selector_type, $selector, string $attribute)
    {
        $element = $this->element_exists($selector_type, $selector);
        $message = sprintf('The attribute "%s" was not found in the %s.', $attribute, $this->get_matching_element_representation($selector_type, $selector));
        $this->assert_element($element->has_attribute($attribute), $message, $element);
        return $element;
    }
    /**
     * Checks that an attribute of a specific elements contains text.
     *
     * @param string       $selectorType
     * @param string|array $selector
     * @param string       $attribute
     *
     * @throws ElementHtmlException
     */
    public function element_attribute_contains($selector_type, $selector, $attribute, string $text)
    {
        $element = $this->element_attribute_exists($selector_type, $selector, $attribute);
        $actual = $element->get_attribute($attribute);
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The text "%s" was not found in the attribute "%s" of the %s.', $text, $attribute, $this->get_matching_element_representation($selector_type, $selector));
        $this->assert_element((bool) preg_match($regex, $actual), $message, $element);
    }
    /**
     * Checks that an attribute of a specific elements does not contain text.
     *
     * @param string       $selectorType
     * @param string|array $selector
     * @param string       $attribute
     *
     * @throws ElementHtmlException
     */
    public function element_attribute_not_contains($selector_type, $selector, $attribute, string $text)
    {
        $element = $this->element_attribute_exists($selector_type, $selector, $attribute);
        $actual = $element->get_attribute($attribute);
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $message = sprintf('The text "%s" was found in the attribute "%s" of the %s.', $text, $attribute, $this->get_matching_element_representation($selector_type, $selector));
        $this->assert_element(!preg_match($regex, $actual), $message, $element);
    }
    /**
     * Checks that specific field exists on the current page.
     *
     * @param string             $field     field id|name|label|value
     * @param TraversableElement $container document to check against
     *
     * @return NodeElement
     *
     * @throws ElementNotFoundException
     */
    public function field_exists($field, Traversable_Element $container = null)
    {
        $container = $container ?: $this->session->get_page();
        $node = $container->find_field($field);
        if (null === $node) {
            throw new Element_Not_Found_Exception($this->session, 'form field', 'id|name|label|value', $field);
        }
        return $node;
    }
    /**
     * Checks that specific field does not exists on the current page.
     *
     * @param string             $field     field id|name|label|value
     * @param TraversableElement $container document to check against
     *
     * @throws ExpectationException
     */
    public function field_not_exists(string $field, Traversable_Element $container = null)
    {
        $container = $container ?: $this->session->get_page();
        $node = $container->find_field($field);
        $this->assert(null === $node, sprintf('A field "%s" appears on this page, but it should not.', $field));
    }
    /**
     * Checks that specific field have provided value.
     *
     * @param string             $field     field id|name|label|value
     * @param string             $value     field value
     * @param TraversableElement $container document to check against
     *
     * @throws ExpectationException
     */
    public function field_value_equals(string $field, $value, Traversable_Element $container = null)
    {
        $node = $this->field_exists($field, $container);
        $actual = $node->get_value();
        $regex = '/^' . preg_quote($value, '/') . '$/ui';
        $message = sprintf('The field "%s" value is "%s", but "%s" expected.', $field, $actual, $value);
        $this->assert((bool) preg_match($regex, $actual), $message);
    }
    /**
     * Checks that specific field have provided value.
     *
     * @param string             $field     field id|name|label|value
     * @param string             $value     field value
     * @param TraversableElement $container document to check against
     *
     * @throws ExpectationException
     */
    public function field_value_not_equals(string $field, $value, Traversable_Element $container = null)
    {
        $node = $this->field_exists($field, $container);
        $actual = $node->get_value();
        $regex = '/^' . preg_quote($value, '/') . '$/ui';
        $message = sprintf('The field "%s" value is "%s", but it should not be.', $field, $actual);
        $this->assert(!preg_match($regex, $actual), $message);
    }
    /**
     * Checks that specific checkbox is checked.
     *
     * @param string             $field     field id|name|label|value
     * @param TraversableElement $container document to check against
     *
     * @throws ExpectationException
     */
    public function checkbox_checked(string $field, Traversable_Element $container = null)
    {
        $node = $this->field_exists($field, $container);
        $this->assert($node->is_checked(), sprintf('Checkbox "%s" is not checked, but it should be.', $field));
    }
    /**
     * Checks that specific checkbox is unchecked.
     *
     * @param string             $field     field id|name|label|value
     * @param TraversableElement $container document to check against
     *
     * @throws ExpectationException
     */
    public function checkbox_not_checked(string $field, Traversable_Element $container = null)
    {
        $node = $this->field_exists($field, $container);
        $this->assert(!$node->is_checked(), sprintf('Checkbox "%s" is checked, but it should not be.', $field));
    }
    /**
     * Gets current url of the page.
     *
     * @return string
     */
    protected function get_current_url_path()
    {
        return $this->clean_url($this->session->get_current_url());
    }
    /**
     * Trims scriptname from the URL.
     *
     * @param string $url
     *
     * @return string
     */
    protected function clean_url($url)
    {
        $parts = parse_url($url);
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];
        return preg_replace('/^\/[^\.\/]+\.php/', '', $parts['path']) . $fragment;
    }
    /**
     * Asserts a condition.
     *
     * @param bool   $condition
     * @param string $message   Failure message
     *
     * @throws ExpectationException when the condition is not fulfilled
     */
    private function assert($condition, $message)
    {
        if ($condition) {
            return;
        }
        throw new Expectation_Exception($message, $this->session);
    }
    /**
     * Asserts a condition involving the response text.
     *
     * @param bool   $condition
     * @param string $message   Failure message
     *
     * @throws ResponseTextException when the condition is not fulfilled
     */
    private function assert_response_text($condition, $message)
    {
        if ($condition) {
            return;
        }
        throw new Response_Text_Exception($message, $this->session);
    }
    /**
     * Asserts a condition on an element.
     *
     * @param bool    $condition
     * @param string  $message   Failure message
     *
     * @throws ElementHtmlException when the condition is not fulfilled
     */
    private function assert_element($condition, $message, Element $element)
    {
        if ($condition) {
            return;
        }
        throw new Element_Html_Exception($message, $this->session, $element);
    }
    /**
     * Asserts a condition involving the text of an element.
     *
     * @param bool    $condition
     * @param string  $message   Failure message
     *
     * @throws ElementTextException when the condition is not fulfilled
     */
    private function assert_element_text($condition, $message, Element $element)
    {
        if ($condition) {
            return;
        }
        throw new Element_Text_Exception($message, $this->session, $element);
    }
    /**
     * @param string       $selectorType
     * @param string|array $selector
     * @param boolean      $plural
     *
     * @return string
     */
    private function get_matching_element_representation($selector_type, $selector, $plural = false)
    {
        $pluralization = $plural ? 's' : '';
        if (in_array($selector_type, ['named', 'named_exact', 'named_partial']) && is_array($selector) && 2 === count($selector)) {
            return sprintf('%s%s matching locator "%s"', $selector[0], $pluralization, $selector[1]);
        }
        if (is_array($selector)) {
            $selector = implode(' ', $selector);
        }
        return sprintf('element%s matching %s "%s"', $pluralization, $selector_type, $selector);
    }
}