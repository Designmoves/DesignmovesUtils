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

namespace DesignmovesUtilsTest;

use DesignmovesUtils\Module;
use DesignmovesUtils\Filter\Slug as SlugFilter;
use DesignmovesUtils\Listener\SlugifyListener;
use PHPUnit_Framework_TestCase;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

/**
 * @coversDefaultClass DesignmovesUtils\Module
 * @uses               DesignmovesUtils\Listener\SlugifyListener
 */
class ModuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Module
     */
    protected $module;

    public function setUp()
    {
        $this->module = new Module;
    }

    /**
     * @covers ::getAutoloaderConfig
     */
    public function testCanGetAutoloaderConfig()
    {
        $autoloaderConfig = $this->module->getAutoloaderConfig();

        $this->assertInternalType('array', $autoloaderConfig);
        $this->assertArrayHasKey('Zend\Loader\ClassMapAutoloader', $autoloaderConfig);
        $this->assertArrayHasKey('Zend\Loader\StandardAutoloader', $autoloaderConfig);
    }

    /**
     * @cover ::getConfig
     */
    public function testCanGetConfig()
    {
        $this->assertInternalType('array', $this->module->getConfig());
    }

    /**
     * @covers ::onBootstrap
     */
    public function testOnBootstrapAttachesListeners()
    {
        $serviceManager = new ServiceManager;

        $eventManagerMock = $this->getMock('Zend\EventManager\EventManager');
        $serviceManager->setService('EventManager', $eventManagerMock);
        $serviceManager->setService('Request', new HttpRequest);
        $serviceManager->setService('Response', new HttpResponse);

        $slugifyListener = new SlugifyListener(new SlugFilter);
        $serviceManager->setService('DesignmovesUtils\Listener\SlugifyListener', $slugifyListener);

        $event = new MvcEvent;
        $application = new Application(array(), $serviceManager);
        $event->setApplication($application);

        $eventManagerMock->expects($this->once())
                         ->method($this->equalTo('attach'))
                         ->with($this->equalTo($slugifyListener))
                         ->will($this->returnValue(null));

        $this->module->onBootstrap($event);
    }
}
