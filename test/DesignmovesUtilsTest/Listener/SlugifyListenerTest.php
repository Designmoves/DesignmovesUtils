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

namespace DesignmovesUtilsTest\Listener;

use DesignmovesUtils\Filter\Slug as SlugFilter;
use DesignmovesUtils\Listener\SlugifyListener;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\Event;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;

/**
 * @coversDefaultClass DesignmovesUtils\Listener\SlugifyListener
 * @uses               DesignmovesUtils\Filter\Slug
 * @uses               DesignmovesUtils\Listener\SlugifyListener
 */
class SlugifyListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var SlugifyListener
     */
    protected $listener;

    /**
     * @var SlugFilter
     */
    protected $slugFilter;

    public function setUp()
    {
        $this->event      = new Event;
        $this->slugFilter = new SlugFilter;
        $this->listener   = new SlugifyListener($this->slugFilter);
    }

    /**
     * @covers ::__construct
     */
    public function testSlugFilterIsSetOnConstruct()
    {
        $this->assertSame($this->slugFilter, self::readAttribute($this->listener, 'slugFilter'));
    }

    /**
     * @covers ::attach
     */
    public function testAttachesSlugifyListenerOnAllEvents()
    {
        $eventManager = new EventManager;
        $eventManager->setSharedManager(new SharedEventManager);
        $eventManager->attach($this->listener);

        $listeners        = $eventManager->getSharedManager()->getListeners('*', 'slugify');
        $expectedCallback = array($this->listener, 'slugify');
        $expectedPriority = 1;

        $found = false;
        foreach ($listeners as $listener) {
            $callback = $listener->getCallback();
            if ($callback === $expectedCallback) {
                if ($listener->getMetadatum('priority') == $expectedPriority) {
                    $found = true;
                    break;
                }
            }
        }

        $this->assertTrue($found, 'Listener not found');
    }

    /**
     * @covers ::slugify
     */
    public function testCanSlugify()
    {
        $this->event->setParam('text', 'Foo bar');
        $this->listener->slugify($this->event);

        $this->assertNotNull($this->event->getParam('text'));
    }
}
