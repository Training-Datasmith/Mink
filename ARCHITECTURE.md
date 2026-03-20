# Architecture: Mink

## Purpose

Mink is a browser emulation layer for PHP acceptance tests. It provides a unified API over multiple browser backends (BrowserKit, Selenium2, etc.) so test code is independent of the underlying driver.

## Directory Structure

```
src/Behat/Mink/
  Driver/
    Driver_Interface.php   Contract all drivers must implement
    Core_Driver.php        Abstract base with unsupported-action exception stubs
  Element/
    Element_Interface.php  Base element contract
    Element.php            Implements XPath/CSS queries via SelectorsHandler
    Node_Element.php       DOM node — click, fill, check, select, etc.
    Document_Element.php   The page root element
    Traversable_Element.php Mixin for elements that can find children
  Exception/              Typed exceptions for driver errors, element not found, etc.
  Selector/
    Selector_Interface.php Contract for CSS→XPath conversion
    Css_Selector.php       Converts CSS to XPath via Symfony CssSelector
    Named_Selector.php     Named selectors (field, link, button, etc.)
    Selectors_Handler.php  Registry + dispatcher of selectors by type
  Mink.php                Session manager — registers, starts, stops named sessions
  Session.php             Per-driver session: navigate, query, assert
  Web_Assert.php          High-level assertion helpers (status code, text, element presence)
```

## Key Design Decisions

- **Driver isolation**: All browser-specific code lives in a driver. The test-visible API (Session, Element) never references a driver implementation class.
- **Selector abstraction**: Selectors convert human-readable locators (CSS, named) to XPath, which is the only query language drivers need to implement.
- **Multiple sessions**: `Mink` manages named sessions to allow tests that exercise multiple browsers simultaneously.

## Extension Points

- Implement `Driver_Interface` to add a new browser backend.
- Implement `Selector_Interface` and register it with `Selectors_Handler` to add custom locator strategies.

## Dependency Flow

```
Test code
  -> Mink::getSession(name)
    -> Session (wraps a Driver)
      -> Session::getPage() -> DocumentElement
        -> NodeElement::find(css, selector)
          -> SelectorsHandler -> CssSelector -> XPath
          -> Driver::find(xpath) -> NodeElement[]
```
