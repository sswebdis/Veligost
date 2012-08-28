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

use Veligost\CommerceML\Document;

/**
 *
 */
class ComponentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\CommerceML\Stereotypes\Component::getChild
     */
    public function test_getChild()
    {
        $m_getChild = new \ReflectionMethod('\Veligost\CommerceML\Stereotypes\Component',
            'getChild');
        $m_getChild->setAccessible(true);

        $xml = new \DOMDocument();
        $xml->loadXML('<КоммерческаяИнформация><Ид>123465</Ид></КоммерческаяИнформация>');
        $doc = new Document($xml);

        $elem = $this->getMockForAbstractClass('\Veligost\CommerceML\Stereotypes\Component',
            array($xml->firstChild, $doc));

        $node = $m_getChild->invoke($elem, 'Ид', 'Types\Id');
        $this->assertInstanceOf('\Veligost\CommerceML\Types\Id', $node);
        $this->assertSame($node, $m_getChild->invoke($elem, 'Ид', 'Id'));

        $node = $m_getChild->invoke($elem, 'Наименование', 'Types\Title');
        $this->assertNull($node);
    }
}
