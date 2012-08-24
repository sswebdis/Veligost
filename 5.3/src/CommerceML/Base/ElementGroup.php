<?php
/**
 * Простой элемент
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

namespace Veligost\CommerceML\Base;

/**
 * Группа элементов
 *
 * Элемент, содержащий набор элементов одного типа
 */
abstract class ElementGroup extends Element
{
    /**
     * Имя дочерних узлов
     *
     * Потомки должны указывать в этом свойстве имя дочерних узлов.
     *
     * @var string
     */
    protected $childNodeName = null;

    /**
     * Имя дочерних узлов
     *
     * Потомки должны указывать в этом свойстве имя класса дочерних узлов.
     *
     * @var string
     */
    protected $childClassName = null;

    /**
     * Возвращает массив дочерних элементов
     *
     * @return Element[]
     */
    public function getChildren()
    {
        $children = array();

        $registry = $this->doc->getRegistry();
        foreach ($this->getDOMElement()->childNodes as $node)
        {
            /** @var \DOMElement $node */
            if ($node->nodeName != $this->childNodeName)
            {
                continue;
            }

            $element = $registry->get($node);
            if (null === $element)
            {
                $className = '\Veligost\CommerceML\\' . $this->childClassName;
                $element = new $className($node, $this->doc);
                $registry->register($element);
            }
            $children []= $element;
        }

        return $children;
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd