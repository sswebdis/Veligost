<?php
/**
 * Документ CommerceML
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

namespace Veligost\CommerceML;

use Veligost\CommerceML\Exceptions\SchemaException,
    Veligost\CommerceML\Cache\ElementRegistry,
    Veligost\CommerceML\Stereotypes\Component,
    DOMDocument,
    DOMElement,
    DateTime;

/**
 * Документ CommerceML
 */
class Document extends Component
{
    /**
     * @var string
     */
    protected $nodeName = 'КоммерческаяИнформация';

    /**
     * @var Cache\ElementRegistry
     */
    protected $registry;

    /**
     * @param DOMDocument $xml
     */
    public function __construct(DOMDocument $xml)
    {
        parent::__construct($xml->firstChild, $this);
        $this->registry = new ElementRegistry();
    }

    /**
     * Возвращает реестр элементов
     *
     * @return Cache\ElementRegistry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Возвращает версию схемы
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->element->getAttribute('ВерсияСхемы');
    }

    /**
     * Возвращает время создания
     *
     * @return DateTime
     */
    public function getTimeCreated()
    {
        return new DateTime($this->element->getAttribute('ДатаФормирования'));
    }
    /**
     * Возвращает классификатор или null
     *
     * @return Classifier|null
     */
    public function getClassifier()
    {
        return $this->getChild('Классификатор', 'Classifier');
    }
}
