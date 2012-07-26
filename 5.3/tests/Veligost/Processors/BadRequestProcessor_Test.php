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

use Veligost\Processors\BadRequestProcessor,
    Veligost\HTTP\Request\Native,
    Veligost\Response;

/**
 *
 */
class BadRequestProcessor_Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\Processors\BadRequestProcessor::process
     */
    public function test_process()
    {
        $response = $this->getMock('Veligost\Response', array('send'));
        $response->expects($this->once())->method('send');

        $p_response = new \ReflectionProperty('Veligost\Processors\BadRequestProcessor',
            'response');
        $p_response->setAccessible(true);

        $processor = new BadRequestProcessor(new Native());
        $p_response->setValue($processor, $response);

        $processor->process();
    }
}