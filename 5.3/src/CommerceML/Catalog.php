<?php
/**
 * Каталог
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

use Veligost\CommerceML\Stereotypes\Message;

/**
 * Каталог
 *
 * Каталог содержит перечень товаров. Может составляться разными предприятиями. У каталога всегда
 * определен владелец. Товары в каталоге описываются по определенному классификатору.
 */
class Catalog extends Message
{
    /**
     * @var string
     */
    protected $nodeName = 'Каталог';

    /**
     * Возвращает товары
     *
     * @throws Exceptions\SchemaException
     *
     * @return Components\CommodityBlock
     */
    public function getArticles()
    {
        $articles = $this->getChild('Товары', 'Components\CommodityBlock');
        return $articles;
    }
}
