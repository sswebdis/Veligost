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
class ElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\CommerceML\Base\Element::__construct
     * @expectedException InvalidArgumentException
     */
    public function test_construct()
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<a/>');

        $p_nodeName = new \ReflectionProperty('\Veligost\CommerceML\Base\Element', 'nodeName');
        $p_nodeName->setAccessible(true);

        /** @var \Veligost\CommerceML\Base\Element $elem */
        $elem = $this->getMockForAbstractClass('\Veligost\CommerceML\Base\Element',
            array($doc->firstChild));
        $p_nodeName->setValue($elem, 'b');
        $elem->__construct($doc->firstChild);
    }

    /**
     * @covers \Veligost\CommerceML\Base\Element::__construct
     * @covers \Veligost\CommerceML\Base\Element::getDOMElement
     * @covers \Veligost\CommerceML\Base\Element::getChildElement
     */
    public function test_getChildElement()
    {
        $m_getChildElement = new \ReflectionMethod('\Veligost\CommerceML\Base\Element',
            'getChildElement');
        $m_getChildElement->setAccessible(true);

        $doc = new \DOMDocument();
        $doc->loadXML('<a><b/></a>');

        $elem = $this->getMockForAbstractClass('\Veligost\CommerceML\Base\Element',
            array($doc->firstChild));

        $this->assertInstanceOf('DOMElement', $m_getChildElement->invoke($elem, 'b'));
        $this->assertNull($m_getChildElement->invoke($elem, 'c'));
    }
}
