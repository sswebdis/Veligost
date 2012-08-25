<?php
/**
 * Стереотип «Message»
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

use Veligost\CommerceML\Exceptions\SchemaException;

/**
 * Стереотип «Message»
 */
abstract class Message extends Component
{
    /**
     * Возвращает идентификатор
     *
     * @throws SchemaException
     *
     * @return \Veligost\CommerceML\DataTypes\Id
     */
    public function getId()
    {
        $id = $this->getChild('Ид', 'DataTypes\Id');
        if (null === $id)
        {
            throw new SchemaException('У узла ' . $this->nodeName . ' отсутствует элемент Ид');
        }
        return $id;
    }

    /**
     * Возвращает наименование
     *
     * @throws Exceptions\SchemaException
     *
     * @return \Veligost\CommerceML\DataTypes\Title
     */
    public function getTitle()
    {
        $title = $this->getChild('Наименование', 'DataTypes\Title');
        if (null === $title)
        {
            throw new SchemaException('У ' . $this->nodeName . ' отсутствует элемент Наименование');
        }
        return $title;
    }

    /**
     * Возвращает описание
     *
     * @return \Veligost\CommerceML\DataTypes\Comment|null
     */
    public function getComment()
    {
        return $this->getChild('Описание', 'DataTypes\Comment');
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd