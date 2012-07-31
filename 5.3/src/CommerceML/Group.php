<?php
/**
 * Группа
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

use Veligost\CommerceML\Exceptions\SchemaException;

/**
 * Группа
 *
 * Компонентами «Группа» описывается иерархия групп (классификация) товаров в каталоге. Каждая
 * Группа может включать в себя любое количество вложенных групп. Для каждой группы могут быть
 * указаны свойства, которые затем могут быть описаны для товаров, принадлежащих как данной
 * группе, так и всем подчиненным группам. Таким образом, в группах свойства объявляются, а в
 * товарах — описываются, то есть указываются их значения.
 */
class Group extends Component
{
    /**
     * @var string
     */
    protected $nodeName = 'Группа';

    /**
     * Возвращает идентификатор группы
     *
     * @throws Exceptions\SchemaException
     *
     * @return Id
     */
    public function getId()
    {
        $id = $this->getChild('Ид', 'Id');
        if (null === $id)
        {
            throw new SchemaException('У Группы отсутствует элемент Ид');
        }
        return $id;
    }

    /**
     * Возвращает наименование группы
     *
     * @throws Exceptions\SchemaException
     *
     * @return Title
     */
    public function getTitle()
    {
        $title = $this->getChild('Наименование', 'Title');
        if (null === $title)
        {
            throw new SchemaException('У Группы отсутствует элемент Наименование');
        }
        return $title;
    }

    /**
     * Возвращает описание группы
     *
     * @throws Exceptions\SchemaException
     *
     * @return Comment
     */
    public function getComment()
    {
        return $this->getChild('Описание', 'Comment');
    }
}
