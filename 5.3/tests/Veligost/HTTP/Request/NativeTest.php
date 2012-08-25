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

namespace Veligost\Tests\HTTP\Request;

use Veligost\HTTP\Request\Native;

/**
 *
 */
class NativeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\HTTP\Request\Native::getArg
     */
    public function test_getArg()
    {
        $req = new Native;

        $this->assertNull($req->getArg('foo'));

        $_GET['bar'] = 'baz';
        $this->assertEquals('baz', $req->getArg('bar'));
    }

    /**
     * @covers \Veligost\HTTP\Request\Native::getCookie
     */
    public function test_getCookie()
    {
        $req = new Native;

        $this->assertNull($req->getCookie('foo'));

        $_COOKIE['bar'] = 'baz';
        $this->assertEquals('baz', $req->getCookie('bar'));
    }

    /**
     * @covers \Veligost\HTTP\Request\Native::getBody
     */
    public function test_getBody()
    {
        $req = new Native;

        $GLOBALS['file_get_contents']['php://input'] = 'foo';
        $this->assertEquals('foo', $req->getBody());

        $GLOBALS['file_get_contents']['php://input'] = pack('CCC', 0xEF, 0xBB, 0xBF) . 'foo';
        $this->assertEquals('foo', $req->getBody());
    }
}
