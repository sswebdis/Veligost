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

namespace Veligost\Tests\CommerceML
{

    use Veligost\CommerceML\Document;

    /**
     *
     */
    class ElementGroupTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @covers \Veligost\CommerceML\Base\ElementGroup::getChildren
         */
        public function test_getChildren()
        {
            $xml = new \DOMDocument();
            $xml->loadXML('<КоммерческаяИнформация>' .
                '<Группы><Группа /><a /><Группа /></Группы>' .
                '</КоммерческаяИнформация>');
            $doc = new Document($xml);

            /** @var \Veligost\CommerceML\Base\ElementGroup $group */
            $group = $this->getMockForAbstractClass('\Veligost\CommerceML\Base\ElementGroup',
                array($xml->firstChild->firstChild, $doc));

            $childNodeName = new \ReflectionProperty('\Veligost\CommerceML\Base\ElementGroup',
                'childNodeName');
            $childNodeName->setAccessible(true);
            $childNodeName->setValue($group, 'Группа');

            $childClassName = new \ReflectionProperty('\Veligost\CommerceML\Base\ElementGroup',
                'childClassName');
            $childClassName->setAccessible(true);
            $childClassName->setValue($group, 'ElementGroupTest_Element');

            $children = $group->getChildren();
            $this->assertInternalType('array', $children);
            $this->assertCount(2, $children);
            $this->assertInstanceOf('\Veligost\CommerceML\Base\Element', $children[0]);
        }
    }
}

namespace Veligost\CommerceML
{
    class ElementGroupTest_Element extends Base\Element
    {
    }
}