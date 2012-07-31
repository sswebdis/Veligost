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

namespace Veligost\Tests\CommerceML;

/**
 *
 */
class Element_Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\CommerceML\Element::__construct
     * @covers \Veligost\CommerceML\Element::getChildElement
     */
    public function test_getChildElement()
    {
        $m_getChildElement = new \ReflectionMethod('\Veligost\CommerceML\Element',
            'getChildElement');
        $m_getChildElement->setAccessible(true);

        $doc = new \DOMDocument();
        $doc->loadXML('<a><b/></a>');

        $elem = $this->getMockForAbstractClass('\Veligost\CommerceML\Element',
            array($doc->firstChild));

        $this->assertInstanceOf('DOMElement', $m_getChildElement->invoke($elem, 'b'));
        $this->assertNull($m_getChildElement->invoke($elem, 'c'));
    }
}
