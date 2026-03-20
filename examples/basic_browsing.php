<?php

declare(strict_types=1);

/**
 * Mink — basic browser interaction example using BrowserKit driver.
 *
 * Demonstrates: registering sessions, navigating, finding elements, asserting content.
 *
 * Requirements:
 *   composer require behat/mink behat/mink-browserkit-driver symfony/browser-kit symfony/http-client
 *
 * Run:
 *   php examples/basic_browsing.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink_Browser_Kit_Driver\Browser_Kit_Driver;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;

// Create a real HTTP browser backed by Symfony HttpClient
$browser  = new HttpBrowser(HttpClient::create());
$driver   = new Browser_Kit_Driver($browser);
$session  = new Session($driver);

$mink = new Mink(['default' => $session]);
$mink->set_default_session_name('default');

// Navigate to a page
$mink->get_session()->visit('https://example.com');

// Assert page title contains expected text
$page  = $mink->get_session()->getPage();
$title = $page->find('css', 'title');

if ($title !== null) {
    echo 'Page title: ' . $title->getText() . PHP_EOL;
} else {
    echo 'No <title> element found.' . PHP_EOL;
}

// Check status code
$code = $mink->get_session()->getStatusCode();
echo 'Status code: ' . $code . PHP_EOL;

// Find all links on the page
$links = $page->findAll('css', 'a');
echo 'Links found: ' . count($links) . PHP_EOL;

$mink->stop_sessions();
