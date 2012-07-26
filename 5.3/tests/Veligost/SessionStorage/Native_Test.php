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

namespace Veligost\Tests\SessionStorage;

use Veligost\SessionStorage\Native;

/**
 *
 */
class Native_Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\SessionStorage\Native::__construct
     * @covers \Veligost\SessionStorage\Native::startSessions
     * @covers \Veligost\SessionStorage\Native::createSession
     * @covers \Veligost\SessionStorage\Native::sessionExists
     * @covers \Veligost\SessionStorage\Native::closeSession
     */
    public function test_overall()
    {
        $storage = new Native('foo');

        $this->assertFalse($storage->sessionExists('bar'));

        $sid = $storage->createSession();
        $this->assertNotEmpty($sid);
        $this->assertTrue($storage->sessionExists($sid));

        $storage->closeSession($sid);
        $this->assertFalse($storage->sessionExists($sid));
    }

    /**
     * @covers \Veligost\SessionStorage\Native::createSession
     * @covers \Veligost\SessionStorage\Native::startSessions
     * @expectedException RuntimeException
     */
    public function test_createSession_failed()
    {
        $GLOBALS['session_start_fail'] = true;
        $storage = new Native('foo');
        $storage->createSession();
    }
}