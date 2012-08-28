<?php
/**
 * Товар
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

namespace Veligost\CommerceML\Components;

use Veligost\CommerceML\Stereotypes\Component,
    Veligost\CommerceML\Components\CommodityId,
    Veligost\CommerceML\Exceptions\SchemaException;

/**
 * Товар
 *
 * Компонент «Товар» описывает идентифицированный в каталоге товар. Товар может относиться к
 * нескольким группам. Товар может иметь аналоги (например, аналоги для лекарств и запчастей).
 */
class Commodity extends Component
{
    /**
     * @var string
     */
    protected $nodeName = 'Товар';

    /**
     * Возвращает идентификатор
     *
     * @throws Exceptions\SchemaException
     *
     * @return \Veligost\CommerceML\Components\CommodityId
     */
    public function getId()
    {
        $id = CommodityId::extractFromElement($this);
        return $id;
    }

    /**
     * Возвращает наименование
     *
     * @throws Exceptions\SchemaException
     *
     * @return \Veligost\CommerceML\Types\Title
     */
    public function getTitle()
    {
        $title = $this->getChild('Наименование', 'Types\Title');
        if (null === $title)
        {
            throw new SchemaException('У узла Товар отсутствует элемент Наименование');
        }
        return $title;
    }

    /**
     * Возвращает описание
     *
     * @throws Exceptions\SchemaException
     *
     * @return \Veligost\CommerceML\Types\Comment
     */
    public function getComment()
    {
        return $this->getChild('Описание', 'Types\Comment');
    }
}
