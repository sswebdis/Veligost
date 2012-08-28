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
class SequenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\CommerceML\Stereotypes\Sequence::extractFromElement
     * @covers \Veligost\CommerceML\Stereotypes\Sequence::__construct
     * @covers \Veligost\CommerceML\Stereotypes\Sequence::__toString
     */
    public function test_extractFromElement()
    {
        $xml = new \DOMDocument();
        $xml->loadXML('<КоммерческаяИнформация><Товар><Ид>123456</Ид></Товар>' .
            '</КоммерческаяИнформация>');
        $doc = new Document($xml);

        $component = $this->getMockForAbstractClass('\Veligost\CommerceML\Stereotypes\Component',
            array($xml->firstChild->firstChild, $doc));

        $element = SequenceTest_Sequence::extractFromElement($component);

        $this->assertEquals('123456', strval($element));
    }
}


class SequenceTest_Sequence extends \Veligost\CommerceML\Stereotypes\Sequence
{
    static protected $nodeNames = array(
        'Ид' => 'Types\Id',
    );
}