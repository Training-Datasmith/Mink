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
 * Exact match selector engine. Like the Named selector engine but ignores partial matches.
 */
class Exact_Named_Selector extends Named_Selector
{
    public function __construct()
    {
        $this->register_replacement('%tagTextMatch%', 'normalize-space(string(.)) = %locator%');
        $this->register_replacement('%valueMatch%', './@value = %locator%');
        $this->register_replacement('%titleMatch%', './@title = %locator%');
        $this->register_replacement('%altMatch%', './@alt = %locator%');
        $this->register_replacement('%relMatch%', './@rel = %locator%');
        $this->register_replacement('%labelAttributeMatch%', './@label = %locator%');
        parent::__construct();
    }
}