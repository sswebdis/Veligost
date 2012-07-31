<?php
/**
 * Узел документа
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

use DOMElement,
    InvalidArgumentException;

/**
 * Узел документа
 */
abstract class Element
{
    /**
     * Имя узла
     *
     * Потомки могут указывать в этом свойстве имя узла, в этом случае в конструкторе будет
     * проводиться проверка на соответствие имени передаваемого узла имени, заданному в этом
     * свойстве.
     *
     * @var string|null
     */
    protected $nodeName = null;

    /**
     * @var DOMElement
     */
    protected $element;

    /**
     * @var Document
     */
    protected $doc;

    /**
     * @param DOMElement $element
     * @param Document   $doc
     *
     * @throws InvalidArgumentException
     */
    public function __construct(DOMElement $element, Document $doc = null)
    {
        if (null !== $this->nodeName && $element->nodeName != $this->nodeName)
        {
            throw new InvalidArgumentException(sprintf(
                'Ожидался узел "%s", но получен узел "%s"', $this->nodeName, $element->nodeName));
        }
        $this->element = $element;
        $this->doc = $doc;
    }

    /**
     * Возвращает первый дочерний элемент с указанным именем или null, если такого нет
     *
     * @param string $name
     *
     * @return null|DOMElement
     */
    protected function getChildElement($name)
    {
        foreach ($this->element->childNodes as $node)
        {
            /** @var \DOMElement $node */
            if ($node->nodeName == $name)
            {
                return $node;
            }
        }
        return null;
    }
}
