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

namespace DesignmovesUtilsTest\Form;

use DesignmovesUtils\Form\TranslatorAwareFieldset;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;
use Zend\I18n\Translator\Translator;

/**
 * @coversDefaultClass DesignmovesUtils\Form\TranslatorAwareFieldset
 */
class TranslatorAwareFieldsetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TranslatorAwareFieldset
     */
    protected $fieldset;

    public function setUp()
    {
        $this->fieldset = new TranslatorAwareFieldset;
    }

    /**
     * @covers ::setTranslator
     * @covers ::getTranslator
     * @covers ::getTranslatorTextDomain
     */
    public function testCanSetTranslator()
    {
        $translator = new Translator;
        $this->fieldset->setTranslator($translator);

        $this->assertSame($translator, $this->fieldset->getTranslator());
        $this->assertSame('default', $this->fieldset->getTranslatorTextDomain());
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
        $this->fieldset->setTranslator($translator, 'foobar');

        $this->assertSame($translator, $this->fieldset->getTranslator());
        $this->assertSame('foobar', $this->fieldset->getTranslatorTextDomain());
    }

    /**
     * @covers ::hasTranslator
     * @covers ::setTranslator
     */
    public function testHasTranslator()
    {
        $this->fieldset->setTranslator(new Translator);

        $this->assertTrue($this->fieldset->hasTranslator());
    }

    /**
     * @covers ::setTranslatorTextDomain
     * @covers ::getTranslatorTextDomain
     */
    public function testCanSetTranslatorTextDomain()
    {
        $this->fieldset->setTranslatorTextDomain('foo-bar');
        $this->assertSame('foo-bar', $this->fieldset->getTranslatorTextDomain());
    }

    /**
     * @covers ::isTranslatorEnabled
     */
    public function testIsTranslatorEnabledDefaultValue()
    {
        $this->assertTrue($this->fieldset->isTranslatorEnabled());
    }

    /**
     * @covers ::setTranslatorEnabled
     * @covers ::isTranslatorEnabled
     */
    public function testCanSetTranslatorEnabled()
    {
        $this->fieldset->setTranslatorEnabled(false);
        $this->assertFalse($this->fieldset->isTranslatorEnabled());
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
        $this->fieldset->setTranslator($translatorMock);

        $translatorMock->expects($this->once())
                       ->method($this->equalTo('translate'))
                       ->with(
                           $this->equalTo('foo'),
                           $this->equalTo('default')
                       )
                       ->will($this->returnValue('foo'));

        $method = new ReflectionMethod($this->fieldset, 'translate');
        $method->setAccessible(true);

        $this->assertSame('foo', $method->invoke($this->fieldset, 'foo'));
    }
}
