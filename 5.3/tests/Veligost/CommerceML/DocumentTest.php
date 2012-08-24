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
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Veligost\CommerceML\Document::getClassifier
     */
    public function test_getClassifier()
    {
        $xml = new \DOMDocument;
        $xml->load(__DIR__ . '/Document.fixtures/import_1.xml');

        $doc = new Document($xml->firstChild);

        $this->assertEquals('2.03', $doc->getVersion());

        $classifier = $doc->getClassifier();
        $this->assertInstanceOf('\Veligost\CommerceML\Classifier', $classifier);
        $this->assertEquals('2aa4a73e-4962-11de-bf4f-000c7834463c', $classifier->getId());
        $this->assertEquals('Классификатор (Каталог товаров)', $classifier->getTitle());

        $groups = $classifier->getGroups();
        $this->assertInstanceOf('\Veligost\CommerceML\Groups', $groups);
    }
}
