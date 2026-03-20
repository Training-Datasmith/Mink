<?php

declare (strict_types=1);
/*
 * This file is part of the Mink package.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Behat\Mink\Selector;

use Symfony\Component\Css_Selector\Css_Selector as CSS;
/**
 * CSS selector engine. Transforms CSS to XPath.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Css_Selector implements Selector_Interface
{
    /**
     * Translates CSS into XPath.
     *
     * @param string|array $locator current selector locator
     *
     * @return string
     */
    public function translate_to_x_path($locator)
    {
        if (!is_string($locator)) {
            throw new \InvalidArgumentException('The CssSelector expects to get a string as locator');
        }
        return CSS::to_x_path($locator);
    }
}