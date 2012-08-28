<?php
/**
 * Стереотип «Sequence»
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

namespace Veligost\CommerceML\Stereotypes;

use Veligost\CommerceML\Base\Element,
    Veligost\CommerceML\Base\SimpleElement,
    Veligost\CommerceML\Exceptions\SchemaException;

/**
 * Стереотип «Sequence»
 */
abstract class Sequence extends Element
{
    /**
     * Список возможных имён тегов (в ключах массива) и соответствующих им типов (в значениях)
     *
     * @var array
     */
    static protected $nodeNames = array();

    /**
     * Последовательность элементов
     *
     * @var Element[]
     */
    protected $sequence = array();

    /**
     * Извлекает последовательность из указанного элемента
     *
     * @param Component $element
     *
     * @throws \Veligost\CommerceML\Exceptions\SchemaException
     *
     * @return Sequence
     */
    public static function extractFromElement(Component $element)
    {
        $nodes = array();
        foreach (static::$nodeNames as $nodeName => $className)
        {
            $node = $element->getChild($nodeName, $className);
            if (null !== $node)
            {
                $nodes []= $node;
            }
        }

        if (count($nodes) == 0)
        {
            throw new SchemaException('У узла ' . $element->getDOMElement()->nodeName .
                ' обязательно должен быть хотя бы один из следующих элементов: ' .
                implode(', ', array_keys(static::$nodeNames)));
        }
        $targetClass = get_called_class();
        $seq = new $targetClass($nodes);
        return $seq;
    }

    /**
     * Конструктор
     *
     * Создаёт объект-последовательность на основе переданных элементов
     *
     * @param \Veligost\CommerceML\Base\Element[] $elements
     */
    public function __construct(array $elements)
    {
        $this->sequence = $elements;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        foreach ($this->sequence as $element)
        {
            if ($element instanceof SimpleElement)
            {
                return strval($this->sequence[0]);
            }
        }
        return '';
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd