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

use Veligost\ProcessorFactory;

/**
 *
 */
class ProcessorFactory_Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\ProcessorFactory::create
     */
    public function test_create()
    {
        $processor = ProcessorFactory::create();
        $this->assertInstanceOf('\Veligost\Processors\BadRequestProcessor', $processor);

        $request = $this->getMock('\Veligost\HTTP\Request\Native', array('getArg'));
        $request->expects($this->any())->method('getArg')->will($this->returnValueMap(array(
            array('type', 'catalog')
        )));

        $processor = ProcessorFactory::create($request);
        $this->assertInstanceOf('\Veligost\Processors\CatalogProcessor', $processor);
    }
}