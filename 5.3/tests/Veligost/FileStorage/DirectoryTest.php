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

namespace Veligost\Tests\FileStorage;

use Veligost\FileStorage\Directory,
    vfsStreamWrapper,
    vfsStream;

/**
 *
 */
class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\FileStorage\Directory::__construct
     * @covers \Veligost\FileStorage\Directory::store
     * @covers \Veligost\FileStorage\Directory::read
     * @covers \Veligost\FileStorage\Directory::cleanup
     * @covers \Veligost\FileStorage\Directory::createFilename
     */
    public function test_overall()
    {
        if (!class_exists('vfsStream'))
        {
            $this->markTestSkipped('vfsStream required to run this test');
        }

        vfsStreamWrapper::register();
        vfsStream::setup('path');

        $storage = new Directory(vfsStream::url('path'));
        $storage->store('foo', 'import.xml', '<import_foo />');
        $storage->store('foo', 'offers.xml', '<offers_foo />');
        $storage->store('bar', 'import.xml', '<import_bar />');
        $storage->store('bar', 'offers.xml', '<offers_bar />');

        $storage = new Directory(vfsStream::url('path'));
        $this->assertEquals('<import_foo />', $storage->read('foo', 'import.xml'));
        $this->assertEquals('<offers_foo />', $storage->read('foo', 'offers.xml'));
        $this->assertEquals('<import_bar />', $storage->read('bar', 'import.xml'));
        $this->assertEquals('<offers_bar />', $storage->read('bar', 'offers.xml'));

        $storage = new Directory(vfsStream::url('path'));
        $storage->cleanup('foo');
        $this->assertFalse($storage->read('foo', 'import.xml'));
        $this->assertFalse($storage->read('foo', 'offers.xml'));
        $this->assertEquals('<import_bar />', $storage->read('bar', 'import.xml'));
        $this->assertEquals('<offers_bar />', $storage->read('bar', 'offers.xml'));
    }
}