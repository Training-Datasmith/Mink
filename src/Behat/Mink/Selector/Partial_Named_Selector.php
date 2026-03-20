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
 * Named selectors engine. Uses registered XPath selectors to create new expressions.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Partial_Named_Selector extends Named_Selector
{
    public function __construct()
    {
        $this->register_replacement('%tagTextMatch%', 'contains(normalize-space(string(.)), %locator%)');
        $this->register_replacement('%valueMatch%', 'contains(./@value, %locator%)');
        $this->register_replacement('%titleMatch%', 'contains(./@title, %locator%)');
        $this->register_replacement('%altMatch%', 'contains(./@alt, %locator%)');
        $this->register_replacement('%relMatch%', 'contains(./@rel, %locator%)');
        $this->register_replacement('%labelAttributeMatch%', 'contains(./@label, %locator%)');
        parent::__construct();
    }
}