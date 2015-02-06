<?php
/**
 * Copyright (c) 2014 - 2015, Designmoves (http://www.designmoves.nl)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * * Neither the name of Designmoves nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace DesignmovesUtilsTest\Hydrator\Strategy;

use DateTime;
use DesignmovesUtils\Hydrator\Strategy\DateTimeStrategy;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass DesignmovesUtils\Hydrator\Strategy\DateTimeStrategy
 */
class DateTimeStrategyTest extends PHPUnit_Framework_TestCase
{
    /**
     * DateTimeStrategy
     */
    protected $strategy;

    public function setUp()
    {
        $this->strategy = new DateTimeStrategy();
    }

    public function testStrategyImplementsZendHydratorStrategyInterface()
    {
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\Strategy\StrategyInterface', $this->strategy);
    }

    /**
     * @covers ::hydrate
     */
    public function testHydrateReturnsInstanceOfDateTime()
    {
        $this->assertInstanceOf('DateTime', $this->strategy->hydrate('2014-01-06 20:00:00'));
    }

    /**
     * @covers ::hydrate
     */
    public function testHydrateReturnsNullOnEmptyString()
    {
        $this->assertNull($this->strategy->hydrate(''));
    }

    /**
     * @covers ::extract
     */
    public function testExtractDateTimeReturnsString()
    {
        $expectedValue = '2014-01-07 18:58:23';

        $input = new DateTime($expectedValue);
        $value = $this->strategy->extract($input);

        $this->assertSame($expectedValue, $value);
    }

    /**
     * @covers ::extract
     */
    public function testExtractReturnsOriginalValueWhenNotDateTime()
    {
        $this->assertSame('foo', $this->strategy->extract('foo'));
    }

    /**
     * @covers ::extract
     */
    public function testExtractNullReturnsNull()
    {
        $this->assertSame(null, $this->strategy->extract(null));
    }
}
