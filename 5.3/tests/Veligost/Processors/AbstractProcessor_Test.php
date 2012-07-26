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
class AbstractProcessor_Test extends \PHPUnit_Framework_TestCase
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

        $response = $this->getMock('stdClass', array('send', 'setStatus', 'add'));
        $response->expects($this->once())->method('send');
        $response->expects($this->once())->method('setStatus')->with(\Veligost\Response::FAILURE);
        $p_response->setValue($processor, $response);

        $processor->process();
    }
}