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

namespace Veligost\Tests\Processors;

use Veligost\Processors\AbstractProcessor,
    Veligost\HTTP\Request\Native;

/**
 *
 */
class AbstractProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\Processors\AbstractProcessor::__construct
     */
    public function test_construct()
    {
        $processor = $this->getMockForAbstractClass('\Veligost\Processors\AbstractProcessor',
            array(new Native));

        $p_request = new \ReflectionProperty('\Veligost\Processors\AbstractProcessor', 'request');
        $p_request->setAccessible(true);
        $this->assertInstanceOf('\Veligost\HTTP\Request\Native', $p_request->getValue($processor));

        $p_response = new \ReflectionProperty('\Veligost\Processors\AbstractProcessor', 'response');
        $p_response->setAccessible(true);
        $this->assertInstanceOf('\Veligost\Response', $p_response->getValue($processor));
    }

    /**
     * @covers \Veligost\Processors\AbstractProcessor::getSessionStorage
     */
    public function test_getSessionStorage()
    {
        $processor = $this->getMockForAbstractClass('\Veligost\Processors\AbstractProcessor',
            array(new Native));

        $m_getSessionStorage = new \ReflectionMethod('\Veligost\Processors\AbstractProcessor',
            'getSessionStorage');
        $m_getSessionStorage->setAccessible(true);

        $storage = $m_getSessionStorage->invoke($processor);
        $this->assertInstanceOf('\Veligost\SessionStorage\Native', $storage);
        $this->assertSame($storage, $m_getSessionStorage->invoke($processor));
    }

    /**
     * @covers \Veligost\Processors\AbstractProcessor::process
     */
    public function test_process()
    {
        $p_response = new \ReflectionProperty('\Veligost\Processors\AbstractProcessor', 'response');
        $p_response->setAccessible(true);

        $req = $this->getMock('Veligost\HTTP\Request\Native', array('getArg'));
        $req->expects($this->once())->method('getArg')->with('mode')->
            will($this->returnValue('checkauth'));

        $processor = $this->getMockBuilder('\Veligost\Processors\AbstractProcessor')->
            setConstructorArgs(array($req))->setMethods(array('actionCheckAuth'))->
            getMock();
        $processor->expects($this->once())->method('actionCheckAuth');

        $response = $this->getMock('stdClass', array('send'));
        $response->expects($this->once())->method('send');
        $p_response->setValue($processor, $response);

        $processor->process();

        //-----

        $req = $this->getMock('Veligost\HTTP\Request\Native', array('getArg'));
        $req->expects($this->any())->method('getArg')->with('mode')->
            will($this->returnValue('init'));

        $processor = $this->getMockBuilder('\Veligost\Processors\AbstractProcessor')->
            setConstructorArgs(array($req))->
            setMethods(array('checkSession', 'actionInit'))->getMock();
        $processor->expects($this->once())->method('checkSession')->will($this->returnValue(true));
        $processor->expects($this->once())->method('actionInit');

        $response = $this->getMock('stdClass', array('send'));
        $response->expects($this->once())->method('send');
        $p_response->setValue($processor, $response);

        $processor->process();

        //-----

        $req = $this->getMock('Veligost\HTTP\Request\Native', array('getArg'));
        $req->expects($this->any())->method('getArg')->with('mode')->
            will($this->returnValue('foo'));

        $processor = $this->getMockBuilder('\Veligost\Processors\AbstractProcessor')->
            setConstructorArgs(array($req))->setMethods(array('checkSession'))->getMock();
        $processor->expects($this->once())->method('checkSession')->will($this->returnValue(true));

        $response = $this->getMock('stdClass', array('send', 'add'));
        $response->expects($this->once())->method('send');
        $p_response->setValue($processor, $response);

        $processor->process();
    }

    /**
     * @covers \Veligost\Processors\AbstractProcessor::checkSession
     */
    public function test_checkSession()
    {
        $storage = $this->getMock('stdClass', array('sessionExists'));
        $storage->expects($this->exactly(2))->method('sessionExists')->with('cookie')->
            will($this->returnCallback(function () { static $r = false; return ($r = !$r); }));

        $request = $this->getMock('\Veligost\HTTP\Request\Native', array('getCookie'));
        $request->expects($this->any())->method('getCookie')->will($this->returnValue('cookie'));

        $processor = $this->getMockBuilder('\Veligost\Processors\AbstractProcessor')->
            setConstructorArgs(array($request))->setMethods(array('getSessionStorage'))->
            getMock();
        $processor->expects($this->any())->method('getSessionStorage')->
            will($this->returnValue($storage));

        $m_checkSession = new \ReflectionMethod('\Veligost\Processors\AbstractProcessor',
            'checkSession');
        $m_checkSession->setAccessible(true);

        $this->assertTrue($m_checkSession->invoke($processor));
        $this->assertFalse($m_checkSession->invoke($processor));
    }

    /**
     * @covers \Veligost\Processors\AbstractProcessor::actionCheckAuth
     */
    public function test_actionCheckAuth()
    {
        $storage = $this->getMock('stdClass', array('createSession'));
        $storage->expects($this->once())->method('createSession');

        $processor = $this->getMockBuilder('\Veligost\Processors\AbstractProcessor')->
            disableOriginalConstructor()->setMethods(array('getSessionStorage'))->getMock();
        $processor->expects($this->any())->method('getSessionStorage')->
            will($this->returnValue($storage));

        $response = $this->getMock('stdClass', array('add'));
        $response->expects($this->exactly(3))->method('add');

        $p_response = new \ReflectionProperty('\Veligost\Processors\AbstractProcessor', 'response');
        $p_response->setAccessible(true);
        $p_response->setValue($processor, $response);

        $m_actionCheckAuth = new \ReflectionMethod('\Veligost\Processors\AbstractProcessor',
            'actionCheckAuth');
        $m_actionCheckAuth->setAccessible(true);

        $m_actionCheckAuth->invoke($processor);
    }

    /**
     * @covers \Veligost\Processors\AbstractProcessor::actionInit
     */
    public function test_actionInit()
    {
        $processor = $this->getMockBuilder('\Veligost\Processors\AbstractProcessor')->
            disableOriginalConstructor()->setMethods(array())->getMock();

        $p_response = new \ReflectionProperty('\Veligost\Processors\AbstractProcessor', 'response');
        $p_response->setAccessible(true);

        $m_actionInit = new \ReflectionMethod('\Veligost\Processors\AbstractProcessor',
            'actionInit');
        $m_actionInit->setAccessible(true);

        $GLOBALS['ini']['upload_max_filesize'] = '2K';
        $response = $this->getMock('stdClass', array('add'));
        $p_response->setValue($processor, $response);
        $response->expects($this->exactly(2))->method('add')->
            with($this->logicalOr('zip=no', 'file_limit=2048'));
        $m_actionInit->invoke($processor);

        $GLOBALS['ini']['upload_max_filesize'] = '3M';
        $response = $this->getMock('stdClass', array('add'));
        $p_response->setValue($processor, $response);
        $response->expects($this->exactly(2))->method('add')->
            with($this->logicalOr('zip=no', 'file_limit=3145728'));
        $m_actionInit->invoke($processor);

        $GLOBALS['ini']['upload_max_filesize'] = '4G';
        $response = $this->getMock('stdClass', array('add'));
        $p_response->setValue($processor, $response);
        $response->expects($this->exactly(2))->method('add')->
            with($this->logicalOr('zip=no', 'file_limit=4294967296'));
        $m_actionInit->invoke($processor);
    }
}