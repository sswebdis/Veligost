<?php
/**
 * Тесты
 *
 * @copyright 2012 ООО «Два слона» http://dvaslona.ru/
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @author Михаил Красильников <mk@dvaslona.ru>
 *
 * Copyright 2012 DvaSlona Ltd.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Veligost\Tests;

use Veligost\Session;

/**
 *
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\Session::__construct
     * @covers \Veligost\Session::getId
     * @covers \Veligost\Session::exists
     * @covers \Veligost\Session::create
     * @covers \Veligost\Session::destroy
     * @covers \Veligost\Session::storeFile
     * @covers \Veligost\Session::retrieveFile
     */
    public function test_overall()
    {
        $sessions = $this->getMockBuilder('\Veligost\SessionStorage\Native')
            ->disableOriginalConstructor()
            ->setMethods(array('createSession', 'sessionExists', 'closeSession'))
            ->getMock();
        $sessions->expects($this->once())->method('createSession')->will($this->returnValue('foo'));
        $sessions->expects($this->once())->method('sessionExists')->will($this->returnValue(true));
        $sessions->expects($this->once())->method('closeSession');

        $files = $this->getMockBuilder('\Veligost\FileStorage\Directory')
            ->disableOriginalConstructor()->setMethods(array('read', 'store', 'cleanup'))
            ->getMock();
        $files->expects($this->any())->method('read')->will($this->returnValueMap(array(
            array('foo', 'a.xml', false),
            array('foo', 'b.xml', 'data'),
        )));
        $files->expects($this->once())->method('store')->with('foo', 'c.xml', 'data');
        $files->expects($this->once())->method('cleanup');

        $session = new Session(null, $sessions, $files);
        $this->assertNull($session->getId());
        $this->assertFalse($session->exists());
        $session->create();
        $this->assertEquals('foo', $session->getId());
        $this->assertTrue($session->exists());
        $this->assertFalse($session->retrieveFile('a.xml'));
        $this->assertEquals('data', $session->retrieveFile('b.xml'));
        $session->storeFile('c.xml', 'data');
        $session->destroy();
    }
}