<?php
/**
 * Модульные тесты
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

require_once 'vfsStream/vfsStream.php';

use vfsStream, vfsStreamWrapper, vfsStreamDirectory;
use Veligost\HTTP\Request\Native;

/**
 *
 */
class Native_Test extends \PHPUnit_Framework_TestCase
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
     * @covers \Veligost\HTTP\Request\Native::providesUploadedTempName
     */
    public function test_providesUploadedTempName()
    {
        $req = new Native;

        $this->assertTrue($req->providesUploadedTempName());
    }

    /**
     * @covers \Veligost\HTTP\Request\Native::getUploadedTempName
     */
    public function test_getUploadedTempName()
    {
        $req = new Native;

        $_FILES['foo'] = array('tmp_name' => '/tmp/filename');
        $this->assertEquals('/tmp/filename', $req->getUploadedTempName('foo'));
        $this->assertNull($req->getUploadedTempName('bar'));
    }

    /**
     * @covers \Veligost\HTTP\Request\Native::providesUploadedContents
     */
    public function test_providesUploadedContents()
    {
        $req = new Native;

        \Veligost\HTTP\Request\ini_set('open_basedir', '');
        $this->assertTrue($req->providesUploadedContents());

        \Veligost\HTTP\Request\ini_set('open_basedir', '/tmp:/foo/');
        \Veligost\HTTP\Request\ini_set('upload_tmp_dir', '');
        $this->assertFalse($req->providesUploadedContents());

        $GLOBALS['phpversion'] = '5.0.0';
        \Veligost\HTTP\Request\ini_set('upload_tmp_dir', '/tmp');
        $this->assertTrue($req->providesUploadedContents());
        \Veligost\HTTP\Request\ini_set('upload_tmp_dir', '/tmp_dir');
        $this->assertTrue($req->providesUploadedContents());
        \Veligost\HTTP\Request\ini_set('upload_tmp_dir', '/foo');
        $this->assertTrue($req->providesUploadedContents());

        $GLOBALS['phpversion'] = '5.2.17';
        \Veligost\HTTP\Request\ini_set('upload_tmp_dir', '/tmp');
        $this->assertTrue($req->providesUploadedContents());
        \Veligost\HTTP\Request\ini_set('upload_tmp_dir', '/tmp_dir');
        $this->assertFalse($req->providesUploadedContents());
        \Veligost\HTTP\Request\ini_set('upload_tmp_dir', '/foo/');
        $this->assertTrue($req->providesUploadedContents());
    }

    /**
     * @covers \Veligost\HTTP\Request\Native::getUploadedContents
     */
    public function test_getUploadedContents()
    {
        $req = new Native;

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('uploads'));
        $filename = vfsStream::url('uploads/tmpFile');
        file_put_contents($filename, 'foo');

        $_FILES['bar'] = array('tmp_name' => $filename);
        $this->assertEquals('foo', $req->getUploadedContents('bar'));
        $this->assertNull($req->getUploadedContents('baz'));
    }

}
