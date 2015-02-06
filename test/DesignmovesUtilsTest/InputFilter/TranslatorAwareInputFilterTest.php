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

namespace DesignmovesUtilsTest\InputFilter;

use DesignmovesUtils\InputFilter\TranslatorAwareInputFilter;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;
use Zend\I18n\Translator\Translator;

/**
 * @coversDefaultClass DesignmovesUtils\InputFilter\TranslatorAwareInputFilter
 */
class TranslatorAwareInputFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TranslatorAwareInputFilter
     */
    protected $inputFilter;

    public function setUp()
    {
        $this->inputFilter = new TranslatorAwareInputFilter;
    }

    /**
     * @covers ::setTranslator
     * @covers ::getTranslator
     * @covers ::getTranslatorTextDomain
     */
    public function testCanSetTranslator()
    {
        $translator = new Translator;
        $this->inputFilter->setTranslator($translator);

        $this->assertSame($translator, $this->inputFilter->getTranslator());
        $this->assertSame('default', $this->inputFilter->getTranslatorTextDomain());
    }

    /**
     * @covers ::setTranslator
     * @covers ::getTranslator
     * @covers ::setTranslatorTextDomain
     * @covers ::getTranslatorTextDomain
     */
    public function testCanSetTranslatorTextDomainWithTextDomain()
    {
        $translator = new Translator;
        $this->inputFilter->setTranslator($translator, 'foobar');

        $this->assertSame($translator, $this->inputFilter->getTranslator());
        $this->assertSame('foobar', $this->inputFilter->getTranslatorTextDomain());
    }

    /**
     * @covers ::hasTranslator
     * @covers ::setTranslator
     */
    public function testHasTranslator()
    {
        $this->inputFilter->setTranslator(new Translator);

        $this->assertTrue($this->inputFilter->hasTranslator());
    }

    /**
     * @covers ::setTranslatorTextDomain
     * @covers ::getTranslatorTextDomain
     */
    public function testCanSetTranslatorTextDomain()
    {
        $this->inputFilter->setTranslatorTextDomain('foo-bar');
        $this->assertSame('foo-bar', $this->inputFilter->getTranslatorTextDomain());
    }

    /**
     * @covers ::isTranslatorEnabled
     */
    public function testIsTranslatorEnabledDefaultValue()
    {
        $this->assertTrue($this->inputFilter->isTranslatorEnabled());
    }

    /**
     * @covers ::setTranslatorEnabled
     * @covers ::isTranslatorEnabled
     */
    public function testCanSetTranslatorEnabled()
    {
        $this->inputFilter->setTranslatorEnabled(false);
        $this->assertFalse($this->inputFilter->isTranslatorEnabled());
    }

    /**
     * @covers ::translate
     * @covers ::setTranslator
     * @covers ::getTranslator
     * @covers ::getTranslatorTextDomain
     */
    public function testCanTranslate()
    {
        $translatorMock = $this->getMock('Zend\I18n\Translator\Translator');
        $this->inputFilter->setTranslator($translatorMock);

        $translatorMock->expects($this->once())
                       ->method($this->equalTo('translate'))
                       ->with(
                           $this->equalTo('foo'),
                           $this->equalTo('default')
                       )
                       ->will($this->returnValue('foo'));

        $method = new ReflectionMethod($this->inputFilter, 'translate');
        $method->setAccessible(true);

        $this->assertSame('foo', $method->invoke($this->inputFilter, 'foo'));
    }
}
