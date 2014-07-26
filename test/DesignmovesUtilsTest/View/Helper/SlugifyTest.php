<?php
/**
 * Copyright (c) 2014, Designmoves http://www.designmoves.nl
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

namespace DesignmovesUtilsTest\View\Helper;

use DesignmovesUtils\Filter\Slug as SlugFilter;
use DesignmovesUtils\View\Helper\Slugify;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

/**
 * @coversDefaultClass DesignmovesUtils\View\Helper\Slugify
 * @uses               DesignmovesUtils\Filter\Slug
 * @uses               DesignmovesUtils\View\Helper\Slugify
 */
class SlugifyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Slugify
     */
    protected $helper;

    /**
     * @var SlugFilter
     */
    protected $filter;

    public function setUp()
    {
        $this->filter = new SlugFilter;
        $this->helper = new Slugify($this->filter);
    }

    /**
     * @covers ::__construct
     */
    public function testSlugFilterIsSetOnConstruct()
    {
        $this->assertSame($this->filter, self::readAttribute($this->helper, 'filter'));
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeReturnsSlugifiedText()
    {
        $this->assertSame('foo-bar', $this->helper->__invoke('Foo bar'));
    }

    /**
     * @covers ::getFilter
     */
    public function testCanGetFilter()
    {
        $method = new ReflectionMethod($this->helper, 'getFilter');
        $method->setAccessible(true);

        $this->assertSame($this->filter, $method->invoke($this->helper));
    }
}
