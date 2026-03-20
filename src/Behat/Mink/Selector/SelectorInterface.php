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

/**
 * Mink selector engine interface.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface Selector_Interface
{
    /**
     * Translates provided locator into XPath.
     *
     * @param string|array $locator current selector locator
     *
     * @return string
     */
    public function translate_to_x_path($locator);
}